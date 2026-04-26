<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Seed static pages (privacy policy, return policy, etc.).
     */
    public function run(): void
    {
        Page::create([
            'slug'       => 'privacy-policy',
            'content_ar' => '<h2>سياسة الخصوصية</h2><p>نحن في ZIZI ABUSALLA نحترم خصوصيتك ونلتزم بحماية معلوماتك الشخصية. تصف سياسة الخصوصية هذه كيفية جمع واستخدام وحماية المعلومات التي تقدمها لنا عبر موقعنا الإلكتروني.</p><h3>المعلومات التي نجمعها</h3><p>نجمع المعلومات التي تقدمها لنا طوعاً عند إنشاء حساب، إجراء عملية شراء، أو التواصل معنا. تشمل هذه المعلومات: الاسم، البريد الإلكتروني، رقم الهاتف، وعنوان الشحن.</p>',
            'content_en' => '<h2>Privacy Policy</h2><p>At ZIZI ABUSALLA, we respect your privacy and are committed to protecting your personal information. This privacy policy describes how we collect, use, and protect the information you provide to us through our website.</p><h3>Information We Collect</h3><p>We collect information that you voluntarily provide when creating an account, making a purchase, or contacting us. This includes: name, email, phone number, and shipping address.</p>',
        ]);

        Page::create([
            'slug'       => 'return-policy',
            'content_ar' => '<h2>سياسة الإرجاع والاستبدال</h2><p>نريد أن تكوني سعيدة بمشترياتك. إذا لم تكوني راضية عن المنتج، يمكنك إرجاعه أو استبداله خلال 30 يوماً من تاريخ الاستلام.</p><h3>شروط الإرجاع</h3><ul><li>يجب أن يكون المنتج بحالته الأصلية وغير مستخدم</li><li>يجب إرفاق الفاتورة الأصلية</li><li>المنتجات المخفضة غير قابلة للإرجاع</li></ul>',
            'content_en' => '<h2>Return & Exchange Policy</h2><p>We want you to be happy with your purchases. If you are not satisfied with a product, you can return or exchange it within 30 days of receipt.</p><h3>Return Conditions</h3><ul><li>Product must be in its original condition and unused</li><li>Original invoice must be included</li><li>Sale items are non-returnable</li></ul>',
        ]);

        Page::create([
            'slug'       => 'terms-conditions',
            'content_ar' => '<h2>الشروط والأحكام</h2><p>باستخدامك لموقع ZIZI ABUSALLA، فإنك توافق على الالتزام بهذه الشروط والأحكام. يرجى قراءتها بعناية قبل استخدام الموقع أو إجراء أي عملية شراء.</p>',
            'content_en' => '<h2>Terms & Conditions</h2><p>By using the ZIZI ABUSALLA website, you agree to be bound by these terms and conditions. Please read them carefully before using the site or making any purchases.</p>',
        ]);
    }
}
