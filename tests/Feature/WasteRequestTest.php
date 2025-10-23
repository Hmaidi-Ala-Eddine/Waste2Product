<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WasteRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_waste_request()
    {
        if (!class_exists('Database\Factories\UserFactory')) {
            $this->markTestSkipped('UserFactory not available');
        }

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/waste-requests/store', [
            'waste_type' => 'plastic',
            'quantity' => 10.5,
            'state' => 'Tunis',
            'address' => '123 Test Street, Tunis',
            'description' => 'Test waste request description',
        ]);

        $this->assertDatabaseHas('waste_requests', [
            'user_id' => $user->id,
            'waste_type' => 'plastic',
            'quantity' => 10.5,
            'state' => 'Tunis',
        ]);
    }

    /** @test */
    public function guest_cannot_create_waste_request()
    {
        $response = $this->post('/waste-requests/store', [
            'waste_type' => 'plastic',
            'quantity' => 10.5,
            'state' => 'Tunis',
            'address' => '123 Test Street',
            'description' => 'Test description',
        ]);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_can_view_their_own_waste_requests()
    {
        $user = User::factory()->create();
        $wasteRequest = WasteRequest::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/waste-requests');

        $response->assertStatus(200);
        $response->assertSee($wasteRequest->waste_type);
    }

    /** @test */
    public function waste_request_requires_valid_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/waste-requests/store', [
            'waste_type' => '',
            'quantity' => -5,
            'state' => '',
            'address' => '',
        ]);

        $response->assertSessionHasErrors(['waste_type', 'quantity', 'state', 'address']);
    }
}
