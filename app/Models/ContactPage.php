<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    protected $table = 'contact_page';

    protected $fillable = [
        'address_ar', 'address_en',
        'phone', 'email', 'map_url',
        'mon_fri_ar', 'mon_fri_en',
        'saturday_ar', 'saturday_en',
        'sunday_ar', 'sunday_en',
    ];

    public function getAddressAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->address_ar : $this->address_en;
    }

    public function getMonFriAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->mon_fri_ar : $this->mon_fri_en;
    }

    public function getSaturdayAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->saturday_ar : $this->saturday_en;
    }

    public function getSundayAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->sunday_ar : $this->sunday_en;
    }
}