<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'first_name', 'last_name',
        'email', 'phone', 'country', 'city', 'address',
        'postal_code', 'note', 'coupon_code',
        'subtotal', 'discount', 'shipping', 'total',
        'payment_method', 'payment_status', 'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'processing' => 'info',
            'shipped'    => 'primary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped'    => 'تم الشحن',
            'delivered'  => 'تم التسليم',
            'cancelled'  => 'ملغي',
            default      => $this->status,
        };
    }
}
