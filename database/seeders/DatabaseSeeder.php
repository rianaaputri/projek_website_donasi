<?php

namespace Database\Seeders;


use App\Models\User;

use App\Models\Admins;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      DB::table('users')->insert([

            'name' => 'fatma',
            'email' => 'fatma@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('123456'), 

            'name' => 'Silva',
            'email' => 'silva@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('123456'), // bcrypt otomatis

            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Panggil AdminSeeder
        $this->call(AdminSeeder::class);
    }
}
