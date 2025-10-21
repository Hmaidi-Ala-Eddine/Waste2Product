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
            $table->date('date');
            $table->json('waste_data')->nullable();
            $table->json('product_data')->nullable();
            $table->json('order_data')->nullable();
            $table->decimal('total_waste_quantity', 10, 2)->default(0);
            $table->decimal('total_income', 10, 2)->default(0);
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->timestamps();
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
