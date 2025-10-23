<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Collector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_apply_as_collector()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/collector/apply', [
            'company_name' => 'EcoCollect Tunisia',
            'vehicle_type' => 'truck',
            'service_areas' => ['Tunis', 'Ariana', 'Ben Arous'],
            'capacity_kg' => 500,
            'bio' => 'Professional waste collection service',
        ]);

        $this->assertDatabaseHas('collectors', [
            'user_id' => $user->id,
            'company_name' => 'EcoCollect Tunisia',
            'vehicle_type' => 'truck',
            'verification_status' => 'pending',
        ]);
    }

    /** @test */
    public function collector_application_requires_valid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/collector/apply', [
            'company_name' => '',
            'vehicle_type' => 'invalid_type',
            'service_areas' => [],
            'capacity_kg' => -100,
        ]);

        $response->assertSessionHasErrors(['company_name', 'vehicle_type', 'service_areas', 'capacity_kg']);
    }

    /** @test */
    public function verified_collector_can_access_dashboard()
    {
        $user = User::factory()->create();
        $collector = Collector::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($user)->get('/collector-dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function unverified_collector_cannot_access_dashboard()
    {
        $user = User::factory()->create();
        $collector = Collector::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/collector-dashboard');

        $response->assertRedirect();
    }
}
