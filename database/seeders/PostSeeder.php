<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (admin)
        $user = User::first();
        
        if (!$user) {
            echo "No users found. Please create users first.\n";
            return;
        }

        // Create sample posts
        $posts = [
            [
                'title' => 'Welcome to Waste2Product Platform',
                'description' => 'This is our new community platform where users can share waste reduction tips, success stories, and connect with local waste management services. Join us in making our planet greener!',
                'likes' => 25
            ],
            [
                'title' => 'Top 5 Recycling Tips for Beginners',
                'description' => 'Starting your recycling journey? Here are the essential tips every beginner should know: 1. Sort properly 2. Clean containers 3. Check local guidelines 4. Reduce first 5. Reuse when possible.',
                'likes' => 18
            ],
            [
                'title' => 'Community Cleanup Event Success!',
                'description' => 'Last weekend community cleanup was a huge success! We collected over 500kg of waste from the local park. Thanks to all volunteers who participated. Next event is scheduled for next month.',
                'likes' => 42
            ],
            [
                'title' => 'How to Start Composting at Home',
                'description' => 'Composting is one of the easiest ways to reduce household waste. Learn how to start your own compost bin and turn kitchen scraps into nutrient-rich soil for your garden.',
                'likes' => 33
            ],
            [
                'title' => 'Plastic-Free Living: Week 1 Challenge',
                'description' => 'Join our plastic-free living challenge! This week we are focusing on eliminating single-use plastics from our daily routine. Share your progress and tips with the community.',
                'likes' => 15
            ]
        ];

        foreach ($posts as $index => $postData) {
            // Add sample images to posts
            $images = [
                'posts/default-post.png',
                'posts/default-post.png',
                'posts/default-post.png',
                'posts/default-post.png',
                'posts/default-post.png'
            ];
            
            $post = Post::create([
                'user_id' => $user->id,
                'title' => $postData['title'],
                'description' => $postData['description'],
                'image' => $images[$index] ?? 'posts/default-post.png',
                'likes' => $postData['likes'],
            ]);

            // Add some sample comments
            $comments = [
                'Great post! Very informative.',
                'Thanks for sharing this.',
                'Looking forward to more content like this!',
                'This is exactly what I needed to know.',
                'Excellent tips, will definitely try these.'
            ];

            // Add 2-3 random comments per post
            $commentCount = rand(2, 3);
            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'comment' => $comments[array_rand($comments)],
                ]);
            }
        }

        echo "Sample posts and comments created successfully!\n";
    }
}