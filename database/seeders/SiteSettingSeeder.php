<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Seed site settings.
     */
    public function run(): void
    {
        SiteSetting::create([
            'header_logo'         => null,
            'footer_logo'         => null,
            'favicon'             => null,
            'seo_title'           => 'ZIZI ABUSALLA | Fashion & Style',
            'seo_description'     => 'Discover the latest fashion trends and shop exclusive collections at ZIZI ABUSALLA. Premium quality, affordable prices.',
            'seo_keywords'        => 'fashion, women clothing, dresses, abayas, accessories, online shop, Jordan',
            'og_image'            => null,
            'og_site_name'        => 'ZIZI ABUSALLA',
            'og_type'             => 'website',
            'twitter_card'        => 'summary_large_image',
            'twitter_handle'      => '@ziziabusalla',
            'google_analytics'    => null,
            'google_verification' => null,
            'facebook_pixel'      => null,
        ]);
    }
}
