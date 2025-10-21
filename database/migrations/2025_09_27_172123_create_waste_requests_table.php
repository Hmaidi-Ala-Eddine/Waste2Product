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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('collector_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('waste_type', ['organic', 'plastic', 'metal', 'paper', 'other']);
            $table->decimal('quantity', 8, 2);
            $table->text('address');
            $table->text('description')->nullable();
            $table->string('state', 100)->nullable();
            $table->enum('status', ['pending', 'assigned', 'collected', 'cancelled'])->default('pending');
            $table->timestamp('collected_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('collector_id');
            $table->index('state');
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
