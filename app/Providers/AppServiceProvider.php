<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\LessonContent;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasPermission($ability) ? true : null;
        });

        $this->registerActivityModelEvents();
    }

    private function registerActivityModelEvents(): void
    {
        $models = [
            AcademicYear::class,
            Announcement::class,
            Course::class,
            CourseCategory::class,
            Department::class,
            Faculty::class,
            LessonContent::class,
            Permission::class,
            Role::class,
            Semester::class,
            Student::class,
            Teacher::class,
            User::class,
        ];

        foreach ($models as $model) {
            if (! is_subclass_of($model, Model::class)) {
                continue;
            }

            $model::created(fn (Model $record) => $this->logModelActivity('create', $record));
            $model::updated(function (Model $record): void {
                $changes = collect($record->getChanges())
                    ->except([
                        'updated_at',
                        'remember_token',
                        'two_factor_code',
                        'two_factor_expires_at',
                    ]);

                if ($changes->isEmpty()) {
                    return;
                }

                if ($record instanceof User && $record->wasChanged('password')) {
                    $this->logModelActivity('password_change', $record);
                    $changes = $changes->except('password');
                }

                if ($changes->isEmpty()) {
                    return;
                }

                $this->logModelActivity('update', $record, $changes->all());
            });
            $model::deleted(fn (Model $record) => $this->logModelActivity('delete', $record));
        }
    }

    private function logModelActivity(string $action, Model $record, array $changes = []): void
    {
        if ($record instanceof ActivityLog || ! function_exists('activity_log')) {
            return;
        }

        $module = class_basename($record);
        $label = $this->recordLabel($record);

        $actionForLog = in_array($module, ['Role', 'Permission'], true) ? 'role_permission' : $action;
        $verb = str($action)->replace('_', ' ')->title();

        $description = "{$verb} {$module}: {$label}";

        if ($action === 'update' && $changes !== []) {
            $description .= ' | Changed: ' . $this->formatChanges($record, $changes);
        }

        activity_log($actionForLog, $module, $description);
    }

    private function recordLabel(Model $record): string
    {
        foreach (['title', 'name', 'role_name', 'permission_code', 'username', 'email', 'student_number', 'employee_number'] as $field) {
            if (filled($record->getAttribute($field))) {
                return (string) $record->getAttribute($field);
            }
        }

        return '#' . $record->getKey();
    }

    private function formatChanges(Model $record, array $changes): string
    {
        return collect($changes)
            ->map(function ($newValue, string $field) use ($record): string {
                $oldValue = $record->getOriginal($field);

                return str($field)->replace('_', ' ')->title()
                    . ': '
                    . $this->formatValue($oldValue)
                    . ' -> '
                    . $this->formatValue($newValue);
            })
            ->implode('; ');
    }

    private function formatValue(mixed $value): string
    {
        if (is_array($value)) {
            return '[' . collect($value)->map(fn ($item) => $this->formatValue($item))->implode(', ') . ']';
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if ($value === null || $value === '') {
            return '-';
        }

        if (is_scalar($value)) {
            return str((string) $value)->limit(80)->toString();
        }

        return str(json_encode($value, JSON_UNESCAPED_UNICODE) ?: '-')->limit(80)->toString();
    }
}
