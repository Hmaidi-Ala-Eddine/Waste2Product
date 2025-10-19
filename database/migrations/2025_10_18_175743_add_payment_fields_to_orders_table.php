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
            $table->string('payment_status')->default('pending')->after('payment_method'); // pending, processing, completed, failed, refunded
            $table->string('transaction_id')->nullable()->after('payment_status'); // External payment processor ID
            $table->string('gateway')->nullable()->after('transaction_id'); // stripe, paypal, bank, cash
            $table->json('gateway_response')->nullable()->after('gateway'); // Store gateway response data
            $table->text('payment_notes')->nullable()->after('gateway_response');
            $table->timestamp('payment_processed_at')->nullable()->after('payment_notes');
            $table->string('shipping_address')->nullable()->after('payment_processed_at');
            $table->text('order_notes')->nullable()->after('shipping_address');
            
            $table->index(['payment_status', 'payment_method']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'payment_method']);
            $table->dropIndex(['transaction_id']);
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
        });
    }
};