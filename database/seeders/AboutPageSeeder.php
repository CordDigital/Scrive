<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Seed the About page content.
     * Table columns: id, description_ar, description_en, image_1, image_2, image_3
     * (No title columns exist in the migration)
     */
    public function run(): void
    {
        AboutPage::create([
            'description_ar' => 'نحن في ZIZI ABUSALLA نؤمن بأن الموضة هي تعبير عن الذات. نقدم تشكيلة متنوعة من الأزياء العصرية والكلاسيكية التي تناسب كل امرأة. منذ تأسيسنا، حرصنا على تقديم أفضل الخامات والتصاميم بأسعار مناسبة. رؤيتنا هي أن نكون الوجهة الأولى للتسوق الإلكتروني في المنطقة.',
            'description_en' => 'At ZIZI ABUSALLA, we believe fashion is a form of self-expression. We offer a diverse collection of contemporary and classic fashion that suits every woman. Since our founding, we have been committed to providing the best materials and designs at affordable prices. Our vision is to become the premier online shopping destination in the region.',
            'image_1'        => 'about/placeholder-1.jpg',
            'image_2'        => 'about/placeholder-2.jpg',
            'image_3'        => 'about/placeholder-3.jpg',
        ]);
    }
}
