<?php

namespace Tests\Feature;

use Tests\TestCase;

class BasicTest extends TestCase
{
    public function test_application_returns_successful_response()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_shop_page_is_accessible()
    {
        $response = $this->get('/shop');
        $response->assertSuccessful();
    }

    public function test_about_page_is_accessible()
    {
        $response = $this->get('/about');
        $response->assertSuccessful();
    }

    public function test_services_page_is_accessible()
    {
        $response = $this->get('/services');
        $response->assertSuccessful();
    }

    public function test_contact_page_is_accessible()
    {
        $response = $this->get('/contact');
        $response->assertSuccessful();
    }
}
