<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoutesExistTest extends TestCase
{
    public function test_frontend_routes_are_registered()
    {
        $this->assertTrue(Route::has('front.home'));
        $this->assertTrue(Route::has('front.about'));
        $this->assertTrue(Route::has('front.contact'));
        $this->assertTrue(Route::has('front.services'));
        $this->assertTrue(Route::has('front.shop'));
        $this->assertTrue(Route::has('front.login'));
    }

    public function test_admin_routes_are_registered()
    {
        $this->assertTrue(Route::has('admin.dashboard'));
        $this->assertTrue(Route::has('admin.users'));
        $this->assertTrue(Route::has('admin.products.index'));
        $this->assertTrue(Route::has('admin.posts'));
        $this->assertTrue(Route::has('admin.waste-requests'));
        $this->assertTrue(Route::has('admin.collectors'));
    }

    public function test_chatbot_routes_are_registered()
    {
        $this->assertTrue(Route::has('chatbot.message'));
        $this->assertTrue(Route::has('chatbot.health'));
        $this->assertTrue(Route::has('chatbot.quick-replies'));
        $this->assertTrue(Route::has('chatbot.history'));
    }

    public function test_eco_ideas_routes_are_registered()
    {
        $this->assertTrue(Route::has('front.eco-ideas'));
        $this->assertTrue(Route::has('admin.eco-ideas'));
    }

    public function test_events_routes_are_registered()
    {
        $this->assertTrue(Route::has('front.events'));
        $this->assertTrue(Route::has('admin.events'));
    }

    public function test_collector_routes_are_registered()
    {
        $this->assertTrue(Route::has('front.collector-application'));
        $this->assertTrue(Route::has('front.collector-dashboard'));
        $this->assertTrue(Route::has('admin.collectors'));
    }

    public function test_cart_routes_are_registered()
    {
        $this->assertTrue(Route::has('front.cart'));
        $this->assertTrue(Route::has('front.checkout'));
    }

    public function test_waste_request_routes_are_registered()
    {
        $this->assertTrue(Route::has('front.waste-requests'));
        $this->assertTrue(Route::has('admin.waste-requests'));
    }
}
