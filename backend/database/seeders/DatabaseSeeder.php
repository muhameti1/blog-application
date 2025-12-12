<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@blog.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create author user
        User::factory()->create([
            'name' => 'John Author',
            'email' => 'author@blog.com',
            'password' => bcrypt('password'),
            'role' => 'author',
        ]);

        // Create reader user
        User::factory()->create([
            'name' => 'Jane Reader',
            'email' => 'reader@blog.com',
            'password' => bcrypt('password'),
            'role' => 'reader',
        ]);
    }
}
