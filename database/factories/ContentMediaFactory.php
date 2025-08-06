<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class ContentMediaFactory extends Factory
{
    /**
     * Helper function untuk mengambil file acak dari direktori.
     *
     * @param string $directory
     * @param string $placeholder
     * @return string
     */
    private function getRandomFile(string $directory='contents', string $placeholder): string
    {

        $files = Storage::disk('public')->files($directory);

        if (empty($files)) {
            return $placeholder;
        }
        return fake()->randomElement($files);
    }

    /**
     * Define the model's default state (fallback to image).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'media_url' => $this->getRandomFile('contents', 'content/images/placeholder.jpg'),
            'media_type' => 'image',
        ];
    }

    /**
     * State untuk media tipe gambar (untuk artikel).
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_url' => $this->getRandomFile('contents', 'content/images/placeholder.jpg'),
            'media_type' => 'image',
        ]);
    }

    /**
     * State untuk media tipe video (untuk video).
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_url' => $this->getRandomFile('contents', 'content/videos/placeholder.mp4'),
            'media_type' => 'video',
        ]);
    }

    /**
     * State untuk media tipe dokumen (untuk modul).
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_url' => $this->getRandomFile('contents', 'content/documents/placeholder.pdf'),
            'media_type' => 'document',
        ]);
    }
}
