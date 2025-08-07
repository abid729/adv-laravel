<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $userRole = Role::create(['name' => 'user']);

        // Create Permissions
        $permissions = [
            'access-admin',
            'view-tasks',
            'create-tasks',
            'edit-all-tasks',
            'delete-tasks',
            'complete-tasks',
            'assign-tasks',
            'view-all-tasks',
            'view-all-teams'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $adminRole->permissions()->sync(Permission::all()->pluck('id'));
        $managerRole->permissions()->sync(
            Permission::whereIn('name', ['view-tasks', 'create-tasks', 'edit-all-tasks', 'delete-tasks', 'assign-tasks'])->pluck('id')
        );
        $userRole->permissions()->sync(
            Permission::whereIn('name', ['view-tasks', 'create-tasks'])->pluck('id')
        );

        // Create Users and Assign Roles
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        $admin->roles()->attach($adminRole);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password')
        ]);
        $manager->roles()->attach($managerRole);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);
        $user->roles()->attach($userRole);

        // Create Teams
        $team1 = Team::create(['name' => 'Development Team']);
        $team2 = Team::create(['name' => 'Marketing Team']);

        // Assign Users to Teams
        $admin->teams()->attach($team1, ['role' => 'leader']);
        $manager->teams()->attach($team1, ['role' => 'member']);
        $user->teams()->attach($team2, ['role' => 'member']);
    }
}
