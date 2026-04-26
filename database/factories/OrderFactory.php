<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 1000);
        $discount = fake()->boolean(30) ? round($subtotal * fake()->randomFloat(2, 0.05, 0.2), 2) : 0;
        $shipping = fake()->randomElement([0, 5.00, 10.00, 15.00]);
        $total    = round($subtotal - $discount + $shipping, 2);

        return [
            'user_id'        => User::where('role', 'user')->inRandomOrder()->value('id') ?? User::factory(),
            'order_number'   => 'ORD-' . strtoupper(fake()->unique()->bothify('####??##')),
            'first_name'     => fake()->firstName(),
            'last_name'      => fake()->lastName(),
            'email'          => fake()->safeEmail(),
            'phone'          => fake()->phoneNumber(),
            'country'        => fake()->randomElement(['Jordan', 'Palestine', 'UAE', 'Saudi Arabia', 'Kuwait']),
            'city'           => fake()->city(),
            'address'        => fake()->streetAddress(),
            'postal_code'    => fake()->optional(0.7)->postcode(),
            'note'           => fake()->optional(0.3)->sentence(),
            'coupon_code'    => null,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'shipping'       => $shipping,
            'total'          => $total,
            'payment_method' => fake()->randomElement(['cash', 'credit_card', 'paypal']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'status'         => fake()->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
        ];
    }

    /**
     * Indicate that the order is delivered and paid.
     */
    public function completed(): static
    {
        return $this->state(fn () => [
            'status'         => 'delivered',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'status'         => 'pending',
            'payment_status' => 'pending',
        ]);
    }
}
