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
        // Add missing columns to products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'reserved_by')) {
                $table->string('reserved_by')->nullable()->after('status');
            }
            if (!Schema::hasColumn('products', 'reserved_at')) {
                $table->timestamp('reserved_at')->nullable()->after('reserved_by');
            }
            if (!Schema::hasColumn('products', 'reserved_message')) {
                $table->text('reserved_message')->nullable()->after('reserved_at');
            }
        });
        
        // Add missing columns to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'gateway')) {
                $table->string('gateway')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('orders', 'gateway_response')) {
                $table->json('gateway_response')->nullable()->after('gateway');
            }
            if (!Schema::hasColumn('orders', 'payment_notes')) {
                $table->text('payment_notes')->nullable()->after('gateway_response');
            }
            if (!Schema::hasColumn('orders', 'payment_processed_at')) {
                $table->timestamp('payment_processed_at')->nullable()->after('payment_notes');
            }
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('payment_processed_at');
            }
            if (!Schema::hasColumn('orders', 'order_notes')) {
                $table->text('order_notes')->nullable()->after('shipping_address');
            }
        });
        
        // Update orders status enum
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending','paid','shipped','completed','cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['reserved_by', 'reserved_at', 'reserved_message']);
        });
        
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
            $table->enum('status', ['pending','completed','cancelled'])->default('pending')->change();
        });
    }
};