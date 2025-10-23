<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteRequest;
use App\Models\Collector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertRedirect();
    }

    public function test_admin_can_manage_users()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    public function test_admin_can_verify_collector()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $collector = Collector::factory()->create(['verification_status' => 'pending']);

        $response = $this->actingAs($admin)->post("/admin/collectors/{$collector->id}/update-status", [
            'verification_status' => 'verified',
        ]);

        $this->assertDatabaseHas('collectors', [
            'id' => $collector->id,
            'verification_status' => 'verified',
        ]);
    }

    public function test_admin_can_view_all_waste_requests()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        WasteRequest::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get('/admin/waste-requests');
        $response->assertStatus(200);
    }

    public function test_admin_can_assign_collector_to_request()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $request = WasteRequest::factory()->create(['status' => 'pending']);
        $collector = Collector::factory()->create(['verification_status' => 'verified']);

        $response = $this->actingAs($admin)->post("/admin/waste-requests/{$request->id}/assign", [
            'collector_id' => $collector->id,
        ]);

        $this->assertDatabaseHas('waste_requests', [
            'id' => $request->id,
            'collector_id' => $collector->id,
        ]);
    }
}
