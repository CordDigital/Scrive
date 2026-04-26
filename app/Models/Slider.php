<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

   
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->attributes['title_ar']
            : $this->attributes['title_en'];
    }

    public function getSubtitleAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->attributes['subtitle_ar']
            : $this->attributes['subtitle_en'];
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
