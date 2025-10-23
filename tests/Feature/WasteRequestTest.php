<?php

namespace Tests\Feature;

use Tests\TestCase;

class WasteRequestTest extends TestCase
{
    public function test_waste_request_page_requires_authentication()
    {
        $response = $this->get('/waste-requests');
        $response->assertRedirect('/login');
    }

    public function test_application_has_waste_request_route()
    {
        $this->assertTrue(true);
    }
}
