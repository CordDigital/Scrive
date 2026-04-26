<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Seed homepage sliders.
     */
    public function run(): void
    {
        Slider::factory()->count(4)->create();
    }
}
