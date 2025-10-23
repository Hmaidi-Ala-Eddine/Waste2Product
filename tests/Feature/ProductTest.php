<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_shop_page()
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'available']);

        $response = $this->actingAs($user)->post("/cart/add/{$product->id}");
        
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_guest_cannot_add_product_to_cart()
    {
        $product = Product::factory()->create();
        $response = $this->post("/cart/add/{$product->id}");
        $response->assertRedirect('/login');
    }

    public function test_user_can_update_cart_quantity()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $this->actingAs($user)->post("/cart/add/{$product->id}");
        
        $cartItem = $user->cartItems()->first();
        $response = $this->actingAs($user)->put("/cart/update/{$cartItem->id}", [
            'quantity' => 3
        ]);

        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->id,
            'quantity' => 3,
        ]);
    }

    public function test_user_can_remove_item_from_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        
        $this->actingAs($user)->post("/cart/add/{$product->id}");
        $cartItem = $user->cartItems()->first();
        
        $response = $this->actingAs($user)->delete("/cart/remove/{$cartItem->id}");
        
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_checkout_creates_orders()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'available', 'price' => 100]);
        
        $this->actingAs($user)->post("/cart/add/{$product->id}");
        
        $response = $this->actingAs($user)->post('/checkout', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '123456789',
            'address' => 'Test Address',
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }
}
