<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $nameEn = fake()->unique()->company();
        $nameAr = fake()->randomElement([
            'متجر الأناقة', 'بوتيك الزهور', 'دار الأزياء', 'بيت الموضة',
        ]) . ' ' . fake()->numberBetween(1, 50);

        return [
            'name_ar'              => $nameAr,
            'name_en'              => $nameEn,
            'slug_ar'              => Str::slug($nameAr, '-', null) ?: 'store-ar-' . fake()->unique()->numberBetween(1, 999),
            'slug_en'              => Str::slug($nameEn) ?: 'store-en-' . fake()->unique()->numberBetween(1, 999),
            'description_ar'       => fake()->paragraphs(2, true),
            'description_en'       => fake()->paragraphs(2, true),
            'description_second_ar'=> fake()->optional(0.5)->paragraph(),
            'description_second_en'=> fake()->optional(0.5)->paragraph(),
            'cover_image'          => null,
            'thumbnail'            => null,
            'sort_order'           => fake()->numberBetween(0, 10),
        ];
    }
}
