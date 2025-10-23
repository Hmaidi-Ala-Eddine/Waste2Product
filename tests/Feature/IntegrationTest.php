<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteRequest;
use App\Models\Collector;
use App\Models\CollectorRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_waste_collection_flow()
    {
        // 1. User registers
        $user = User::factory()->create();
        
        // 2. User creates waste request
        $wasteRequest = WasteRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
        
        $this->assertDatabaseHas('waste_requests', [
            'id' => $wasteRequest->id,
            'status' => 'pending',
        ]);

        // 3. Collector applies
        $collectorUser = User::factory()->create();
        $collector = Collector::factory()->create([
            'user_id' => $collectorUser->id,
            'verification_status' => 'verified',
        ]);

        // 4. Collector accepts request
        $wasteRequest->update([
            'collector_id' => $collector->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('waste_requests', [
            'id' => $wasteRequest->id,
            'status' => 'accepted',
            'collector_id' => $collector->id,
        ]);

        // 5. Collector completes collection
        $wasteRequest->update([
            'status' => 'collected',
            'collected_at' => now(),
        ]);

        $this->assertDatabaseHas('waste_requests', [
            'id' => $wasteRequest->id,
            'status' => 'collected',
        ]);

        // 6. User rates collector
        $rating = CollectorRating::create([
            'waste_request_id' => $wasteRequest->id,
            'collector_id' => $collector->id,
            'customer_id' => $user->id,
            'rating' => 5,
            'review' => 'Excellent service!',
        ]);

        $this->assertDatabaseHas('collector_ratings', [
            'waste_request_id' => $wasteRequest->id,
            'rating' => 5,
        ]);

        // 7. Verify collector stats updated
        $collector->refresh();
        $this->assertGreaterThan(0, $collector->rating);
    }

    public function test_complete_shopping_flow()
    {
        // 1. User browses shop
        $response = $this->get('/shop');
        $response->assertStatus(200);

        // 2. User registers
        $user = User::factory()->create();

        // 3. User adds product to cart
        $product = \App\Models\Product::factory()->create([
            'status' => 'available',
            'price' => 150.00,
        ]);

        $this->actingAs($user)->post("/cart/add/{$product->id}");

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // 4. User proceeds to checkout
        $response = $this->actingAs($user)->post('/checkout', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => '123456789',
            'address' => 'Test Address',
            'payment_method' => 'card',
        ]);

        // 5. Verify order created
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);

        // 6. Verify cart cleared
        $this->assertDatabaseMissing('cart_items', [
            'user_id' => $user->id,
        ]);

        // 7. Verify product status updated
        $product->refresh();
        $this->assertEquals('reserved', $product->status);
    }

    public function test_collector_application_workflow()
    {
        // 1. User registers
        $user = User::factory()->create(['role' => 'user']);

        // 2. User applies as collector
        $collector = Collector::factory()->create([
            'user_id' => $user->id,
            'verification_status' => 'pending',
        ]);

        $this->assertDatabaseHas('collectors', [
            'user_id' => $user->id,
            'verification_status' => 'pending',
        ]);

        // 3. Admin verifies collector
        $admin = User::factory()->create(['role' => 'admin']);
        
        $collector->update(['verification_status' => 'verified']);

        $this->assertDatabaseHas('collectors', [
            'id' => $collector->id,
            'verification_status' => 'verified',
        ]);

        // 4. Verified collector can access dashboard
        $response = $this->actingAs($user)->get('/collector-dashboard');
        $response->assertStatus(200);

        // 5. Collector can see available requests
        $availableRequest = WasteRequest::factory()->create([
            'status' => 'pending',
            'collector_id' => null,
        ]);

        $response = $this->actingAs($user)->get('/collector-dashboard');
        $response->assertStatus(200);
    }
}
