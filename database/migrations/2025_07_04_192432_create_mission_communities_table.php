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
        Schema::create('mission_communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_id')->constrained('missions')->onDelete('cascade');
            $table->foreignId('community_id')->constrained('communities')->onDelete('cascade');
            $table->enum('status', ['requested', 'rejected', 'accepted'])->default('requested');
            $table->timestamp('answered_at')->nullable();
            $table->string('certificate_url')->nullable();
            $table->timestamp('awarded_at')->nullable();
            $table->unique(['mission_id', 'community_id']); // Satu komunitas hanya bisa terlibat sekali per misi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mission_communities');
    }
};
