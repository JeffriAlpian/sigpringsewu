<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Destination::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'category' => fake()->randomElement(['Wisata', 'UMKM']),
            'rating' => fake()->randomFloat(1, 3, 5),
            'review_count' => fake()->numberBetween(0, 5000),
            'location' => 'Kec. '.fake()->city(),
            'district' => fake()->city(),
            'description' => fake()->sentence(),
            'long_description' => fake()->paragraph(),
            'image' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop&auto=format',
            'images' => [
                'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop&auto=format',
                'https://images.unsplash.com/photo-1500534623283-312aade485b7?w=800&h=600&fit=crop&auto=format',
            ],
            'weekday_price' => fake()->randomElement([0, 5000, 10000, 15000, 20000]),
            'weekend_price' => fake()->randomElement([0, 10000, 15000, 20000, 25000]),
            'open_hours' => '08:00 – 17:00',
            'status' => fake()->randomElement(['Buka', 'Buka', 'Buka', 'Tutup', 'Musiman']),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'map_x' => fake()->randomFloat(2, 0, 100),
            'map_y' => fake()->randomFloat(2, 0, 100),
            'latitude' => fake()->randomFloat(6, -5.45, -5.25),
            'longitude' => fake()->randomFloat(6, 104.80, 105.10),
            'tags' => fake()->randomElements(['Alam', 'Keluarga', 'Bukit', 'Danau', 'Edukasi', 'Kuliner', 'Oleh-oleh'], 3),
        ];
    }
}
