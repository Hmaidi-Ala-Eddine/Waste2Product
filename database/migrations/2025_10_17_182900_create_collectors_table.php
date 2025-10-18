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
        Schema::create('collectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who applied
            $table->string('company_name')->nullable(); // Optional company/organization name
            $table->enum('vehicle_type', ['truck', 'van', 'motorcycle', 'bicycle', 'cart', 'other'])->default('other');
            $table->json('service_areas')->nullable(); // Geographic zones they cover (JSON array)
            $table->decimal('capacity_kg', 8, 2)->default(0); // Max collection capacity in kg
            $table->json('availability_schedule')->nullable(); // Days/hours available (JSON)
            $table->enum('verification_status', ['pending', 'verified', 'suspended'])->default('pending');
            $table->decimal('rating', 3, 2)->default(0)->nullable(); // Average rating (0-5.00)
            $table->integer('total_collections')->default(0); // Total successful collections
            $table->text('bio')->nullable(); // Brief description/bio
            $table->timestamps();
            
            // Ensure one collector profile per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectors');
    }
};
