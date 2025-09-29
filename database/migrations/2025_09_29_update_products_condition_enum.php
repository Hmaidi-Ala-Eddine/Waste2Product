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
        // Check if condition_new column already exists and drop it if it does
        if (Schema::hasColumn('products', 'condition_new')) {
            DB::statement("ALTER TABLE products DROP COLUMN condition_new");
        }
        
        // First, add a temporary column with the new enum values
        DB::statement("ALTER TABLE products ADD COLUMN condition_new ENUM('excellent', 'good', 'fair', 'poor') DEFAULT 'good'");
        
        // Update the new column with mapped values
        DB::table('products')->where('condition', 'new')->update(['condition_new' => 'excellent']);
        DB::table('products')->where('condition', 'refurbished')->update(['condition_new' => 'good']);
        DB::table('products')->where('condition', 'used')->update(['condition_new' => 'fair']);
        
        // Drop the old column and rename the new one (escape 'condition' as it's a reserved keyword)
        DB::statement("ALTER TABLE products DROP COLUMN `condition`");
        DB::statement("ALTER TABLE products CHANGE condition_new `condition` ENUM('excellent', 'good', 'fair', 'poor') DEFAULT 'good'");
        
        // Also make description nullable since it's optional in the form
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add temporary column with old enum values
        DB::statement("ALTER TABLE products ADD COLUMN condition_old ENUM('new', 'refurbished', 'used') DEFAULT 'new'");
        
        // Map values back
        DB::table('products')->where('condition', 'excellent')->update(['condition_old' => 'new']);
        DB::table('products')->where('condition', 'good')->update(['condition_old' => 'refurbished']);
        DB::table('products')->where('condition', 'fair')->update(['condition_old' => 'used']);
        DB::table('products')->where('condition', 'poor')->update(['condition_old' => 'used']);
        
        // Drop new column and rename old one (escape 'condition' as it's a reserved keyword)
        DB::statement("ALTER TABLE products DROP COLUMN `condition`");
        DB::statement("ALTER TABLE products CHANGE condition_old `condition` ENUM('new', 'refurbished', 'used') DEFAULT 'new'");
        
        // Make description required again
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });
    }
};
