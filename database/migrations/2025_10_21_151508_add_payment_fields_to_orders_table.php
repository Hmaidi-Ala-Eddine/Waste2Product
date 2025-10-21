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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->string('gateway')->nullable()->after('transaction_id');
            $table->json('gateway_response')->nullable()->after('gateway');
            $table->text('payment_notes')->nullable()->after('gateway_response');
            $table->timestamp('payment_processed_at')->nullable()->after('payment_notes');
            $table->text('shipping_address')->nullable()->after('payment_processed_at');
            $table->text('order_notes')->nullable()->after('shipping_address');
            
            // Update status enum to include 'completed'
            $table->enum('status', ['pending', 'paid', 'shipped', 'completed', 'cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status',
                'transaction_id', 
                'gateway',
                'gateway_response',
                'payment_notes',
                'payment_processed_at',
                'shipping_address',
                'order_notes'
            ]);
            
            // Revert status enum
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending')->change();
        });
    }
};
