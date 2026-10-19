<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('data/destinations.json'));
        $data = json_decode($json, true);

        Destination::truncate();

        foreach ($data as $item) {
            // Melewati data kategori 'Budaya' agar tidak dimasukkan ke database
            $category = $item['category'];
            if ($category === 'Budaya') {
                continue;
            }

            Destination::create([
                'name' => $item['name'],
                'category' => $category,
                'rating' => $item['rating'],
                'review_count' => $item['reviewCount'],
                'location' => $item['location'],
                'district' => $item['district'],
                'description' => $item['description'],
                'long_description' => $item['longDescription'],
                'image' => $item['image'],
                'images' => $item['images'], // otomatis dicast ke JSON oleh Model
                'weekday_price' => $item['weekdayPrice'] ?? 0,
                'weekend_price' => $item['weekendPrice'] ?? 0,
                'open_hours' => $item['openHours'],
                'status' => $item['status'],
                'address' => $item['address'],
                'phone' => $item['phone'] ?? null,
                'map_x' => $item['mapX'] ?? null,
                'map_y' => $item['mapY'] ?? null,
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
                'tags' => $item['tags'], // otomatis dicast ke JSON oleh Model
            ]);
        }
    }
}
