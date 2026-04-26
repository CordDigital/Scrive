<?php

namespace Database\Seeders;

use App\Models\ContactPage;
use Illuminate\Database\Seeder;

class ContactPageSeeder extends Seeder
{
    /**
     * Seed the Contact page content.
     */
    public function run(): void
    {
        ContactPage::create([
            'address_ar'  => 'عمّان، الأردن - شارع المدينة المنورة',
            'address_en'  => 'Amman, Jordan - Al Madina Al Munawwara St.',
            'phone'       => '+962 79 123 4567',
            'email'       => 'info@ziziabusalla.com',
            'map_url'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54086.1713738148!2d35.87!3d31.95!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1',
            'mon_fri_ar'  => '9:00 صباحاً - 6:00 مساءً',
            'mon_fri_en'  => '9:00 AM - 6:00 PM',
            'saturday_ar' => '10:00 صباحاً - 4:00 مساءً',
            'saturday_en' => '10:00 AM - 4:00 PM',
            'sunday_ar'   => 'مغلق',
            'sunday_en'   => 'Closed',
        ]);
    }
}
