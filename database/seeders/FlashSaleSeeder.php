<?php

namespace Database\Seeders;

use App\Models\FlashSale;
use Illuminate\Database\Seeder;

class FlashSaleSeeder extends Seeder
{
    /**
     * Seed flash sales.
     */
    public function run(): void
    {
        FlashSale::factory()->count(3)->create();
    }
}
