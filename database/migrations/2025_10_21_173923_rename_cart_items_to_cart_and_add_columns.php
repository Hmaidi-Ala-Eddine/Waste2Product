<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename cart_items to cart
        Schema::rename('cart_items', 'cart');
        
        // Add missing columns to cart table
        Schema::table('cart', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('id');
            $table->decimal('price', 10, 2)->default(0.00)->after('quantity');
            $table->decimal('total_price', 10, 2)->default(0.00)->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added columns
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['session_id', 'price', 'total_price']);
        });
        
        // Rename cart back to cart_items
        Schema::rename('cart', 'cart_items');
    }
};