<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Services\GroqService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class CommentModerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Mock the GroqService to avoid real API calls in tests
     */
    protected function mockGroqService()
    {
        $mock = Mockery::mock(GroqService::class);
        
        // Mock for clean comments - return the original text unchanged
        $mock->shouldReceive('moderateComment')
            ->with(Mockery::on(function ($comment) {
                // Match clean comments (no bad words)
                return !preg_match('/\b(stupid|idiot|dumb|damn|shit|fuck|bitch)\b/i', $comment);
            }))
            ->andReturnUsing(function ($comment) {
                return [
                    'success' => true,
                    'is_appropriate' => true,
                    'original_text' => $comment,
                    'censored_text' => $comment,
                    'violations' => []
                ];
            });
        
        // Mock for inappropriate comments - return censored version
        $mock->shouldReceive('moderateComment')
            ->with(Mockery::on(function ($comment) {
                // Match inappropriate comments (contains bad words)
                return preg_match('/\b(stupid|idiot|dumb|damn|shit|fuck|bitch)\b/i', $comment);
            }))
            ->andReturnUsing(function ($comment) {
                $censored = preg_replace('/\b(stupid|idiot|dumb|damn|shit|fuck|bitch)\b/i', '***', $comment);
                return [
                    'success' => true,
                    'is_appropriate' => false,
                    'original_text' => $comment,
                    'censored_text' => $censored,
                    'violations' => ['profanity']
                ];
            });
        
        $this->app->instance(GroqService::class, $mock);
        
        return $mock;
    }

    /**
     * Test clean comment passes through without modification
     */
    public function test_clean_comment_is_not_modified()
    {
        $this->mockGroqService();
        
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
        $this->mockGroqService();
        
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
        $this->mockGroqService();
        
        $groqService = app(GroqService::class);

        // Test clean comment
        $cleanResult = $groqService->moderateComment('This is a nice comment');
        $this->assertTrue($cleanResult['is_appropriate']);
        $this->assertArrayHasKey('censored_text', $cleanResult);

        // Test inappropriate comment
        $badResult = $groqService->moderateComment('You are so stupid and dumb');
        $this->assertFalse($badResult['is_appropriate']);
        $this->assertArrayHasKey('censored_text', $badResult);
        $this->assertArrayHasKey('violations', $badResult);
    }

    /**
     * Test comment API returns moderated data
     */
    public function test_comment_api_returns_moderated_data()
    {
        $this->mockGroqService();
        
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
