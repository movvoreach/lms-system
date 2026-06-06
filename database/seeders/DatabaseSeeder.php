<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Course;
use App\Models\Role;
use App\Models\Student;
use App\Models\StudentCourseRegistration;
use App\Models\Teacher;
use App\Models\TeacherCourseAssignment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function (): void {
            $roles = $this->seedRoles();
            $permissions = $this->seedPermissions();

            $this->syncRolePermissions($roles, $permissions);
            $this->migrateLegacyAdminRole($roles);
            $this->seedDashboardUsers($roles);
        });
    }

    private function seedRoles(): array
    {
        $roles = [
            'Administrator' => 'Full system administrator access.',
            'Manager' => 'Manage LMS academic records, users, and courses.',
            'Course Creator' => 'Create and manage course content.',
            'Teacher' => 'Teach courses and manage assigned learning materials.',
            'Non-editing Teacher' => 'View courses and grade students without editing courses.',
            'Student' => 'Learn from courses and submit course work.',
            'Guest' => 'View limited public course content.',
            'Authenticated User' => 'Default role for logged-in users.',
            'Parent / Mentor' => 'View related student progress.',
        ];

        $createdRoles = [];

        foreach ($roles as $roleName => $description) {
            $createdRoles[$roleName] = Role::query()->updateOrCreate(
                ['role_name' => $roleName],
                ['description' => $description]
            );
        }

        return $createdRoles;
    }

    private function seedPermissions(): array
    {
        $permissions = [
            'dashboard.access' => 'Access the dashboard.',
            'users.manage' => 'Create, update, and delete users.',
            'roles.manage' => 'Manage role and permission assignments.',
            'students.view' => 'View student records.',
            'students.manage' => 'Create, update, and delete student records.',
            'teachers.view' => 'View teacher records.',
            'teachers.manage' => 'Create, update, and delete teacher records.',
            'courses.view' => 'View courses.',
            'courses.manage' => 'Create, update, and delete courses.',
            'lesson_contents.view' => 'View lesson content.',
            'lesson_contents.manage' => 'Create, update, and delete lesson content.',
            'grades.manage' => 'Grade students and manage course completion.',
            'certificates.request' => 'Request certificates for completed students.',
            'certificates.manage' => 'Approve or reject certificate requests.',
            'learning_issues.create' => 'Submit learning issue reports.',
            'learning_issues.view' => 'View learning issue reports.',
            'learning_issues.reply' => 'Reply to learning issue reports and update progress.',
            'learning_issues.analytics' => 'View learning issue analytics.',
            'announcements.view' => 'View announcements.',
            'announcements.manage' => 'Create, publish, archive, and delete announcements.',
            'activity_logs.view' => 'View and export system activity logs.',
            'academic.manage' => 'Manage faculties, departments, semesters, and academic years.',
        ];

        $createdPermissions = [];

        foreach ($permissions as $permissionCode => $description) {
            $createdPermissions[$permissionCode] = Permission::query()->updateOrCreate(
                ['permission_code' => $permissionCode],
                ['description' => $description]
            );
        }

        return $createdPermissions;
    }

    private function syncRolePermissions(array $roles, array $permissions): void
    {
        $permissionSets = [
            'Administrator' => array_keys($permissions),
            'Manager' => [
                'dashboard.access',
                'users.manage',
                'students.view',
                'students.manage',
                'teachers.view',
                'teachers.manage',
                'courses.view',
                'courses.manage',
                'lesson_contents.view',
                'lesson_contents.manage',
                'grades.manage',
                'certificates.request',
                'certificates.manage',
                'learning_issues.create',
                'learning_issues.view',
                'learning_issues.reply',
                'learning_issues.analytics',
                'announcements.view',
                'announcements.manage',
                'activity_logs.view',
                'academic.manage',
            ],
            'Course Creator' => [
                'dashboard.access',
                'courses.view',
                'courses.manage',
                'lesson_contents.view',
                'lesson_contents.manage',
                'learning_issues.view',
                'announcements.view',
                'announcements.manage',
            ],
            'Teacher' => [
                'dashboard.access',
                'students.view',
                'courses.view',
                'lesson_contents.view',
                'lesson_contents.manage',
                'grades.manage',
                'certificates.request',
                'learning_issues.view',
                'learning_issues.reply',
                'learning_issues.analytics',
                'announcements.view',
                'announcements.manage',
            ],
            'Non-editing Teacher' => [
                'dashboard.access',
                'students.view',
                'courses.view',
                'lesson_contents.view',
                'grades.manage',
                'certificates.request',
                'learning_issues.view',
                'learning_issues.reply',
                'announcements.view',
            ],
            'Student' => [
                'dashboard.access',
                'courses.view',
                'lesson_contents.view',
                'learning_issues.create',
                'learning_issues.view',
                'announcements.view',
            ],
            'Guest' => [
                'courses.view',
                'lesson_contents.view',
                'announcements.view',
            ],
            'Authenticated User' => [
                'dashboard.access',
            ],
            'Parent / Mentor' => [
                'dashboard.access',
                'students.view',
                'courses.view',
                'announcements.view',
            ],
        ];

        foreach ($permissionSets as $roleName => $permissionCodes) {
            if (! isset($roles[$roleName])) {
                continue;
            }

            $permissionIds = collect($permissionCodes)
                ->map(fn (string $code) => $permissions[$code]->permission_id ?? null)
                ->filter()
                ->values()
                ->all();

            $roles[$roleName]->permissions()->sync($permissionIds);
        }
    }

    private function seedDashboardUsers(array $roles): void
    {
        $admin = $this->seedUser('admin', 'admin@example.com', $roles['Administrator']);
        $teacherUser = $this->seedUser('teacher', 'teacher@example.com', $roles['Teacher']);
        $studentUser = $this->seedUser('student', 'student@example.com', $roles['Student']);
        $courseId = Course::query()->oldest('id')->value('id');

        $teacher = Teacher::query()->updateOrCreate(
            ['user_id' => $teacherUser->user_id],
            [
                'course_id' => $courseId,
                'employee_number' => 'TCH-0001',
                'first_name' => 'Demo',
                'last_name' => 'Teacher',
                'specialization' => 'Learning Management',
                'academic_rank' => 'Instructor',
                'phone' => '010000001',
                'address' => 'Phnom Penh',
                'status' => 'active',
            ]
        );

        $student = Student::query()->updateOrCreate(
            ['user_id' => $studentUser->user_id],
            [
                'course_id' => $courseId,
                'student_number' => 'STD-0001',
                'first_name' => 'Demo',
                'last_name' => 'Student',
                'gender' => 'Other',
                'date_of_birth' => '2004-01-01',
                'phone' => '010000002',
                'address' => 'Phnom Penh',
                'status' => 'active',
            ]
        );

        if ($courseId) {
            TeacherCourseAssignment::query()->updateOrCreate(
                [
                    'teacher_id' => $teacher->teacher_id,
                    'course_id' => $courseId,
                ],
                [
                    'status' => 'assigned',
                    'assigned_at' => now(),
                    'notes' => 'Demo teacher assignment',
                ]
            );

            StudentCourseRegistration::query()->updateOrCreate(
                [
                    'student_id' => $student->student_id,
                    'course_id' => $courseId,
                ],
                [
                    'academic_year_id' => null,
                    'status' => 'registered',
                    'registered_at' => now(),
                    'notes' => 'Demo student registration',
                ]
            );
        }

        $admin->roles()->sync([$roles['Administrator']->role_id]);
    }

    private function migrateLegacyAdminRole(array $roles): void
    {
        if (! isset($roles['Administrator'])) {
            return;
        }

        $legacyAdminRole = Role::query()->where('role_name', 'Admin')->first();

        if (! $legacyAdminRole) {
            return;
        }

        $legacyAdminRole->users()
            ->each(fn (User $user) => $user->roles()->syncWithoutDetaching([
                $roles['Administrator']->role_id,
            ]));
    }

    private function seedUser(string $username, string $email, Role $role): User
    {
        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'username' => $username,
                'password' => Hash::make('password'),
                'is_active' => true,
                'two_factor_enabled' => false,
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]
        );

        $user->roles()->sync([$role->role_id]);

        return $user;
    }
}
