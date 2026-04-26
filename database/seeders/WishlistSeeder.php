<?php

namespace Database\Seeders;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    /**
     * Seed wishlists (unique user-product pairs).
     */
    public function run(): void
    {
        $users    = User::where('role', 'user')->pluck('id')->toArray();
        $products = Product::where('is_active', true)->pluck('id')->toArray();

        if (empty($users) || empty($products)) {
            return;
        }

        $created = [];

        for ($i = 0; $i < 30; $i++) {
            $userId    = fake()->randomElement($users);
            $productId = fake()->randomElement($products);
            $key       = "{$userId}-{$productId}";

            // Ensure unique user-product pair
            if (in_array($key, $created)) {
                continue;
            }

            Wishlist::create([
                'user_id'    => $userId,
                'product_id' => $productId,
            ]);

            $created[] = $key;
        }
    }
}
