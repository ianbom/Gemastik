<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\ContentMedia;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
   public function definition(): array
    {
        return [
            'author_user_id' => User::inRandomOrder()->first()->id,
            'title' => fake()->sentence(nbWords: 5),
            'body' => fake()->paragraphs(4, true), // Menghasilkan teks HTML
            'content_type' => fake()->randomElement(['video', 'modul', 'artikel']),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        // afterCreating() berjalan SETELAH sebuah record 'Content' dibuat.
        return $this->afterCreating(function (Content $content) {

            // Di sinilah logika kondisionalnya berada.
            if ($content->content_type === 'artikel') {
                // Jika tipe konten adalah 'artikel', panggil factory media dengan state 'image'.
                ContentMedia::factory()->image()->create([
                    'content_id' => $content->id,
                ]);
            }
            elseif ($content->content_type === 'video') {
                // Jika tipe konten adalah 'video', panggil factory media dengan state 'video'.
                ContentMedia::factory()->video()->create([
                    'content_id' => $content->id,
                ]);
            }
            elseif ($content->content_type === 'modul') {
                // Jika tipe konten adalah 'modul', panggil factory media dengan state 'document'.
                ContentMedia::factory()->document()->create([
                    'content_id' => $content->id,
                ]);
            }
        });
    }
}
