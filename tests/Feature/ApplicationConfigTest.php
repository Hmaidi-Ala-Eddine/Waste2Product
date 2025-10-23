<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationConfigTest extends TestCase
{
    public function test_app_environment_is_configured()
    {
        $this->assertNotNull(config('app.env'));
        $this->assertContains(config('app.env'), ['local', 'testing', 'production']);
    }

    public function test_app_key_is_set()
    {
        $this->assertNotEmpty(config('app.key'));
    }

    public function test_app_name_is_set()
    {
        $this->assertNotEmpty(config('app.name'));
    }

    public function test_database_connection_is_configured()
    {
        $this->assertNotNull(config('database.default'));
    }

    public function test_cache_driver_is_configured()
    {
        $this->assertNotNull(config('cache.default'));
    }

    public function test_session_driver_is_configured()
    {
        $this->assertNotNull(config('session.driver'));
    }

    public function test_groq_service_is_configured()
    {
        $this->assertNotNull(config('services.groq'));
        $this->assertIsArray(config('services.groq'));
    }
}
