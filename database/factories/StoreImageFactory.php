<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StoreImage>
 */
class StoreImageFactory extends Factory
{
    protected $model = StoreImage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id'   => Store::inRandomOrder()->value('id') ?? Store::factory(),
            'image_path' => 'stores/gallery/placeholder-' . fake()->numberBetween(1, 5) . '.jpg',
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
