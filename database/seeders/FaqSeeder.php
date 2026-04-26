<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Seed FAQs with realistic bilingual content.
     */
    public function run(): void
    {
        $faqs = [
            [
                'category_ar' => 'الشحن والتوصيل', 'category_en' => 'Shipping & Delivery',
                'question_ar' => 'كم يستغرق التوصيل؟', 'question_en' => 'How long does delivery take?',
                'answer_ar' => 'يستغرق التوصيل من 3 إلى 7 أيام عمل حسب موقعك. التوصيل داخل عمّان يستغرق 1-2 يوم عمل.',
                'answer_en' => 'Delivery takes 3-7 business days depending on your location. Delivery within Amman takes 1-2 business days.',
                'sort_order' => 1,
            ],
            [
                'category_ar' => 'الشحن والتوصيل', 'category_en' => 'Shipping & Delivery',
                'question_ar' => 'هل تشحنون دولياً؟', 'question_en' => 'Do you ship internationally?',
                'answer_ar' => 'نعم، نشحن إلى دول الخليج والشرق الأوسط. رسوم الشحن تختلف حسب الدولة.',
                'answer_en' => 'Yes, we ship to Gulf and Middle East countries. Shipping fees vary by country.',
                'sort_order' => 2,
            ],
            [
                'category_ar' => 'الإرجاع والاستبدال', 'category_en' => 'Returns & Exchanges',
                'question_ar' => 'هل يمكنني إرجاع المنتج؟', 'question_en' => 'Can I return a product?',
                'answer_ar' => 'نعم، يمكنك إرجاع المنتج خلال 30 يوماً من تاريخ الاستلام بشرط أن يكون بحالته الأصلية.',
                'answer_en' => 'Yes, you can return products within 30 days of receipt provided they are in their original condition.',
                'sort_order' => 3,
            ],
            [
                'category_ar' => 'الإرجاع والاستبدال', 'category_en' => 'Returns & Exchanges',
                'question_ar' => 'كيف أستبدل منتجاً؟', 'question_en' => 'How do I exchange a product?',
                'answer_ar' => 'يمكنك التواصل معنا عبر صفحة اتصل بنا أو الواتساب لترتيب الاستبدال.',
                'answer_en' => 'You can contact us through our Contact page or WhatsApp to arrange an exchange.',
                'sort_order' => 4,
            ],
            [
                'category_ar' => 'الدفع', 'category_en' => 'Payment',
                'question_ar' => 'ما هي طرق الدفع المتاحة؟', 'question_en' => 'What payment methods are available?',
                'answer_ar' => 'نقبل الدفع نقداً عند الاستلام، بطاقات الائتمان (فيزا/ماستركارد)، وPayPal.',
                'answer_en' => 'We accept cash on delivery, credit cards (Visa/Mastercard), and PayPal.',
                'sort_order' => 5,
            ],
            [
                'category_ar' => 'المنتجات', 'category_en' => 'Products',
                'question_ar' => 'كيف أختار المقاس المناسب؟', 'question_en' => 'How do I choose the right size?',
                'answer_ar' => 'يمكنك الاطلاع على جدول المقاسات المتوفر في صفحة كل منتج. إذا كنت غير متأكدة، تواصلي معنا.',
                'answer_en' => 'You can check the size chart available on each product page. If you are unsure, feel free to contact us.',
                'sort_order' => 6,
            ],
            [
                'category_ar' => 'الحساب', 'category_en' => 'Account',
                'question_ar' => 'هل أحتاج لإنشاء حساب للتسوق؟', 'question_en' => 'Do I need an account to shop?',
                'answer_ar' => 'لا، يمكنك التسوق كزائر. لكن إنشاء حساب يتيح لك تتبع طلباتك وحفظ المفضلة.',
                'answer_en' => 'No, you can shop as a guest. However, creating an account lets you track orders and save favorites.',
                'sort_order' => 7,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
