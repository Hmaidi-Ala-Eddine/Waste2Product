<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_home_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertViewIs('front.home');
    }

    public function test_about_page_is_accessible()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    public function test_contact_page_is_accessible()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    public function test_services_page_is_accessible()
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
    }

    public function test_projects_page_is_accessible()
    {
        $response = $this->get('/projects');
        $response->assertStatus(200);
    }

    public function test_blog_page_is_accessible()
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
    }

    public function test_login_page_is_accessible()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_shop_page_is_accessible()
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
    }

    public function test_events_page_is_accessible()
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_eco_ideas_page_is_accessible()
    {
        $response = $this->get('/eco-ideas');
        $response->assertStatus(200);
    }

    public function test_posts_page_is_accessible()
    {
        $response = $this->get('/posts');
        $response->assertStatus(200);
    }

    public function test_404_page_is_accessible()
    {
        $response = $this->get('/404');
        $response->assertStatus(200);
    }

    public function test_invalid_route_returns_404()
    {
        $response = $this->get('/this-route-does-not-exist');
        $response->assertStatus(404);
    }
}
