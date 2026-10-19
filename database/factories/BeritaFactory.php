<?php

namespace Database\Factories;

use App\Models\Berita;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(4, true),
            'image' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=800&h=600&fit=crop',
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
