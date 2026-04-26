<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Seed contact messages.
     */
    public function run(): void
    {
        ContactMessage::factory()->count(10)->create();
    }
}
