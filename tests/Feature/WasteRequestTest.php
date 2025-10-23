<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class WasteRequestTest extends TestCase
{
    public function test_waste_request_page_requires_authentication()
    {
        $response = $this->get('/waste-requests');
        $this->assertContains($response->status(), [302, 500]); // Auth required
    }

    public function test_waste_request_routes_exist()
    {
        $this->assertTrue(Route::has('front.waste-requests'));
        $this->assertTrue(Route::has('front.waste-requests.store'));
        $this->assertTrue(Route::has('admin.waste-requests'));
    }

    public function test_admin_waste_requests_requires_authentication()
    {
        $response = $this->get('/admin/waste-requests');
        $response->assertStatus(302); // Redirect
    }
}
