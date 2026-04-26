<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $product  = Product::inRandomOrder()->first();
        $price    = $product?->price ?? fake()->randomFloat(2, 20, 300);
        $quantity = fake()->numberBetween(1, 4);

        return [
            'order_id'      => Order::inRandomOrder()->value('id') ?? Order::factory(),
            'product_id'    => $product?->id,
            'product_name'  => $product?->name_en ?? fake()->words(3, true),
            'product_image' => $product?->image ?? 'products/placeholder.jpg',
            'price'         => $price,
            'quantity'      => $quantity,
            'size'          => fake()->optional(0.7)->randomElement(['S', 'M', 'L', 'XL']),
            'color'         => fake()->optional(0.7)->randomElement(['Red', 'Blue', 'Black', 'White']),
            'total'         => round($price * $quantity, 2),
        ];
    }
}
