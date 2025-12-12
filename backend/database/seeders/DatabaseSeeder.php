<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        $reader = User::factory()->create([
            'name' => 'Jane Reader',
            'email' => 'reader@blog.com',
            'password' => bcrypt('password'),
            'role' => 'reader',
        ]);

        // Get users for posts
        $admin = User::where('email', 'admin@blog.com')->first();
        $author = User::where('email', 'author@blog.com')->first();

        // Create sample posts
        $posts = [
            [
                'user_id' => $admin->id,
                'title' => 'Welcome to Our Blog Platform',
                'content' => '<p>Welcome to our amazing blog platform! This is a test post created by an admin user. Since admins can auto-approve posts, this post is immediately published.</p><p>This platform features user authentication, role-based access control, and a complete post management system. Authors can create posts that require approval, while admins can publish instantly.</p>',
                'excerpt' => 'Welcome post introducing our blog platform and its features.',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'published_at' => now(),
            ],
            [
                'user_id' => $author->id,
                'title' => 'Getting Started with Laravel and Nuxt',
                'content' => '<p>Building modern web applications requires a solid foundation. Laravel provides an excellent backend framework with robust features, while Nuxt.js offers a powerful frontend solution.</p><p>In this post, we explore how these two technologies work together to create seamless full-stack applications. The combination allows for API-driven development with server-side rendering capabilities.</p>',
                'excerpt' => 'Learn how Laravel and Nuxt work together to build modern applications.',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now()->subDay(),
                'published_at' => now()->subDay(),
            ],
            [
                'user_id' => $author->id,
                'title' => 'Understanding Docker for Development',
                'content' => '<p>Docker has revolutionized how we develop and deploy applications. By containerizing our applications, we ensure consistency across different environments.</p><p>This post covers the basics of Docker, including containers, images, and docker-compose for orchestrating multiple services. Perfect for developers looking to streamline their workflow.</p>',
                'excerpt' => 'A comprehensive guide to using Docker in development workflows.',
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(2),
                'published_at' => now()->subDays(2),
            ],
            [
                'user_id' => $author->id,
                'title' => 'Awaiting Approval: The Role of Content Moderation',
                'content' => '<p>This post demonstrates the approval workflow in action. As an author, my posts must be reviewed by an administrator before they become publicly visible.</p><p>This ensures quality control and maintains the integrity of our platform. Administrators can approve or reject posts based on content guidelines.</p>',
                'excerpt' => 'A look at how the content approval system works.',
                'status' => 'pending',
            ],
        ];

        foreach ($posts as $postData) {
            $slug = Str::slug($postData['title']);
            Post::create(array_merge($postData, ['slug' => $slug]));
        }
    }
}
