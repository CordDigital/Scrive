<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Seed customer testimonials.
     */
    public function run(): void
    {
        Testimonial::factory()->count(8)->create();
    }
}
