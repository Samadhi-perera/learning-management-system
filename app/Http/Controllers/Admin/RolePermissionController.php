<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::with('permissions')->withCount('users')->get();
        $permissions = Permission::all()->groupBy('group');

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:roles,slug',
            'description' => 'nullable|string',
        ]);

        $slug = $validated['slug'] ? Str::slug($validated['slug']) : Str::slug($validated['name']);

        Role::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'is_system' => false,
        ]);

        return redirect()->route('admin.roles.index')->with('success', "Role [{$validated['name']}] created successfully.");
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $permissionGroups = Permission::all()->groupBy('group');
        $activePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionGroups', 'activePermissionIds'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', "Permissions for [{$role->name}] updated successfully.");
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return back()->with('error', 'Core system roles cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', "Role [{$role->name}] removed.");
    }
}
