<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientRoles = Role::where('type', 'client')->pluck('name')->toArray();
        $data = User::where('id', '!=', $this->user->id)
            ->active()
            ->role($clientRoles)
            ->get();
        $roles = Role::where('guard_name', 'web')
            ->where('type', 'client')
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                ];
            })
            ->toArray();
        return self::getViewStaff('Clients', [
            'data' => $data,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mobile'     => 'required|regex:/^09\d{9}$/|unique:users,mobile',
            'username'   => 'required|string|max:50|unique:users,username',
            'email'      => 'required|email|unique:users,email',
            'role_id'    => 'required|exists:roles,id',
            'status'     => 'required|in:active,inactive',
            'password'   => 'required|string|min:6',
        ]);
        $data['password'] = bcrypt($data['password'] ?? 'password123');
        $role_id = $data['role_id'] ?? 1;
        $user = User::create($data);
        $user->assignRole($role_id);

        return redirect()->route('panel.clients.index')->with('success', 'User created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $client)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mobile'     => 'required|regex:/^09\d{9}$/|unique:users,mobile',
            'username'   => 'required|string|max:50|unique:users,username,' . $client->id,
            'email'      => 'required|email|unique:users,email,' . $client->id,
            'role_id'    => 'required|exists:roles,id',
            'status'     => 'required|in:active,inactive,banned',
        ]);

        $client->update($data);

        // نقش را هم بروزرسانی کنیم
        if (isset($data['role_id'])) {
            $client->syncRoles([$data['role_id']]);
        }

        return redirect()->route('panel.clients.index')->with('success', 'User updated successfully!');
    }

    /**
     * Update the password of the specified resource in storage.
     */
    public function updatePassword(Request $request, User $client)
    {
        $data = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $client->update([
            'password' => bcrypt($data['password']),
        ]);

        return redirect()->route('panel.clients.index')->with('success', 'Password updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $client)
    {
        $client->delete();

        return redirect()->route('panel.clients.index')
            ->with('success', 'Clients deleted successfully!');
    }
}
