<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug_ar',
        'slug_en',
        'description_ar',
        'description_en',
        'description_second_ar',
        'description_second_en',
        'cover_image',
        'thumbnail',
        'sort_order',
    ];


    public function images()
    {
        return $this->hasMany(StoreImage::class)->orderBy('sort_order');
    }


    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar'
            ? $this->name_ar
            : $this->name_en;
    }


    public function getDescriptionAttribute()
    {
        return app()->getLocale() == 'ar'
            ? $this->description_ar
            : $this->description_en;
    }


    public function getDescriptionSecondAttribute()
    {
        return app()->getLocale() == 'ar'
            ? $this->description_second_ar
            : $this->description_second_en;
    }


    public function getSlugAttribute()
    {
        return app()->getLocale() == 'ar'
            ? $this->slug_ar
            : $this->slug_en;
    }
}
