<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialLink>
 */
class SocialLinkFactory extends Factory
{
    protected $model = SocialLink::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $platforms = [
            ['platform' => 'facebook',  'icon' => 'fab fa-facebook-f',  'url' => 'https://facebook.com/'],
            ['platform' => 'instagram', 'icon' => 'fab fa-instagram',   'url' => 'https://instagram.com/'],
            ['platform' => 'twitter',   'icon' => 'fab fa-twitter',     'url' => 'https://twitter.com/'],
            ['platform' => 'tiktok',    'icon' => 'fab fa-tiktok',      'url' => 'https://tiktok.com/@'],
            ['platform' => 'youtube',   'icon' => 'fab fa-youtube',     'url' => 'https://youtube.com/'],
            ['platform' => 'whatsapp',  'icon' => 'fab fa-whatsapp',    'url' => 'https://wa.me/'],
        ];

        $p = fake()->randomElement($platforms);

        return [
            'platform'   => $p['platform'],
            'url'        => $p['url'] . fake()->userName(),
            'icon'       => $p['icon'],
            'is_active'  => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
