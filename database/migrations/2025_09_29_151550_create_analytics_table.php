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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // Date for the analytics record
            $table->json('waste_data')->nullable(); // Waste request statistics
            $table->json('product_data')->nullable(); // Product statistics
            $table->json('order_data')->nullable(); // Order statistics
            $table->decimal('total_waste_quantity', 10, 2)->default(0); // Total waste collected
            $table->decimal('total_income', 10, 2)->default(0); // Total income from orders
            $table->integer('total_products')->default(0); // Total products created
            $table->integer('total_orders')->default(0); // Total orders made
            $table->timestamps();
            
            // Add index on date for faster queries
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
