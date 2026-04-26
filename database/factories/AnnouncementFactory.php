<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'text_ar'   => fake()->randomElement([
                'شحن مجاني لجميع الطلبات فوق 100 دينار',
                'خصم 20% على المجموعة الجديدة',
                'تسوقي الآن واستمتعي بعروضنا الحصرية',
                'أهلاً بالربيع! تشكيلة جديدة متوفرة الآن',
            ]),
            'text_en'   => fake()->randomElement([
                'Free shipping on all orders over 100 JOD',
                '20% off on the new collection',
                'Shop now and enjoy our exclusive offers',
                'Hello Spring! New collection available now',
            ]),
            'is_active' => true,
        ];
    }
}
