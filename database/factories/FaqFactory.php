<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $faqs = [
            [
                'cat_ar' => 'الشحن والتوصيل', 'cat_en' => 'Shipping & Delivery',
                'q_ar' => 'كم يستغرق التوصيل؟', 'q_en' => 'How long does delivery take?',
                'a_ar' => 'يستغرق التوصيل من 3 إلى 7 أيام عمل حسب موقعك.', 'a_en' => 'Delivery takes 3-7 business days depending on your location.',
            ],
            [
                'cat_ar' => 'الإرجاع والاستبدال', 'cat_en' => 'Returns & Exchanges',
                'q_ar' => 'هل يمكنني إرجاع المنتج؟', 'q_en' => 'Can I return a product?',
                'a_ar' => 'نعم، يمكنك الإرجاع خلال 30 يوماً من تاريخ الاستلام.', 'a_en' => 'Yes, you can return products within 30 days of receipt.',
            ],
            [
                'cat_ar' => 'الدفع', 'cat_en' => 'Payment',
                'q_ar' => 'ما هي طرق الدفع المتاحة؟', 'q_en' => 'What payment methods are available?',
                'a_ar' => 'نقبل الدفع نقداً عند الاستلام، بطاقات الائتمان، وPayPal.', 'a_en' => 'We accept cash on delivery, credit cards, and PayPal.',
            ],
            [
                'cat_ar' => 'المنتجات', 'cat_en' => 'Products',
                'q_ar' => 'كيف أختار المقاس المناسب؟', 'q_en' => 'How do I choose the right size?',
                'a_ar' => 'يمكنك الاطلاع على جدول المقاسات المتوفر في صفحة كل منتج.', 'a_en' => 'You can check the size chart available on each product page.',
            ],
            [
                'cat_ar' => 'الحساب', 'cat_en' => 'Account',
                'q_ar' => 'هل أحتاج لإنشاء حساب للتسوق؟', 'q_en' => 'Do I need an account to shop?',
                'a_ar' => 'لا، يمكنك التسوق كزائر، لكن إنشاء حساب يسهل تتبع طلباتك.', 'a_en' => 'No, you can shop as a guest, but creating an account makes it easier to track your orders.',
            ],
        ];

        $faq = fake()->randomElement($faqs);

        return [
            'category_ar' => $faq['cat_ar'],
            'category_en' => $faq['cat_en'],
            'question_ar' => $faq['q_ar'],
            'question_en' => $faq['q_en'],
            'answer_ar'   => $faq['a_ar'],
            'answer_en'   => $faq['a_en'],
            'is_active'   => true,
            'sort_order'  => fake()->numberBetween(0, 10),
        ];
    }
}
