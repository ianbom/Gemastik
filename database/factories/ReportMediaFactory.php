<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportMedia>
 */
class ReportMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $directory = 'reports';

        $files = Storage::disk('public')->files($directory);

        if (empty($files)) {
            return [
                'media_url' => $directory . '/placeholder.jpg',
                'media_type' => 'image',
            ];
        }

        // 4. Pilih satu file secara acak
        $randomFile = fake()->randomElement($files);

        // 5. Tentukan tipe media berdasarkan ekstensi file
        $extension = pathinfo($randomFile, PATHINFO_EXTENSION);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        $mediaType = in_array(strtolower($extension), $imageExtensions) ? 'image' : 'video';

        // 6. Kembalikan data
        return [
            // 'report_id' akan diisi secara otomatis saat kita memanggilnya dari ReportFactory
            'media_url' => $randomFile,
            'media_type' => $mediaType,
        ];
    }
}
