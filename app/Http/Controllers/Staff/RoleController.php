<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Role::with('permissions')->get();
        $permissions = Permission::all();

        return self::getViewStaff('Roles', [
            'data' => $data,
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'type' => 'required|string|in:admin,staff,client',
            'guard_name' => 'required|string|max:255',
        ]);

        $role = Role::create($data);

        return redirect()->route('panel.roles.index')->with('success', 'Role created successfully!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'type' => 'required|string|in:admin,staff,client',
            'guard_name' => 'required|string|max:255',
        ]);

        $role->update($data);

        return redirect()->route('panel.roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Get permissions for a specific role.
     */
    public function permissions(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Update permissions for a specific role.
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);
        return response()->json(['success' => true]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('panel.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
