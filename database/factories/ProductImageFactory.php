<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     * Note: `image` column is NOT nullable.
     */
    public function definition(): array
    {
        $colors = [
            ['ar' => 'أحمر', 'en' => 'Red'],
            ['ar' => 'أزرق', 'en' => 'Blue'],
            ['ar' => 'أسود', 'en' => 'Black'],
            ['ar' => 'أبيض', 'en' => 'White'],
            ['ar' => 'أخضر', 'en' => 'Green'],
            ['ar' => 'وردي', 'en' => 'Pink'],
            ['ar' => 'بيج',  'en' => 'Beige'],
        ];

        $color = fake()->randomElement($colors);

        return [
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),
            'image'      => 'products/gallery/placeholder-' . fake()->numberBetween(1, 20) . '.jpg',
            'color_ar'   => fake()->optional(0.7)->randomElement(array_column($colors, 'ar')),
            'color_en'   => fake()->optional(0.7)->randomElement(array_column($colors, 'en')),
        ];
    }
}
