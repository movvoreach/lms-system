<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $roles = [
            [
                'role_name' => 'Administrator',
                'description' => 'គ្រប់គ្រងប្រព័ន្ធទាំងមូល'
            ],
            [
                'role_name' => 'Manager',
                'description' => 'គ្រប់គ្រង Courses និង Users'
            ],
            [
                'role_name' => 'Course Creator',
                'description' => 'បង្កើត Course'
            ],
            [
                'role_name' => 'Teacher',
                'description' => 'បង្រៀន និងគ្រប់គ្រងសិស្ស'
            ],
            [
                'role_name' => 'Non-editing Teacher',
                'description' => 'មើល និងដាក់ពិន្ទុ តែមិនអាចកែ Course'
            ],
            [
                'role_name' => 'Student',
                'description' => 'រៀន និងដាក់ Assignment'
            ],
            [
                'role_name' => 'Guest',
                'description' => 'មើល Course ខ្លះៗដោយគ្មានការកែប្រែ'
            ],
            [
                'role_name' => 'Authenticated User',
                'description' => 'User ដែល Login ចូលប្រព័ន្ធ'
            ],
            [
                'role_name' => 'Parent / Mentor',
                'description' => 'មើលលទ្ធផលសិស្ស'
            ],
        ];

        // Insert Roles
        foreach ($roles as $role) {

            DB::table('roles')->updateOrInsert(
                [
                    'role_name' => $role['role_name']
                ],
                [
                    'description' => $role['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Create Admin User
        $user = User::query()->updateOrCreate(
            [
                'email' => 'samphorstorng9999@gmail.com'
            ],
            [
                'name' => 'samphors',
                'username' => 'samphors',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Get Administrator Role ID
        $administratorRoleId = DB::table('roles')
            ->where('role_name', 'Administrator')
            ->value('role_id');

        // Assign Role to User
        if ($administratorRoleId) {

            $user->roles()->sync([$administratorRoleId]);
        }
    }
}
