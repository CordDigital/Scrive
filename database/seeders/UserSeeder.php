<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed users: 1 admin + 15 regular users.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name'  => 'Admin',
            'email' => 'admin@admin.com',
        ]);

        // Create regular users
        User::factory()->count(15)->create();
    }
}
