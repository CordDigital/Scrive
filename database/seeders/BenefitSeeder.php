<?php

namespace Database\Seeders;

use App\Models\Benefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Seed store benefits/features.
     */
    public function run(): void
    {
        $benefits = [
            [
                'icon'           => 'fas fa-shipping-fast',
                'title_ar'       => 'شحن مجاني',
                'title_en'       => 'Free Shipping',
                'description_ar' => 'شحن مجاني لجميع الطلبات فوق 100 دينار',
                'description_en' => 'Free shipping on all orders over 100 JOD',
                'is_active'      => true,
                'sort_order'     => 1,
            ],
            [
                'icon'           => 'fas fa-undo',
                'title_ar'       => 'إرجاع سهل',
                'title_en'       => 'Easy Returns',
                'description_ar' => 'إرجاع مجاني خلال 30 يوم من تاريخ الشراء',
                'description_en' => 'Free returns within 30 days of purchase',
                'is_active'      => true,
                'sort_order'     => 2,
            ],
            [
                'icon'           => 'fas fa-headset',
                'title_ar'       => 'دعم على مدار الساعة',
                'title_en'       => '24/7 Support',
                'description_ar' => 'فريق دعم متاح على مدار الساعة لمساعدتك',
                'description_en' => 'Support team available around the clock to help you',
                'is_active'      => true,
                'sort_order'     => 3,
            ],
            [
                'icon'           => 'fas fa-lock',
                'title_ar'       => 'دفع آمن',
                'title_en'       => 'Secure Payment',
                'description_ar' => 'جميع المعاملات مشفرة وآمنة بالكامل',
                'description_en' => 'All transactions are fully encrypted and secure',
                'is_active'      => true,
                'sort_order'     => 4,
            ],
        ];

        foreach ($benefits as $benefit) {
            Benefit::create($benefit);
        }
    }
}
