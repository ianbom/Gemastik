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
         Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->enum('content_type', ['video', 'modul', 'artikel'])->nullable(); // Misal: 'article', 'news', 'guide'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
