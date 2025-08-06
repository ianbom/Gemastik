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
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable()->constrained('reports')->onDelete('set null'); // Misi bisa terkait report atau berdiri sendiri
            $table->foreignId('creator_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->text('description')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['open', 'on-progress', 'completed', 'cancelled', 'under-authority'])->default('open');
            $table->timestamp('scheduled_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('assigned_to_type', ['community', 'volunteer'])->nullable();
            $table->boolean('is_point_shared')->default(false);
            $table->foreignId('assigned_volunteer_id')->nullable()->constrained('users')->onDelete('set null'); // Jika ditugaskan ke individu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
