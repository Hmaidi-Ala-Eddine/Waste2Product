<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user or create one
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
            ]);
        }

        $products = [
            [
                'name' => 'Chaise en plastique recyclé',
                'description' => 'Belle chaise fabriquée à partir de bouteilles en plastique recyclées. Design moderne et écologique.',
                'category' => 'furniture',
                'condition' => 'new',
                'price' => 45.00,
                'status' => 'available',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Lampadaire en métal récupéré',
                'description' => 'Lampadaire unique créé à partir de métal récupéré. Style industriel et fonctionnel.',
                'category' => 'furniture',
                'condition' => 'refurbished',
                'price' => 75.00,
                'status' => 'available',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Sac à main en textile recyclé',
                'description' => 'Sac à main élégant fabriqué à partir de textiles recyclés. Parfait pour un usage quotidien.',
                'category' => 'textile',
                'condition' => 'new',
                'price' => 25.00,
                'status' => 'sold',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Table basse en bois récupéré',
                'description' => 'Table basse rustique fabriquée à partir de bois de récupération. Chaque pièce est unique.',
                'category' => 'furniture',
                'condition' => 'used',
                'price' => null, // Gratuit
                'status' => 'donated',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Smartphone reconditionné',
                'description' => 'Smartphone reconditionné en excellent état. Toutes les fonctionnalités opérationnelles.',
                'category' => 'electronics',
                'condition' => 'refurbished',
                'price' => 120.00,
                'status' => 'reserved',
                'user_id' => $user->id,
            ],
            [
                'name' => 'Vase en verre recyclé',
                'description' => 'Magnifique vase créé à partir de verre recyclé. Design artistique et écologique.',
                'category' => 'plastic',
                'condition' => 'new',
                'price' => 15.00,
                'status' => 'available',
                'user_id' => $user->id,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
