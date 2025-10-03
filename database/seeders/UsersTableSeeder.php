<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Roles
        $adminRoleId = DB::table('roles')->insertGetId([
            'role_name' => 'Admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $IstaffRoleId = DB::table('roles')->insertGetId([
            'role_name' => 'Inventory',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $PstaffRoleId = DB::table('roles')->insertGetId([
            'role_name' => 'Production',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Insert Users
        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $staffUserId = DB::table('users')->insertGetId([
            'name' => 'John Staff',
            'email' => 'John',
            'password' => Hash::make('password'),
            'role_id' => $IstaffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $staffUserId = DB::table('users')->insertGetId([
            'name' => 'Fred Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role_id' => $IstaffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Employees (linked to users)
        DB::table('employees')->insert([
            [
                'user_id' => $adminUserId,
                'first_name' => 'System',
                'middle_name' => null,
                'last_name' => 'Admin',
                'phone' => '09123456789',
                'email' => 'admin@example.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $staffUserId,
                'first_name' => 'John',
                'middle_name' => 'M',
                'last_name' => 'Staff',
                'phone' => '09998887777',
                'email' => 'staff@example.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
