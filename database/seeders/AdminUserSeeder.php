<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert admin user
        $userId = DB::table('users')->insertGetId([
            'username' => 'admin',
            'password' => Hash::make('1234567890'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert corresponding customer record
        DB::table('customers')->insert([
            'user_id' => $userId,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'image' => 'user_photo.png',
            'phone' => '123-456-7890',
            'status' => 'Actived',
            'address' => 'Confidential Detail',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
