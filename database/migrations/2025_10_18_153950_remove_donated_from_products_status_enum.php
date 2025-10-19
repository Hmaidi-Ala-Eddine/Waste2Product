<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any products with 'donated' status to 'sold'
        DB::table('products')->where('status', 'donated')->update(['status' => 'sold']);
        
        // Then modify the enum to remove 'donated'
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('available', 'sold', 'reserved') DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'donated' back to the enum
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('available', 'sold', 'donated', 'reserved') DEFAULT 'available'");
    }
};