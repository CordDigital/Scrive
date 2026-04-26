<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $table = 'about_page';

    protected $fillable = [
        'title_ar', 'title_en',
        'description_ar', 'description_en',
        'image_1', 'image_2', 'image_3',
    ];

    // Accessor للعنوان
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->title_ar ?? '')
            : ($this->title_en ?? '');
    }

    // Accessor للوصف
    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->description_ar ?? '')
            : ($this->description_en ?? '');
    }
}
