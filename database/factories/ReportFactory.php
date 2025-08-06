<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     public function definition(): array
    {
        // Ambil data lokasi yang konsisten
        $province = Province::inRandomOrder()->first();
        $city = City::where('province_id', $province->id)->inRandomOrder()->first();
        $district = District::where('city_id', $city->id)->inRandomOrder()->first();

        $status = fake()->randomElement(['pending', 'verified', 'on-progress', 'rejected', 'completed', 'under-authority']);
        $isVerified = in_array($status, ['verified', 'on-progress', 'completed', 'under-authority']);
        $isCompleted = $status === 'completed';

        return [
            'reporter_id' => 1,
            'province_id' => $province->id,
            'city_id' => $city->id,
            'district_id' => $district->id,
            'verified_by_user_id' => null,
            'completed_by_user_id' => null,
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(5),
            'category' => fake()->randomElement([
                'sampah-plastik',
                'pencemaran-air',
                'pencemaran-udara',
                'pencemaran-tanah',
                'limbah-industri',
                'emisi-gas-rumah-kaca',
                'penggundulan-kebakaran-hutan',
                'naiknya-permukaan-air-laut',
                'limbah-pertanian-peternakan',
                'lainnya'
            ]),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'address' => fake()->address(),
            'status' => $status,
            'upvotes_count' => 0,
            'dislikes_count' => 0,
            'verified_at' => null,
            'completion_details' => null
        ];
    }
}
