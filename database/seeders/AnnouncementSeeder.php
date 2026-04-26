<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Seed announcements.
     */
    public function run(): void
    {
        Announcement::factory()->count(3)->create();
    }
}
