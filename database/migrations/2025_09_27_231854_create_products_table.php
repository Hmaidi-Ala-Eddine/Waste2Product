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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('waste_request_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->text('description');
            $table->string('category');
            $table->enum('condition', ['new', 'refurbished', 'used']);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('status', ['available', 'sold', 'donated', 'reserved'])->default('available');
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('waste_request_id')->references('id')->on('waste_requests')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['category', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
