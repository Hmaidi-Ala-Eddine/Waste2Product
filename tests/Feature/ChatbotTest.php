<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatbotTest extends TestCase
{
    public function test_chatbot_health_endpoint_is_accessible()
    {
        $response = $this->get('/chatbot/health');
        $response->assertStatus(200);
    }

    public function test_chatbot_quick_replies_endpoint_is_accessible()
    {
        $response = $this->get('/chatbot/quick-replies');
        $response->assertStatus(200);
    }

    public function test_chatbot_test_endpoint_is_accessible()
    {
        $response = $this->get('/chatbot/test');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_chatbot_message_endpoint_exists()
    {
        // POST route exists but GET should fail
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('chatbot.message'));
    }

    public function test_chatbot_history_endpoint_is_accessible()
    {
        $response = $this->withSession([])->get('/chatbot/history');
        $response->assertStatus(200);
    }
}
