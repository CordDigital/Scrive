<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Seed coupons with realistic discount data.
     */
    public function run(): void
    {
        // Create specific well-known coupons
        Coupon::create([
            'code'       => 'WELCOME10',
            'type'       => 'percent',
            'value'      => 10,
            'min_order'  => 50,
            'max_uses'   => 1000,
            'used_count' => 0,
            'is_active'  => true,
            'expires_at' => now()->addMonths(6),
        ]);

        Coupon::create([
            'code'       => 'SUMMER25',
            'type'       => 'percent',
            'value'      => 25,
            'min_order'  => 100,
            'max_uses'   => 500,
            'used_count' => 0,
            'is_active'  => true,
            'expires_at' => now()->addMonths(3),
        ]);

        Coupon::create([
            'code'       => 'FLAT20',
            'type'       => 'fixed',
            'value'      => 20,
            'min_order'  => 80,
            'max_uses'   => null,
            'used_count' => 0,
            'is_active'  => true,
            'expires_at' => null,
        ]);

        // Create additional random coupons
        Coupon::factory()->count(5)->create();

        // Create 2 expired coupons
        Coupon::factory()->count(2)->expired()->create();
    }
}
