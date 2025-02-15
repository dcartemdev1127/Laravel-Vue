<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'admin@findasupertutor.com',
            'password' => Hash::make('123123'),
            'role' => 'admin',
            'country' => 'GB',
            'phoneNumber' => '+442071234567',
            'city' => 'London'
        ]);
    }
}
