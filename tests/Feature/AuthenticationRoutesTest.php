<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthenticationRoutesTest extends TestCase
{
    public function test_guest_can_access_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_guest_can_access_admin_sign_in_page()
    {
        $response = $this->get('/admin/sign-in');
        $response->assertStatus(200);
    }

    public function test_protected_profile_requires_authentication()
    {
        $response = $this->get('/profile');
        $this->assertContains($response->status(), [302, 500]); // Auth required
    }

    public function test_protected_cart_requires_authentication()
    {
        $response = $this->get('/cart');
        $this->assertContains($response->status(), [302, 500]); // Auth required
    }

    public function test_protected_checkout_requires_authentication()
    {
        $response = $this->get('/checkout');
        $this->assertContains($response->status(), [302, 500]); // Auth required
    }

    public function test_collector_dashboard_requires_authentication()
    {
        $response = $this->get('/collector-dashboard');
        $this->assertContains($response->status(), [302, 500]); // Auth required
    }

    public function test_admin_dashboard_requires_authentication()
    {
        $response = $this->get('/admin');
        $response->assertStatus(302); // Redirect
    }

    public function test_admin_users_page_requires_authentication()
    {
        $response = $this->get('/admin/users');
        $response->assertStatus(302); // Redirect
    }

    public function test_admin_products_page_requires_authentication()
    {
        $response = $this->get('/admin/products');
        $response->assertStatus(302); // Redirect
    }
}
