<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(3)
            ->sequence(
                ['name' => 'Ali', 'user_name' => 'ali', 'email' => 'ali@gmail.com', 'role' => 'admin'],
                ['name' => 'Jacob', 'user_name' => 'jacob', 'email' => 'jacob@gmail.com'],
                ['name' => 'Lith', 'user_name' => 'lith', 'email' => 'lith@gmail.com']
            )
            ->create();
        User::factory()->count(17)->create();
    }
}
