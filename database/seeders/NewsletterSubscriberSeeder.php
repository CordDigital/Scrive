<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Seeder;

class NewsletterSubscriberSeeder extends Seeder
{
    /**
     * Seed newsletter subscribers.
     */
    public function run(): void
    {
        NewsletterSubscriber::factory()->count(20)->create();
    }
}
