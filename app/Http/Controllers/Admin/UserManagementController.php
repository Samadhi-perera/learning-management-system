<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('department.faculty');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('identifier', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,lecturer,student'],
            'identifier' => ['nullable', 'string', 'max:50', 'unique:users,identifier'],
            'phone' => ['nullable', 'string', 'max:20'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'identifier' => $request->identifier,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'status' => 'active',
        ]);

        $roleModel = Role::where('slug', $request->role)->first();
        if ($roleModel) {
            $user->roles()->sync([$roleModel->id]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $departments = Department::orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,lecturer,student'],
            'identifier' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,suspended'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'identifier' => $request->identifier,
            'phone' => $request->phone,
            'status' => $request->status,
            'department_id' => $request->department_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sync role pivot
        $roleModel = Role::where('slug', $request->role)->first();
        if ($roleModel) {
            $user->roles()->sync([$roleModel->id]);
        }

        return redirect()->route('admin.users.index')->with('success', "User '{$user->name}' updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "User '{$userName}' deleted successfully.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        return back()->with('success', 'User status updated to ' . $user->status . '.');
    }
}
