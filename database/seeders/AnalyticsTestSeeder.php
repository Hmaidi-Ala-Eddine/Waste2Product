<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WasteRequest;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class AnalyticsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Ensure we have some users
        $users = User::all();
        if ($users->count() < 2) {
            // Create test users if they don't exist
            $user1 = User::firstOrCreate(
                ['email' => 'customer@example.com'],
                [
                    'name' => 'Test Customer',
                    'password' => bcrypt('password'),
                    'role' => 'customer',
                    'is_active' => true
                ]
            );
            
            $user2 = User::firstOrCreate(
                ['email' => 'collector@example.com'],
                [
                    'name' => 'Test Collector',
                    'password' => bcrypt('password'),
                    'role' => 'collector',
                    'is_active' => true
                ]
            );
            
            $users = collect([$user1, $user2]);
        }
        
        $customer = $users->where('role', '!=', 'admin')->first();
        $collector = $users->where('role', 'collector')->first() ?? $users->first();
        
        // Create waste requests for today
        $wasteRequest1 = new WasteRequest([
            'user_id' => $customer->id,
            'collector_id' => $collector->id,
            'waste_type' => 'plastic',
            'quantity' => 15.5,
            'address' => '123 Test Street',
            'description' => 'Plastic bottles and containers',
            'status' => 'collected',
        ]);
        $wasteRequest1->created_at = $today;
        $wasteRequest1->updated_at = $today;
        $wasteRequest1->save();
        
        $wasteRequest2 = new WasteRequest([
            'user_id' => $customer->id,
            'collector_id' => $collector->id,
            'waste_type' => 'metal',
            'quantity' => 8.2,
            'address' => '456 Sample Avenue',
            'description' => 'Aluminum cans',
            'status' => 'collected',
        ]);
        $wasteRequest2->created_at = $today;
        $wasteRequest2->updated_at = $today;
        $wasteRequest2->save();
        
        // Create products for today
        $product1 = new Product([
            'user_id' => $customer->id,
            'name' => 'Recycled Plastic Chair',
            'description' => 'Chair made from recycled plastic',
            'category' => 'furniture',
            'condition' => 'excellent',
            'price' => 45.99,
            'status' => 'available',
        ]);
        $product1->created_at = $today;
        $product1->updated_at = $today;
        $product1->save();
        
        $product2 = new Product([
            'user_id' => $customer->id,
            'name' => 'Metal Art Sculpture',
            'description' => 'Art piece from recycled metal',
            'category' => 'metal',
            'condition' => 'good',
            'price' => null, // Free product
            'status' => 'donated',
        ]);
        $product2->created_at = $today;
        $product2->updated_at = $today;
        $product2->save();
        
        // Get products for orders
        $products = Product::all();
        if ($products->count() > 0) {
            // Create orders for today
            $order1 = new Order([
                'user_id' => $customer->id,
                'product_id' => $products->first()->id,
                'quantity' => 1,
                'total_price' => 45.99,
                'status' => 'confirmed',
                'payment_method' => 'credit_card',
                'ordered_at' => $today,
            ]);
            $order1->created_at = $today;
            $order1->updated_at = $today;
            $order1->save();
        }
        
        // Create third waste request for TODAY as well
        $wasteRequest3 = new WasteRequest([
            'user_id' => $customer->id,
            'collector_id' => $collector->id,
            'waste_type' => 'organic',
            'quantity' => 22.3,
            'address' => '789 Test Road',
            'description' => 'Organic waste materials',
            'status' => 'collected',
        ]);
        $wasteRequest3->created_at = $today;
        $wasteRequest3->updated_at = $today;
        $wasteRequest3->save();
        
        // Create data for yesterday (for monthly charts)
        $wasteRequestYesterday = new WasteRequest([
            'user_id' => $customer->id,
            'collector_id' => $collector->id,
            'waste_type' => 'paper',
            'quantity' => 12.5,
            'address' => '789 Yesterday Road',
            'description' => 'Paper waste materials',
            'status' => 'collected',
        ]);
        $wasteRequestYesterday->created_at = $yesterday;
        $wasteRequestYesterday->updated_at = $yesterday;
        $wasteRequestYesterday->save();
        
        $productYesterday = new Product([
            'user_id' => $customer->id,
            'name' => 'Compost Bin',
            'description' => 'Bin made from organic waste',
            'category' => 'plastic',
            'condition' => 'fair',
            'price' => 25.50,
            'status' => 'sold',
        ]);
        $productYesterday->created_at = $yesterday;
        $productYesterday->updated_at = $yesterday;
        $productYesterday->save();
        
        if ($products->count() > 1) {
            $orderYesterday = new Order([
                'user_id' => $customer->id,
                'product_id' => $products->skip(1)->first()->id,
                'quantity' => 2,
                'total_price' => 51.00,
                'status' => 'pending',
                'payment_method' => 'paypal',
                'ordered_at' => $yesterday,
            ]);
            $orderYesterday->created_at = $yesterday;
            $orderYesterday->updated_at = $yesterday;
            $orderYesterday->save();
        }
        
        // Create data for 3 days ago (for more monthly chart data)
        $threeDaysAgo = Carbon::today()->subDays(3);
        
        $wasteRequest3Days = new WasteRequest([
            'user_id' => $customer->id,
            'collector_id' => $collector->id,
            'waste_type' => 'e-waste',
            'quantity' => 5.8,
            'address' => '321 Three Days Street',
            'description' => 'Electronic waste',
            'status' => 'collected',
        ]);
        $wasteRequest3Days->created_at = $threeDaysAgo;
        $wasteRequest3Days->updated_at = $threeDaysAgo;
        $wasteRequest3Days->save();
        
        $product3Days = new Product([
            'user_id' => $customer->id,
            'name' => 'Electronic Art',
            'description' => 'Art from old electronics',
            'category' => 'electronics',
            'condition' => 'excellent',
            'price' => 75.00,
            'status' => 'available',
        ]);
        $product3Days->created_at = $threeDaysAgo;
        $product3Days->updated_at = $threeDaysAgo;
        $product3Days->save();
    }
}