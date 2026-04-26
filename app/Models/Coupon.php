<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order',
        'max_uses', 'used_count', 'is_active', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>=', now());
                     });
    }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    public function calcDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_order) return 0;

        if ($this->type === 'percent') {
            return round($subtotal * $this->value / 100, 2);
        }
        return min($this->value, $subtotal);
    }
}
