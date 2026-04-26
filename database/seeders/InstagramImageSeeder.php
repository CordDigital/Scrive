<?php

namespace Database\Seeders;

use App\Models\InstagramImage;
use Illuminate\Database\Seeder;

class InstagramImageSeeder extends Seeder
{
    /**
     * Seed Instagram images.
     */
    public function run(): void
    {
        InstagramImage::factory()->count(8)->create();
    }
}
