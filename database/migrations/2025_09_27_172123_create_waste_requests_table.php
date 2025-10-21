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
        Schema::create('waste_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Customer who requested
            $table->foreignId('collector_id')->nullable()->constrained('users')->onDelete('set null'); // Assigned collector
            $table->enum('waste_type', ['organic', 'plastic', 'metal', 'e-waste', 'paper', 'glass', 'textile', 'mixed']);
            $table->decimal('quantity', 8, 2); // Weight in kg
            $table->text('address'); // Pickup location
            $table->text('description')->nullable(); // Additional details
            $table->enum('status', ['pending', 'accepted', 'collected', 'cancelled'])->default('pending');
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_requests');
    }
};
