<?php

namespace Database\Factories;

use App\Models\Benefit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Benefit>
 */
class BenefitFactory extends Factory
{
    protected $model = Benefit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $benefits = [
            ['icon' => 'fas fa-shipping-fast', 'ar' => 'شحن مجاني', 'en' => 'Free Shipping', 'desc_ar' => 'شحن مجاني لجميع الطلبات فوق 100 دينار', 'desc_en' => 'Free shipping on all orders over 100 JOD'],
            ['icon' => 'fas fa-undo',          'ar' => 'إرجاع سهل', 'en' => 'Easy Returns', 'desc_ar' => 'إرجاع مجاني خلال 30 يوم', 'desc_en' => 'Free returns within 30 days'],
            ['icon' => 'fas fa-headset',       'ar' => 'دعم 24/7', 'en' => '24/7 Support', 'desc_ar' => 'فريق دعم متاح على مدار الساعة', 'desc_en' => 'Support team available around the clock'],
            ['icon' => 'fas fa-lock',          'ar' => 'دفع آمن', 'en' => 'Secure Payment', 'desc_ar' => 'جميع المعاملات مشفرة وآمنة', 'desc_en' => 'All transactions are encrypted and secure'],
        ];

        $benefit = fake()->randomElement($benefits);

        return [
            'icon'           => $benefit['icon'],
            'title_ar'       => $benefit['ar'],
            'title_en'       => $benefit['en'],
            'description_ar' => $benefit['desc_ar'],
            'description_en' => $benefit['desc_en'],
            'is_active'      => true,
            'sort_order'     => fake()->numberBetween(0, 10),
        ];
    }
}
