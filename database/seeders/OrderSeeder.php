<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::take(5)->get();
        $users = User::take(3)->get();
        
        if ($products->isEmpty() || $users->isEmpty()) {
            echo "No products or users found. Please run ProductSeeder and UserSeeder first.\n";
            return;
        }

        $orders = [
            [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'quantity' => 1,
                'total_price' => 45.00,
                'status' => 'pending',
                'payment_method' => 'credit_card',
                'ordered_at' => now()->subDays(5),
            ],
            [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'quantity' => 2,
                'total_price' => 150.00,
                'status' => 'confirmed',
                'payment_method' => 'paypal',
                'ordered_at' => now()->subDays(10),
            ],
            [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'quantity' => 1,
                'total_price' => 25.00,
                'status' => 'delivered',
                'payment_method' => 'bank_transfer',
                'ordered_at' => now()->subDays(15),
            ],
            [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'quantity' => 3,
                'total_price' => 360.00,
                'status' => 'pending',
                'payment_method' => 'credit_card',
                'ordered_at' => now()->subDays(2),
            ],
            [
                'user_id' => $users->random()->id,
                'product_id' => $products->random()->id,
                'quantity' => 1,
                'total_price' => 15.00,
                'status' => 'delivered',
                'payment_method' => 'cash',
                'ordered_at' => now()->subDays(20),
            ],
        ];

        foreach ($orders as $orderData) {
            Order::create($orderData);
        }

        echo "Sample orders created successfully!\n";
    }
}
