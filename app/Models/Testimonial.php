<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'title_ar', 'title_en',
        'content_ar', 'content_en',
        'rating', 'avatar', 'review_date', 'is_active', 'sort_order',
    ];

    protected $casts = ['review_date' => 'date'];

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? ($this->title_ar ?? '') : ($this->title_en ?? '');
    }

    public function getContentAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : $this->content_en;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
