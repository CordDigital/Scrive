<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /**
     * Seed social media links.
     */
    public function run(): void
    {
        $links = [
            ['platform' => 'facebook',  'icon' => 'fab fa-facebook-f',  'url' => 'https://facebook.com/ziziabusalla',  'sort_order' => 1],
            ['platform' => 'instagram', 'icon' => 'fab fa-instagram',   'url' => 'https://instagram.com/ziziabusalla', 'sort_order' => 2],
            ['platform' => 'twitter',   'icon' => 'fab fa-twitter',     'url' => 'https://twitter.com/ziziabusalla',   'sort_order' => 3],
            ['platform' => 'tiktok',    'icon' => 'fab fa-tiktok',      'url' => 'https://tiktok.com/@ziziabusalla',   'sort_order' => 4],
            ['platform' => 'whatsapp',  'icon' => 'fab fa-whatsapp',    'url' => 'https://wa.me/962791234567',          'sort_order' => 5],
        ];

        foreach ($links as $link) {
            SocialLink::create(array_merge($link, ['is_active' => true]));
        }
    }
}
