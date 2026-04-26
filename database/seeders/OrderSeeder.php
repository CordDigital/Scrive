<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Seed orders with order items linked to real products.
     */
    public function run(): void
    {
        $users    = User::where('role', 'user')->pluck('id')->toArray();
        $products = Product::where('is_active', true)->get();

        if (empty($users) || $products->isEmpty()) {
            return;
        }

        // Create 25 orders
        for ($i = 0; $i < 25; $i++) {
            $itemCount  = fake()->numberBetween(1, 4);
            $orderItems = $products->random(min($itemCount, $products->count()));

            $subtotal = 0;
            $items    = [];

            foreach ($orderItems as $product) {
                $quantity = fake()->numberBetween(1, 3);
                $price    = $product->price;
                $total    = round($price * $quantity, 2);
                $subtotal += $total;

                $items[] = [
                    'product_id'    => $product->id,
                    'product_name'  => $product->name_en,
                    'product_image' => $product->image,
                    'price'         => $price,
                    'quantity'      => $quantity,
                    'size'          => is_array($product->sizes) && count($product->sizes) > 0
                        ? fake()->randomElement($product->sizes)
                        : null,
                    'color'         => is_array($product->colors) && count($product->colors) > 0
                        ? fake()->randomElement($product->colors)
                        : null,
                    'total'         => $total,
                ];
            }

            $discount = fake()->boolean(30) ? round($subtotal * fake()->randomFloat(2, 0.05, 0.2), 2) : 0;
            $shipping = fake()->randomElement([0, 5.00, 10.00]);
            $grandTotal = round($subtotal - $discount + $shipping, 2);

            $order = Order::factory()->create([
                'user_id'  => fake()->randomElement($users),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'total'    => $grandTotal,
            ]);

            foreach ($items as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }
        }
    }
}
