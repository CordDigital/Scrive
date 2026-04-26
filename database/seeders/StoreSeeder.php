<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Seed stores and their images.
     */
    public function run(): void
    {
        Store::factory()
            ->count(4)
            ->create()
            ->each(function (Store $store) {
                StoreImage::factory()
                    ->count(fake()->numberBetween(3, 6))
                    ->create(['store_id' => $store->id]);
            });
    }
}
