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
            'name' => 'admin',
            'email' => 'alladeen.santos@MGS.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $staffUserId1 = DB::table('users')->insertGetId([
            'name' => 'Johnstaff',
            'email' => 'John.Dew@MGS.com',
            'password' => Hash::make('password'),
            'role_id' => $IstaffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $staffUserId2 = DB::table('users')->insertGetId([
            'name' => 'Gregstaff',
            'email' => 'Greg.Smith@MGS.com',
            'password' => Hash::make('password'),
            'role_id' => $PstaffRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Employees (linked to users)
        DB::table('employees')->insert([
            [

                'user_id' => $adminUserId,
                'first_name' => 'Alladeen',
                'middle_name' => null,
                'last_name' => 'Santos',
                'phone' => '09123456789',
                'email' => 'AlladeenSantis@example.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $staffUserId1,
                'first_name' => 'John',
                'middle_name' => 'M',
                'last_name' => 'Dew',
                'phone' => '09998887777',
                'email' => 'JohnDew@example.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $staffUserId2,
                'first_name' => 'Greg',
                'middle_name' => 'S',
                'last_name' => 'Smith',
                'phone' => '09998887766',
                'email' => 'GregSmith@gmail.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
