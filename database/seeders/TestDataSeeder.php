<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Post;
use App\Models\Event;
use App\Models\EcoIdea;
use App\Models\Collector;
use App\Models\WasteRequest;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $user1 = User::firstOrCreate(
            ['email' => 'john.test@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'jane.test@example.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin.test@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create collector profile
        Collector::create([
            'user_id' => $user1->id,
            'company_name' => 'Green Collection Co.',
            'vehicle_type' => 'truck',
            'service_areas' => json_encode(['Tunis', 'Ariana', 'Ben Arous']),
            'capacity_kg' => 1000,
            'verification_status' => 'verified',
            'rating' => 4.5,
            'bio' => 'Professional waste collector with 5 years experience.',
        ]);

        // Create products
        Product::create([
            'user_id' => $user1->id,
            'name' => 'Vintage Wooden Chair',
            'description' => 'Beautiful vintage wooden chair in excellent condition.',
            'category' => 'Furniture',
            'condition' => 'excellent',
            'price' => 50.00,
            'stock' => 1,
            'status' => 'available',
            'image_path' => null,
        ]);

        Product::create([
            'user_id' => $user2->id,
            'name' => 'Old Books Collection',
            'description' => 'Collection of classic literature books.',
            'category' => 'Books',
            'condition' => 'good',
            'price' => 25.00,
            'stock' => 5,
            'status' => 'available',
            'image_path' => null,
        ]);

        // Create posts
        Post::create([
            'user_id' => $user1->id,
            'title' => 'Tips for Sustainable Living',
            'description' => 'Here are some great tips for living more sustainably...',
            'image' => null,
            'likes' => 15,
        ]);

        Post::create([
            'user_id' => $user2->id,
            'title' => 'Upcycling Ideas',
            'description' => 'Creative ways to upcycle old items...',
            'image' => null,
            'likes' => 8,
        ]);

        // Create events
        Event::create([
            'subject' => 'Community Cleanup Day',
            'date_time' => now()->addDays(7),
            'description' => 'Join us for a community cleanup event in downtown.',
            'author_id' => $admin->id,
            'engagement' => 25,
        ]);

        Event::create([
            'subject' => 'Eco Workshop',
            'date_time' => now()->addDays(14),
            'description' => 'Learn about sustainable practices in our eco workshop.',
            'author_id' => $user1->id,
            'engagement' => 12,
        ]);

        // Create eco ideas
        EcoIdea::create([
            'creator_id' => $user1->id,
            'title' => 'Plastic Bottle Garden',
            'waste_type' => 'plastic',
            'difficulty' => 'easy',
            'description' => 'Create a vertical garden using recycled plastic bottles.',
            'ai_suggestion' => 'This is a great beginner project that helps reduce plastic waste.',
            'team_size_needed' => 2,
            'status' => 'approved',
            'upvotes' => 20,
            'project_status' => 'recruiting',
        ]);

        EcoIdea::create([
            'creator_id' => $user2->id,
            'title' => 'Electronic Waste Art',
            'waste_type' => 'e-waste',
            'difficulty' => 'hard',
            'description' => 'Transform electronic waste into artistic installations.',
            'ai_suggestion' => 'This project requires technical skills and artistic vision.',
            'team_size_needed' => 4,
            'status' => 'pending',
            'upvotes' => 5,
            'project_status' => 'idea',
        ]);

        // Create waste requests
        WasteRequest::create([
            'user_id' => $user2->id,
            'collector_id' => $user1->id,
            'waste_type' => 'organic',
            'quantity' => 50.00,
            'address' => '123 Main Street, Tunis',
            'description' => 'Kitchen waste from restaurant',
            'status' => 'accepted',
        ]);

        WasteRequest::create([
            'user_id' => $user1->id,
            'waste_type' => 'plastic',
            'quantity' => 25.00,
            'address' => '456 Oak Avenue, Ariana',
            'description' => 'Plastic bottles and containers',
            'status' => 'pending',
        ]);

        $this->command->info('Test data created successfully!');
        $this->command->info('Users created: john.test@example.com, jane.test@example.com, admin.test@example.com');
        $this->command->info('Password for all users: password');
    }
}