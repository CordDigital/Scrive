<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'color_ar',
        'color_en',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getColorAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->color_ar ?? '')
            : ($this->color_en ?? '');
    }
}
