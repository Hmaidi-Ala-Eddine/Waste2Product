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

    public function test_chatbot_message_requires_post_request()
    {
        $response = $this->get('/chatbot/message');
        $response->assertStatus(405); // Method not allowed
    }

    public function test_chatbot_history_endpoint_is_accessible()
    {
        $response = $this->withSession([])->get('/chatbot/history');
        $response->assertStatus(200);
    }
}
