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
        Schema::create('collector_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waste_request_id')->constrained('waste_requests')->onDelete('cascade');
            $table->foreignId('collector_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->text('review')->nullable();
            $table->timestamps();
            
            $table->unique('waste_request_id');
            $table->index('collector_id');
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collector_ratings');
    }
};
