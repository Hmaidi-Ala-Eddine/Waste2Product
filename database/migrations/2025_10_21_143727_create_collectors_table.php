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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->text('service_areas')->nullable();
            $table->decimal('capacity_kg', 8, 2)->nullable();
            $table->text('availability_schedule')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'suspended'])->default('pending');
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_collections')->default(0);
            $table->text('bio')->nullable();
            $table->timestamps();
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
