<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
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

    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->attributes['description_ar']
            : $this->attributes['description_en'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
