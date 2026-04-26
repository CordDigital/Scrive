<?php

namespace Database\Factories;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slider>
 */
class SliderFactory extends Factory
{
    protected $model = Slider::class;

    /**
     * Define the model's default state.
     * All string columns in sliders are NOT nullable.
     */
    public function definition(): array
    {
        return [
            'title_ar'    => fake()->randomElement(['مجموعة الربيع الجديدة', 'أناقة بلا حدود', 'تشكيلة حصرية', 'أحدث الصيحات']),
            'title_en'    => fake()->randomElement(['New Spring Collection', 'Endless Elegance', 'Exclusive Collection', 'Latest Trends']),
            'subtitle_ar' => fake()->randomElement(['اكتشفي تشكيلتنا الجديدة', 'تسوقي الآن', 'عروض لا تفوت']),
            'subtitle_en' => fake()->randomElement(['Discover our new collection', 'Shop now', 'Unmissable offers']),
            'image'       => 'sliders/placeholder-' . fake()->numberBetween(1, 4) . '.jpg',
            'is_active'   => true,
            'sort_order'  => fake()->numberBetween(0, 5),
        ];
    }
}
