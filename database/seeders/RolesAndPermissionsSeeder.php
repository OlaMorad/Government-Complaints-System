<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'send complaint',
            'process complaint',
            'edit complaint information',
            'edit complaint status',
            'view complaints',
            'view user activities'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
     $roles = [
            'Super Admin' => [  'edit complaint status', 'view complaints', 'view user activities'],
            'Employee' => [ 'process complaint', 'view complaints'],
            'Citizen' => ['send complaint', 'edit complaint information']
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('Roles and permissions have been seeded successfully.');
    }

    }

