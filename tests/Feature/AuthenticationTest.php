<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_guest_can_access_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
