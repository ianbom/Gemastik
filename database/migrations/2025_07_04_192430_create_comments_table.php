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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reply_id')->nullable()->constrained('comments')->onDelete('cascade'); // Untuk balasan komentar
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->text('comment')->nullable();
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable(); // Bisa 'image', 'video', atau lainnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
