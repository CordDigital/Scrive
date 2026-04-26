<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramImage extends Model
{
    protected $fillable = ['image', 'url', 'is_active', 'sort_order'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}