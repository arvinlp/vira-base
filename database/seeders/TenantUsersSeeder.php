<?php

use Illuminate\Database\Seeder;
use App\Models\Tenant\User as TenantUser;
use App\Models\Tenant;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class TenantUsersSeeder extends Seeder
{
    public function run(): void
    {
        // مثال: گرفتن اولین tenant
        $tenant = Tenant::first();

        if (!$tenant) {
            $this->command->error('No tenants found! Please create a tenant first.');
            return;
        }

        // تعریف Permissions (tenant-specific guard)
        $permissions = [
            'manage users',
            'view reports',
            'activate modules',
            'manage settings'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm, 'guard_name' => 'tenant']
            );
        }

        // تعریف Roles و اختصاص Permissions
        $roles = [
            'owner' => $permissions, // دسترسی کامل
            'admin' => ['manage users', 'view reports', 'activate modules'],
            'manager' => ['view reports'],
            'staff' => []
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'tenant']);
            $role->syncPermissions($rolePerms);
        }

        // ایجاد Tenant Users نمونه
        $users = [
            [
                'first_name' => 'Tenant',
                'last_name' => 'Owner',
                'username' => 'owner1',
                'email' => 'owner1@example.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'status' => 'active',
            ],
            [
                'first_name' => 'Tenant',
                'last_name' => 'Admin',
                'username' => 'admin1',
                'email' => 'admin1@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'first_name' => 'Tenant',
                'last_name' => 'Manager',
                'username' => 'manager1',
                'email' => 'manager1@example.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'status' => 'active',
            ],
            [
                'first_name' => 'Tenant',
                'last_name' => 'Staff',
                'username' => 'staff1',
                'email' => 'staff1@example.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            $user = TenantUser::firstOrCreate(
                ['tenant_id' => $tenant->id, 'username' => $userData['username']],
                array_merge($userData, ['tenant_id' => $tenant->id])
            );
            $user->assignRole($userData['role'], 'tenant');
        }

        $this->command->info('Tenant users and roles seeded successfully!');
    }
}
