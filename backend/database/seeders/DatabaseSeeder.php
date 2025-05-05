<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = User::factory(10)->create();
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $createUserPermission = Permission::firstOrCreate(['name' => 'create user']);
        
        // Give permission to role
        $adminRole->givePermissionTo($createUserPermission);

        // Make sure the user exists before assigning a role
        $user = User::find(2);
        $user->assignRole('admin');
    }
}
