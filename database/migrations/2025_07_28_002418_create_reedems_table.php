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
    Schema::create('redeems', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
    $table->foreignId('merchandise_id')->constrained('merchandise')->onDelete('cascade');
    $table->unsignedInteger('points_spent');
    $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
    $table->text('shipping_address')->nullable();
    $table->string('logistic')->nullable();
    $table->string('tracking_number')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reedems');
    }
};
