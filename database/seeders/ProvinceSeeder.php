<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Opsional: untuk menonaktifkan event atau foreign key checks

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Opsional: Nonaktifkan foreign key checks sementara untuk menghindari masalah urutan
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel terlebih dahulu jika ingin menjalankan seeder berulang kali
        // District::truncate();
        // City::truncate();
        // Province::truncate();

        // Data Provinsi
        $provincesData = [
            'Jawa Timur',
            'Jawa Tengah',
            'Jawa Barat',
            'DKI Jakarta',
            'DI Yogyakarta',
        ];

        foreach ($provincesData as $provinceName) {
            $province = Province::create(['name' => $provinceName]);

            // Data Kota/Kabupaten untuk setiap Provinsi
            if ($provinceName === 'Jawa Timur') {
                $citiesData = ['Surabaya', 'Malang', 'Kediri', 'Banyuwangi'];
                foreach ($citiesData as $cityName) {
                    $city = City::create([
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ]);

                    // Data Kecamatan (Districts) untuk setiap Kota/Kabupaten
                    if ($cityName === 'Surabaya') {
                        $districtsData = ['Sukolilo', 'Gubeng', 'Tegalsari'];
                    } elseif ($cityName === 'Malang') {
                        $districtsData = ['Lowokwaru', 'Blimbing', 'Sukun'];
                    } elseif ($cityName === 'Kediri') {
                        $districtsData = ['Kota Kediri', 'Mojoroto'];
                    } elseif ($cityName === 'Banyuwangi') {
                        $districtsData = ['Banyuwangi', 'Genteng'];
                    } else {
                        $districtsData = ['Default District 1', 'Default District 2'];
                    }

                    foreach ($districtsData as $districtName) {
                        District::create([
                            'city_id' => $city->id,
                            'name' => $districtName,
                        ]);
                    }
                }
            } elseif ($provinceName === 'Jawa Tengah') {
                $citiesData = ['Semarang', 'Surakarta', 'Magelang'];
                foreach ($citiesData as $cityName) {
                    $city = City::create([
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ]);

                    if ($cityName === 'Semarang') {
                        $districtsData = ['Semarang Tengah', 'Tembalang'];
                    } elseif ($cityName === 'Surakarta') {
                        $districtsData = ['Laweyan', 'Serengan'];
                    } else {
                        $districtsData = ['Default District 1', 'Default District 2'];
                    }

                    foreach ($districtsData as $districtName) {
                        District::create([
                            'city_id' => $city->id,
                            'name' => $districtName,
                        ]);
                    }
                }
            } elseif ($provinceName === 'Jawa Barat') {
                $citiesData = ['Bandung', 'Bekasi', 'Depok'];
                foreach ($citiesData as $cityName) {
                    $city = City::create([
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ]);

                    if ($cityName === 'Bandung') {
                        $districtsData = ['Coblong', 'Cibeunying Kaler'];
                    } else {
                        $districtsData = ['Default District 1', 'Default District 2'];
                    }

                    foreach ($districtsData as $districtName) {
                        District::create([
                            'city_id' => $city->id,
                            'name' => $districtName,
                        ]);
                    }
                }
            } elseif ($provinceName === 'DKI Jakarta') {
                $citiesData = ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'];
                foreach ($citiesData as $cityName) {
                    $city = City::create([
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ]);

                    if ($cityName === 'Jakarta Pusat') {
                        $districtsData = ['Tanah Abang', 'Menteng'];
                    } else {
                        $districtsData = ['Default District 1', 'Default District 2'];
                    }

                    foreach ($districtsData as $districtName) {
                        District::create([
                            'city_id' => $city->id,
                            'name' => $districtName,
                        ]);
                    }
                }
            } elseif ($provinceName === 'DI Yogyakarta') {
                $citiesData = ['Yogyakarta', 'Sleman', 'Bantul'];
                foreach ($citiesData as $cityName) {
                    $city = City::create([
                        'province_id' => $province->id,
                        'name' => $cityName,
                    ]);

                    if ($cityName === 'Yogyakarta') {
                        $districtsData = ['Gondokusuman', 'Danurejan'];
                    } else {
                        $districtsData = ['Default District 1', 'Default District 2'];
                    }

                    foreach ($districtsData as $districtName) {
                        District::create([
                            'city_id' => $city->id,
                            'name' => $districtName,
                        ]);
                    }
                }
            }
        }

        // Opsional: Aktifkan kembali foreign key checks
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
