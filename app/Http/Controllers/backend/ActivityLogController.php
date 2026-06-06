<?php

namespace App\Http\Controllers\backend;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index()
    {
        $actions = ActivityLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');
        $modules = ActivityLog::query()->select('module')->distinct()->orderBy('module')->pluck('module');

        return view('activity-log.index', compact('actions', 'modules'));
    }

    public function data(Request $request)
    {
        $baseQuery = $this->filteredQuery($request);
        $recordsTotal = ActivityLog::query()->count();
        $recordsFiltered = (clone $baseQuery)->count();

        $logs = $baseQuery
            ->latest('activity_logs.created_at')
            ->skip((int) $request->input('start', 0))
            ->take((int) $request->input('length', 10))
            ->get();

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $logs->map(fn (ActivityLog $log) => $this->row($log))->values(),
        ]);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $fileName = 'activity-logs-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($request): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'User', 'Action', 'Module', 'Description', 'IP Address', 'User Agent']);

            $this->filteredQuery($request)
                ->latest('activity_logs.created_at')
                ->chunk(500, function ($logs) use ($handle): void {
                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->created_at?->format('Y-m-d H:i:s'),
                            $log->user->username ?? 'System',
                            $log->action,
                            $log->module,
                            $log->description,
                            $log->ip_address,
                            $log->user_agent,
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportPdf(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latest('activity_logs.created_at')
            ->limit(1000)
            ->get();

        return view('activity-log.pdf', compact('logs'));
    }

    private function filteredQuery(Request $request): Builder
    {
        $query = ActivityLog::query()->with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('module')) {
            $query->where('module', $request->input('module'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('activity_logs.created_at', '>=', $request->date('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('activity_logs.created_at', '<=', $request->date('date_to'));
        }

        $search = $this->searchTerm($request);

        if (filled($search)) {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function (Builder $query) use ($search): void {
                        $query->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    private function searchTerm(Request $request): ?string
    {
        $search = $request->input('search');

        if (is_array($search)) {
            $search = $search['value'] ?? null;
        }

        if (is_string($search) && filled($search)) {
            return $search;
        }

        $fallback = $request->input('keyword');

        return is_string($fallback) && filled($fallback) ? $fallback : null;
    }

    private function row(ActivityLog $log): array
    {
        $meta = ActivityLog::actionMeta($log->action);

        return [
            'created_at' => e($log->created_at?->format('Y-m-d H:i:s')),
            'user' => e($log->user->username ?? 'System'),
            'action' => '<span class="badge badge-' . e($meta['badge']) . '"><i class="' . e($meta['icon']) . ' mr-1"></i>' . e(str($log->action)->replace('_', ' ')->title()) . '</span>',
            'module' => e($log->module),
            'description' => $this->formatDescription($log->description),
            'ip_address' => e($log->ip_address ?? '-'),
            'user_agent' => '<span title="' . e($log->user_agent ?? '-') . '">' . e(str($log->user_agent ?? '-')->limit(55)) . '</span>',
        ];
    }

    private function formatDescription(string $description): string
    {
        $parts = collect(explode(' | ', $description))
            ->map(fn (string $part) => trim($part))
            ->filter()
            ->values();

        if ($parts->count() <= 1) {
            return e($description);
        }

        $main = e($parts->shift());
        $items = $parts
            ->flatMap(function (string $part) {
                if (str_starts_with($part, 'Changed: ')) {
                    return collect(explode('; ', str_replace('Changed: ', '', $part)))
                        ->map(fn (string $change) => 'Changed: ' . $change);
                }

                return [$part];
            })
            ->map(fn (string $part) => '<li>' . e($part) . '</li>')
            ->implode('');

        return '<strong>' . $main . '</strong><ul class="mb-0 pl-3">' . $items . '</ul>';
    }
}


