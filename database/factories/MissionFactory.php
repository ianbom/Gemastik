<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mission>
 */
class MissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['open', 'on-progress', 'completed', 'cancelled', 'under-authority']);
        $isCompleted = $status === 'completed';
        $scheduledDate = fake()->dateTimeBetween('+1 week', '+3 months');

        $province = Province::inRandomOrder()->first();
        $city = City::where('province_id', $province->id)->inRandomOrder()->first();
        $district = District::where('city_id', $city->id)->inRandomOrder()->first();

        $relatedReport = fake()->boolean(100) ? Report::inRandomOrder()->first() : null;

        $directory = 'missions';

        $files = Storage::disk('public')->files($directory);


        $thumbnails = fake()->randomElement($files);

        return [
            'report_id' => $relatedReport?->id,
            'creator_user_id' => 1,
            'province_id' => $relatedReport?->province_id ?? $province->id,
            'city_id' => $relatedReport?->city_id ?? $city->id,
            'district_id' => $relatedReport?->district_id ?? $district->id,
            'title' => 'Misi Aksi ' . fake()->words(3, true),
            'thumbnail_url' => $thumbnails,
            'description' => fake()->paragraph(4),
            'latitude' => $relatedReport?->latitude ?? fake()->latitude(),
            'longitude' => $relatedReport?->longitude ?? fake()->longitude(),
            'address' => $relatedReport?->address ?? fake()->address(),
            'status' => $status,
            'scheduled_date' => $scheduledDate,
            'completed_at' =>  null,
            'assigned_to_type' => null,
            'assigned_volunteer_id' => null,
        ];
    }

}
