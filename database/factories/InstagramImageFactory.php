<?php

namespace Database\Factories;

use App\Models\InstagramImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InstagramImage>
 */
class InstagramImageFactory extends Factory
{
    protected $model = InstagramImage::class;

    /**
     * Define the model's default state.
     * Note: `image` column is NOT nullable.
     */
    public function definition(): array
    {
        return [
            'image'      => 'instagram/placeholder-' . fake()->unique()->numberBetween(1, 100) . '.jpg',
            'url'        => 'https://www.instagram.com/p/' . fake()->regexify('[A-Za-z0-9]{11}'),
            'is_active'  => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
