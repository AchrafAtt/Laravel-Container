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
        User::factory(10)->create();

        // add user with email specific
        User::factory()->create([
            'first_name' => 'achraf',
            'last_name' => 'fcb',
            'email_pro' => 'attouaki.officiel@gmail.com',
            'password' => bcrypt('password'), // password
            'is_active' => true,
            'email_verified_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
            'profile_picture' => 'https://example.com/profile.jpg',
            'family_situation' => 'single',
            'position' => 'Software Engineer',
            'address' => '123 Main St',
            'country' => 'USA',
            'id_number' => '123456789',
            'ssn' => '987-65-4320',
            'bank_account' => '1234567890',
            'birth_place' => 'New York',
            'children_count' => 0,
            'contract_type' => 'FullTime',
            'leave_balance' => 20.00,
            'hire_date' => now(),
            'personal_email' => 'test@gmail.com'
        ]);

        // $users = User::factory(10)->create();
        // // Clear cache
        // app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // $adminRole = Role::firstOrCreate(['name' => 'admin']);
        // $createUserPermission = Permission::firstOrCreate(['name' => 'create user']);
        
        // // Give permission to role
        // $adminRole->givePermissionTo($createUserPermission);

        // // Make sure the user exists before assigning a role
        // $user = User::first();
        
        // $user->assignRole('admin');
    }
}
