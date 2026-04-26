<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $reviewsAr = [
            'منتجات رائعة وجودة ممتازة، سأطلب مرة أخرى بالتأكيد!',
            'التوصيل سريع والتغليف ممتاز، شكراً لكم.',
            'أفضل متجر أونلاين تعاملت معه، خدمة عملاء مميزة.',
            'القماش ممتاز والتصميم أنيق جداً.',
            'أحببت المجموعة الجديدة، ألوان جميلة وخامات ممتازة.',
        ];

        $reviewsEn = [
            'Amazing products and excellent quality, will definitely order again!',
            'Fast delivery and great packaging, thank you.',
            'Best online store I\'ve dealt with, outstanding customer service.',
            'Excellent fabric and very elegant design.',
            'Loved the new collection, beautiful colors and premium materials.',
        ];

        $index = fake()->numberBetween(0, count($reviewsAr) - 1);

        return [
            'name'        => fake()->name(),
            'title_ar'    => fake()->optional(0.7)->randomElement(['عميلة مميزة', 'مصممة أزياء', 'مدونة موضة']),
            'title_en'    => fake()->optional(0.7)->randomElement(['Valued Customer', 'Fashion Designer', 'Fashion Blogger']),
            'content_ar'  => $reviewsAr[$index],
            'content_en'  => $reviewsEn[$index],
            'rating'      => fake()->numberBetween(4, 5),
            'avatar'      => null,
            'review_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'is_active'   => true,
            'sort_order'  => fake()->numberBetween(0, 10),
        ];
    }
}
