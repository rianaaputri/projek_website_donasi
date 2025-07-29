<?php

namespace Database\Seeders;


use App\Models\User;

use App\Models\Admins;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder bawaan User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        // Panggil AdminSeeder
        $this->call(AdminSeeder::class);
    }
}
