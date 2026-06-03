<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use App\Models\User;
use App\Notifications\AnnouncementPublishedNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('announcement.index', [
            'statuses' => $this->statuses(),
            'priorities' => $this->priorities(),
            'targets' => $this->targets(),
        ]);
    }

    public function data(Request $request)
    {
        $announcements = Announcement::query()
            ->with(['creator', 'course'])
            ->when(! $request->user()->can('announcements.manage'), fn (Builder $query) => $this->visibleToUser($query, $request->user()))
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->status))
            ->when($request->filled('priority'), fn (Builder $query) => $query->where('priority', $request->priority))
            ->when($request->filled('target_type'), fn (Builder $query) => $query->where('target_type', $request->target_type))
            ->latest('announcement_id')
            ->get()
            ->map(fn (Announcement $announcement) => [
                'id' => $announcement->announcement_id,
                'title' => e($announcement->title),
                'target' => e($this->targets()[$announcement->target_type] ?? $announcement->target_type),
                'course' => e($announcement->course->title ?? 'N/A'),
                'priority' => view('announcement.partials.badge', ['type' => 'priority', 'value' => $announcement->priority])->render(),
                'status' => view('announcement.partials.badge', ['type' => 'status', 'value' => $announcement->status])->render(),
                'creator' => e($announcement->creator->username ?? 'N/A'),
                'created_at' => $announcement->created_at?->format('Y-m-d H:i'),
                'publish_at' => $announcement->publish_at?->format('Y-m-d H:i') ?? 'Now',
                'action' => view('announcement.partials.actions', compact('announcement'))->render(),
            ]);

        return response()->json(['data' => $announcements]);
    }

    public function create()
    {
        return view('announcement.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement = Announcement::query()->create(array_merge($validated, [
            'created_by_user_id' => $request->user()->user_id,
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]));

        if ($announcement->status === 'published') {
            $this->sendAnnouncementNotifications($announcement);
        }

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function show(Request $request, Announcement $announcement)
    {
        if (! $request->user()->can('announcements.manage')) {
            abort_unless($this->announcementVisibleToUser($announcement, $request->user()), 403);
        }

        $announcement->load(['creator', 'course']);

        return view('announcement.show', compact('announcement'));
    }

    public function showFromNotification(Request $request, Announcement $announcement)
    {
        $request->user()->unreadNotifications()
            ->where('data->announcement_id', $announcement->announcement_id)
            ->get()
            ->markAsRead();

        return redirect()->route('admin.announcements.show', $announcement);
    }

    public function edit(Announcement $announcement)
    {
        return view('announcement.edit', array_merge($this->formData(), compact('announcement')));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $this->validated($request);
        $wasPublished = $announcement->status === 'published';

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }

            $validated['attachment_path'] = $request->file('attachment')->store('announcements', 'public');
        }

        $announcement->update(array_merge($validated, [
            'published_at' => $validated['status'] === 'published'
                ? ($announcement->published_at ?? now())
                : null,
            'archived_at' => null,
        ]));

        if (! $wasPublished && $announcement->status === 'published') {
            $this->sendAnnouncementNotifications($announcement);
        }

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function publish(Announcement $announcement)
    {
        $announcement->update([
            'status' => 'published',
            'published_at' => $announcement->published_at ?? now(),
            'archived_at' => null,
        ]);

        $this->sendAnnouncementNotifications($announcement);

        return back()->with('success', 'Announcement published and notifications sent.');
    }

    public function archive(Announcement $announcement)
    {
        $announcement->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        return back()->with('success', 'Announcement archived.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }

        $announcement->delete();

        return back()->with('success', 'Announcement deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'target_type' => ['required', 'in:all,teachers,students,course'],
            'course_id' => ['nullable', 'required_if:target_type,course', 'exists:courses,id'],
            'publish_at' => ['nullable', 'date'],
            'expire_at' => ['nullable', 'date', 'after_or_equal:publish_at'],
            'status' => ['required', 'in:draft,published'],
        ]);
    }

    private function sendAnnouncementNotifications(Announcement $announcement): void
    {
        $recipients = $this->recipients($announcement);

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new AnnouncementPublishedNotification($announcement));
        }
    }

    private function recipients(Announcement $announcement)
    {
        return User::query()
            ->where('is_active', true)
            ->when($announcement->target_type === 'teachers', function ($query) {
                $query->whereHas('roles', fn ($query) => $query->whereIn('role_name', ['Teacher', 'Non-editing Teacher']));
            })
            ->when($announcement->target_type === 'students', function ($query) {
                $query->whereHas('roles', fn ($query) => $query->where('role_name', 'Student'));
            })
            ->when($announcement->target_type === 'course', function ($query) use ($announcement) {
                $query->where(function ($query) use ($announcement) {
                    $query->whereHas('student.courseRegistrations', fn ($query) => $query->where('course_id', $announcement->course_id))
                        ->orWhereHas('teacher.courseAssignments', fn ($query) => $query->where('course_id', $announcement->course_id));
                });
            })
            ->get();
    }

    private function visibleToUser(Builder $query, User $user): Builder
    {
        $query->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('publish_at')->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expire_at')->orWhere('expire_at', '>=', now());
            })
            ->whereNull('archived_at');

        if ($user->hasRole('Student')) {
            $courseIds = $user->student?->courseRegistrations()->pluck('course_id')->all() ?? [];

            return $query->where(function ($query) use ($courseIds) {
                $query->whereIn('target_type', ['all', 'students'])
                    ->orWhere(function ($query) use ($courseIds) {
                        $query->where('target_type', 'course')->whereIn('course_id', $courseIds);
                    });
            });
        }

        if ($user->hasAnyRole(['Teacher', 'Non-editing Teacher'])) {
            $courseIds = $user->teacher?->courseAssignments()->pluck('course_id')->all() ?? [];

            return $query->where(function ($query) use ($courseIds) {
                $query->whereIn('target_type', ['all', 'teachers'])
                    ->orWhere(function ($query) use ($courseIds) {
                        $query->where('target_type', 'course')->whereIn('course_id', $courseIds);
                    });
            });
        }

        return $query->where('target_type', 'all');
    }

    private function announcementVisibleToUser(Announcement $announcement, User $user): bool
    {
        return $this->visibleToUser(Announcement::query()->whereKey($announcement->getKey()), $user)->exists();
    }

    private function formData(): array
    {
        return [
            'courses' => Course::query()->orderBy('title')->get(),
            'priorities' => $this->priorities(),
            'targets' => $this->targets(),
            'statuses' => ['draft' => 'Draft', 'published' => 'Published'],
        ];
    }

    private function priorities(): array
    {
        return ['low' => 'Low', 'normal' => 'Normal', 'high' => 'High', 'urgent' => 'Urgent'];
    }

    private function targets(): array
    {
        return ['all' => 'All users', 'teachers' => 'Only teachers', 'students' => 'Only students', 'course' => 'Specific course/class'];
    }

    private function statuses(): array
    {
        return ['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'];
    }
}
