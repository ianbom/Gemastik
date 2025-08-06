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
    Schema::create('merchandise', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->string('image_url');
    $table->integer('points_cost');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchandise');
    }
};
