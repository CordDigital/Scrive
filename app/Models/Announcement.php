<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['text_ar', 'text_en', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
