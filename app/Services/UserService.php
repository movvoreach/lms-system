<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class UserService
{
    public function getAll(): Collection
    {
        return User::query()
            ->with('roles')
            ->latest('user_id')
            ->get();
    }

    public function store(array $data): User
    {
        $roleIds = Arr::pull($data, 'roles', []);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        try {
            return DB::transaction(function () use ($data, $roleIds) {
                $user = User::create($data);
                $user->roles()->sync($roleIds);
                activity_log('role_permission', 'User', "Roles assigned to user: {$user->username} | Roles: {$this->roleNames($roleIds)}");

                return $user;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create user.', ['data' => Arr::except($data, 'password'), 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to create user. Please try again.', 0, $exception);
        }
    }

    public function update(int $id, array $data): User
    {
        $roleIds = Arr::pull($data, 'roles', []);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        try {
            return DB::transaction(function () use ($id, $data, $roleIds) {
                $user = $this->findById($id);
                $oldRoleIds = $user->roles->pluck('role_id')->sort()->values()->all();
                $oldRoleNames = $this->roleNames($oldRoleIds);
                $user->update($data);
                $user->roles()->sync($roleIds);

                if ($oldRoleIds !== collect($roleIds)->map(fn ($roleId) => (int) $roleId)->sort()->values()->all()) {
                    activity_log('role_permission', 'User', "Role permissions changed for user: {$user->username} | From: {$oldRoleNames} | To: {$this->roleNames($roleIds)}");
                }

                return $user;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update user.', ['user_id' => $id, 'data' => Arr::except($data, 'password'), 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to update user. Please try again.', 0, $exception);
        }
    }

    public function delete(int $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                $user = $this->findById($id);
                $roleNames = $this->roleNames($user->roles->pluck('role_id')->all());
                $user->roles()->detach();
                activity_log('role_permission', 'User', "Roles detached from deleted user: {$user->username} | Roles: {$roleNames}");

                return (bool) $user->delete();
            });
        } catch (Throwable $exception) {
            Log::error('Failed to delete user.', ['user_id' => $id, 'error' => $exception->getMessage()]);

            throw new RuntimeException('Unable to delete user. Please try again.', 0, $exception);
        }
    }

    public function findById(int $id): User
    {
        return User::query()->with('roles')->findOrFail($id);
    }

    private function roleNames(array $roleIds): string
    {
        $ids = collect($roleIds)
            ->map(fn ($roleId) => (int) $roleId)
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return '-';
        }

        return Role::query()
            ->whereIn('role_id', $ids)
            ->orderBy('role_name')
            ->pluck('role_name')
            ->implode(', ') ?: '-';
    }
}
