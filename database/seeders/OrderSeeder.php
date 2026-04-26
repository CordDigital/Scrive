<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users    = User::where('role', 'user')->get();

        $firstNames = ['Ahmed', 'Mohamed', 'Sara', 'Nour', 'Youssef', 'Laila', 'Khaled', 'Rana', 'Omar', 'Hana'];
        $lastNames  = ['Hassan', 'Ali', 'Ibrahim', 'Mostafa', 'Mahmoud', 'Saleh', 'Farouk', 'Nasser', 'Kamal', 'Zaki'];
        $cities     = ['Cairo', 'Alexandria', 'Giza', 'Mansoura', 'Tanta', 'Luxor', 'Aswan', 'Suez', 'Ismailia', 'Hurghada'];
        $streets    = ['Nile St', 'Tahrir Sq', 'Pyramids Rd', 'Hassan St', 'El Nasr Ave'];
        $methods    = ['cash', 'credit_card', 'paypal'];

        for ($i = 1; $i <= 15; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName  = $lastNames[array_rand($lastNames)];
            $method    = $methods[array_rand($methods)];
            $user      = $users->isNotEmpty() ? $users->random() : null;

            // Pick 1–3 random products
            $pickedProducts = $products->isNotEmpty()
                ? $products->random(min(rand(1, 3), $products->count()))
                : collect();

            $subtotal = 0;
            $items    = [];

            foreach ($pickedProducts as $product) {
                $qty   = rand(1, 4);
                $price = (float) $product->price;
                $line  = round($price * $qty, 2);
                $subtotal += $line;

                $items[] = [
                    'product_id'    => $product->id,
                    'product_name'  => $product->name_en,
                    'product_image' => $product->image,
                    'price'         => $price,
                    'quantity'      => $qty,
                    'size'          => null,
                    'color'         => null,
                    'total'         => $line,
                ];
            }

            $shipping = rand(0, 1) ? 10.00 : 0.00;
            $total    = round($subtotal + $shipping, 2);

            // Spread orders over the last 90 days
            $createdAt = Carbon::now()->subDays(rand(0, 90))->subHours(rand(0, 23));

            $order = Order::create([
                'user_id'        => $user?->id,
                'order_number'   => 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'first_name'     => $firstName,
                'last_name'      => $lastName,
                'email'          => strtolower($firstName . '.' . $lastName . rand(1, 99) . '@example.com'),
                'phone'          => '01' . rand(0, 2) . rand(10000000, 99999999),
                'country'        => 'Egypt',
                'city'           => $cities[array_rand($cities)],
                'address'        => rand(1, 200) . ' ' . $streets[array_rand($streets)],
                'postal_code'    => (string) rand(10000, 99999),
                'note'           => null,
                'coupon_code'    => null,
                'subtotal'       => $subtotal,
                'discount'       => 0,
                'shipping'       => $shipping,
                'total'          => $total,
                'payment_method' => $method,
                'payment_status' => 'paid',
                'status'         => 'delivered',
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);

            foreach ($items as $item) {
                $item['order_id']   = $order->id;
                $item['created_at'] = $createdAt;
                $item['updated_at'] = $createdAt;
                OrderItem::create($item);
            }
        }

        $this->command->info('✅ 15 completed orders seeded successfully.');
    }
}
