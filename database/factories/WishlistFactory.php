<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wishlist>
 */
class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'    => User::where('role', 'user')->inRandomOrder()->value('id') ?? User::factory(),
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),
        ];
    }
}
