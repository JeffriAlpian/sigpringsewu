<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'rating',
        'review_count',
        'location',
        'district',
        'description',
        'long_description',
        'image',
        'images',
        'weekday_price',
        'weekend_price',
        'open_hours',
        'status',
        'address',
        'phone',
        'map_x',
        'map_y',
        'latitude',
        'longitude',
        'tags',
    ];

    protected $casts = [
        'rating' => 'float',
        'review_count' => 'integer',
        'images' => 'array',
        'tags' => 'array',
        'weekday_price' => 'integer',
        'weekend_price' => 'integer',
        'map_x' => 'float',
        'map_y' => 'float',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function getImageAttribute($value): ?string
    {
        if (! $value) {
            return null;
        }

        return str_starts_with($value, 'http') ? $value : asset(ltrim($value, '/'));
    }

    public function getImagesAttribute($value): array
    {
        $images = is_string($value) ? (json_decode($value, true) ?: []) : ($value ?: []);

        return array_map(fn ($img) => str_starts_with($img, 'http') ? $img : asset(ltrim($img, '/')), $images);
    }
}
