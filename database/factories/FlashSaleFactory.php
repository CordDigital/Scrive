<?php

namespace Database\Factories;

use App\Models\FlashSale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashSale>
 */
class FlashSaleFactory extends Factory
{
    protected $model = FlashSale::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title_ar'         => fake()->randomElement(['تخفيضات نهاية الموسم', 'عروض خاصة', 'تخفيضات حصرية', 'عرض اليوم']),
            'title_en'         => fake()->randomElement(['End of Season Sale', 'Special Offers', 'Exclusive Deals', 'Deal of the Day']),
            'subtitle_ar'      => fake()->optional(0.7)->randomElement(['خصم يصل إلى 50%', 'لا تفوت الفرصة', 'لفترة محدودة']),
            'subtitle_en'      => fake()->optional(0.7)->randomElement(['Up to 50% off', 'Don\'t miss out', 'Limited time only']),
            'image'            => null,
            'discount_percent' => fake()->randomElement([10, 15, 20, 25, 30, 50]),
            'min_amount'       => fake()->randomElement([50, 100, 120, 200]),
            'ends_at'          => fake()->dateTimeBetween('+1 day', '+30 days'),
            'is_active'        => true,
        ];
    }
}
