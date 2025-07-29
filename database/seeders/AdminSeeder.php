<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->insert([

            'name' => 'fatma',
            'email' => 'fatma@gmail.com',
            'password' => Hash::make('123456'), 

            'name' => 'Silva',
            'email' => 'silva@gmail.com',
            'password' => Hash::make('123456'), // bcrypt otomatis

            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
