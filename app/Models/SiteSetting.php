<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'header_logo', 'footer_logo', 'favicon',
        'seo_title', 'seo_description', 'seo_keywords', 'og_image',
        'og_site_name', 'og_type', 'twitter_card', 'twitter_handle',
        'google_analytics', 'google_verification', 'facebook_pixel',
    ];

    public static function current(): self
    {
        return static::first() ?? new static();
    }
}
