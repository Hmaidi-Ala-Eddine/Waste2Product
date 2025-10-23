<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteRequest;
use App\Models\Collector;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_migrations_run_successfully()
    {
        $this->assertTrue(true);
    }

    public function test_user_factory_creates_valid_user()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    public function test_waste_request_factory_creates_valid_request()
    {
        $request = WasteRequest::factory()->create();

        $this->assertDatabaseHas('waste_requests', [
            'id' => $request->id,
            'waste_type' => $request->waste_type,
        ]);
    }

    public function test_collector_factory_creates_valid_collector()
    {
        $collector = Collector::factory()->create();

        $this->assertDatabaseHas('collectors', [
            'id' => $collector->id,
            'vehicle_type' => $collector->vehicle_type,
        ]);
    }

    public function test_product_factory_creates_valid_product()
    {
        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
        ]);
    }

    public function test_user_has_waste_requests_relationship()
    {
        $user = User::factory()->create();
        $request = WasteRequest::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->wasteRequests->contains($request));
    }

    public function test_user_has_collector_relationship()
    {
        $user = User::factory()->create();
        $collector = Collector::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Collector::class, $user->collector);
        $this->assertEquals($collector->id, $user->collector->id);
    }

    public function test_waste_request_belongs_to_user()
    {
        $user = User::factory()->create();
        $request = WasteRequest::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $request->user);
        $this->assertEquals($user->id, $request->user->id);
    }

    public function test_collector_belongs_to_user()
    {
        $user = User::factory()->create();
        $collector = Collector::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $collector->user);
        $this->assertEquals($user->id, $collector->user->id);
    }
}
