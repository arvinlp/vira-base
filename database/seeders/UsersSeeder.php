<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start central users and roles seeded ...');

        // تعریف Permissions (tenant-specific guard)
        $permissions = [
            'users-add',
            'users-edit',
            'users-view',
            'users-list',
            'users-delete',
            'users-switch',

            'clients-add',
            'clients-edit',
            'clients-view',
            'clients-list',
            'clients-delete',
            'clients-switch',

            'tenant-add',
            'tenant-edit',
            'tenant-delete',
            'tenant-view',
            'tenant-list',
            'tenant-activate',
            'tenant-deactivate',
            'tenant-switch',
            'tenant-database-reset',
            'tenant-database-migrate',
            'tenant-database-seed',
            'tenant-database-backup',
            'tenant-database-restore',

            'tenant-domain-add',
            'tenant-domain-edit',
            'tenant-domain-delete',
            'tenant-domain-view',
            'tenant-domain-list',
            'tenant-domain-set-primary',
            'tenant-domain-clear-primary',

            'reports-view',
            'reports-export',

            'factors-add',
            'factors-edit',
            'factors-delete',
            'factors-view',
            'factors-list',

            'plans-add',
            'plans-edit',
            'plans-delete',
            'plans-view',
            'plans-list',

            'modules-add',
            'modules-edit',
            'modules-delete',
            'modules-view',
            'modules-list',

            'role-add',
            'role-edit',
            'role-delete',
            'role-view',
            'role-list',

            'permission-add',
            'permission-edit',
            'permission-delete',
            'permission-view',
            'permission-list',

            'settings'
        ];

        $staffPermissions = [
            'clients-add',
            'clients-edit',
            'clients-view',
            'clients-list',
            'clients-switch',

            'tenant-add',
            'tenant-edit',
            'tenant-view',
            'tenant-list',
            'tenant-activate',
            'tenant-deactivate',
            'tenant-switch',
            'tenant-database-reset',
            'tenant-database-migrate',
            'tenant-database-seed',
            'tenant-database-backup',
            'tenant-database-restore',

            'tenant-domain-add',
            'tenant-domain-edit',
            'tenant-domain-view',
            'tenant-domain-list',
            'tenant-domain-set-primary',
            'tenant-domain-clear-primary',

            'reports-view',
            'reports-export',

            'factors-add',
            'factors-edit',
            'factors-view',
            'factors-list',

            'plans-add',
            'plans-edit',
            'plans-view',
            'plans-list',

            'modules-add',
            'modules-view',
            'modules-list',

        ];

        $clientPermissions = [
            'modules-list',
            'modules-view',
            'plans-list',
            'plans-view',

            'tenant-add',
            'tenant-edit',
            'tenant-view',
            'tenant-list',
            'tenant-activate',
            'tenant-deactivate',
            'tenant-switch',
            'tenant-database-reset',
            'tenant-database-migrate',
            'tenant-database-seed',
            'tenant-database-backup',
            'tenant-database-restore',
            'tenant-domain-add',
            'tenant-domain-edit',
            'tenant-domain-view',
            'tenant-domain-list',
            'tenant-domain-set-primary',
            'tenant-domain-clear-primary',

            'factors-add',
            'factors-edit',
            'factors-view',
            'factors-list',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm, 'guard_name' => 'web']
            );
        }

        // تعریف Roles و اختصاص Permissions
        $roles = [
            'admin' => $permissions,
            'staff' => $staffPermissions,
            'client' => $clientPermissions
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePerms);
        }

        // ایجاد Super Admin نمونه
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => 1,
                'status' => 'active',
            ],
            [
                'first_name' => 'Staff',
                'last_name' => 'User System',
                'username' => 'staff',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2,
                'status' => 'active',
            ],
            [
                'first_name' => 'Client',
                'last_name' => 'TenantManager',
                'username' => 'client',
                'email' => 'client@example.com',
                'password' => Hash::make('password'),
                'role_id' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                array_merge($userData)
            );
            $user->assignRole($userData['role_id']);
        }

        $this->command->info('Central users and roles seeded successfully!');
    }
}
