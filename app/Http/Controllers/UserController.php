<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest\StoreUserRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;
use App\Models\Role;
use App\Services\UserService;
use Throwable;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index()
    {
        $users = $this->userService->getAll();

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::query()->orderBy('role_name')->get();

        return view('user.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $this->userService->store($request->validated());

            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        $user = $this->userService->findById((int) $id);
        $roles = Role::query()->orderBy('role_name')->get();
        $selectedRoles = $user->roles->pluck('role_id')->all();

        return view('user.edit', compact('user', 'roles', 'selectedRoles'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $this->userService->update((int) $id, $request->validated());

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (Throwable $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->delete((int) $id);

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
        } catch (Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
