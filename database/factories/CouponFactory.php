<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coupon>
 */
class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['percent', 'fixed']);

        return [
            'code'       => strtoupper(fake()->unique()->bothify('SALE-####')),
            'type'       => $type,
            'value'      => $type === 'percent' ? fake()->numberBetween(5, 50) : fake()->numberBetween(5, 100),
            'min_order'  => fake()->randomElement([0, 50, 100, 200]),
            'max_uses'   => fake()->optional(0.6)->numberBetween(10, 500),
            'used_count' => fake()->numberBetween(0, 20),
            'is_active'  => fake()->boolean(80),
            'expires_at' => fake()->optional(0.7)->dateTimeBetween('now', '+6 months'),
        ];
    }

    /**
     * Indicate that the coupon is expired.
     */
    public function expired(): static
    {
        return $this->state(fn () => [
            'expires_at' => fake()->dateTimeBetween('-3 months', '-1 day'),
            'is_active'  => false,
        ]);
    }
}
