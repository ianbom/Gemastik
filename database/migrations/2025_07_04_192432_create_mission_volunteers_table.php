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
        Schema::create('mission_volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_id')->constrained('missions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('participation_status', ['pending', 'confirmed', 'cancelled', 'attended'])->default('pending');
            $table->boolean('is_leader')->default(false);
            $table->string('certificate_url')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->unique(['mission_id', 'user_id']); // Satu user hanya bisa berpartisipasi sekali per misi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_volunteers');
    }
};
