<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'title_ar', 'title_en', 'subtitle_ar', 'subtitle_en', 'image',
        'discount_percent', 'min_amount', 'ends_at', 'is_active',
    ];

    protected $casts = ['ends_at' => 'datetime'];

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getSubtitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? ($this->subtitle_ar ?? '') : ($this->subtitle_en ?? '');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('ends_at', '>', now());
    }
}
