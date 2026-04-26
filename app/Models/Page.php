<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'content_ar',
        'content_en',
    ];

    public function getContentAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->content_ar ?? '')
            : ($this->content_en ?? '');
    }
}
