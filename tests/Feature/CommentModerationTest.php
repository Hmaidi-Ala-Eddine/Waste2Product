<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentModerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test clean comment passes through without modification
     */
    public function test_clean_comment_is_not_modified()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'comment' => 'This is a great post! Very informative.'
        ]);

        // The moderated version should be the same as original
        $this->assertEquals($comment->comment, $comment->moderated_comment);
        $this->assertFalse($comment->hasInappropriateContent());
    }

    /**
     * Test inappropriate comment is censored
     */
    public function test_inappropriate_comment_is_censored()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'comment' => 'This is damn stupid you idiot!'
        ]);

        // The moderated version should be different from original
        $this->assertNotEquals($comment->comment, $comment->moderated_comment);
        
        // Should detect as inappropriate
        $this->assertTrue($comment->hasInappropriateContent());
        
        // Moderated text should contain censoring
        $this->assertStringContainsStringIgnoringCase('***', $comment->moderated_comment);
    }

    /**
     * Test moderation service directly
     */
    public function test_groq_moderation_service()
    {
        $groqService = app(GroqService::class);

        // Test clean comment
        $cleanResult = $groqService->moderateComment('This is a nice comment');
        $this->assertTrue($cleanResult['is_appropriate']);
        $this->assertEquals('This is a nice comment', $cleanResult['censored_text']);

        // Test inappropriate comment
        $badResult = $groqService->moderateComment('You are so stupid and dumb');
        $this->assertFalse($badResult['is_appropriate']);
        $this->assertNotEquals('You are so stupid and dumb', $badResult['censored_text']);
    }

    /**
     * Test comment API returns moderated data
     */
    public function test_comment_api_returns_moderated_data()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        
        $this->actingAs($user);
        
        $response = $this->postJson("/posts/{$post->id}/comments", [
            'comment' => 'This is a test comment'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'comment' => [
                    'id',
                    'user_name',
                    'comment',
                    'moderated_comment',
                    'is_moderated'
                ]
            ]);
    }
}
