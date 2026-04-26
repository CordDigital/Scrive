<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Seed blogs and their comments.
     */
    public function run(): void
    {
        Blog::factory()
            ->count(12)
            ->create()
            ->each(function (Blog $blog) {
                // Create 2-5 comments for each blog
                BlogComment::factory()
                    ->count(fake()->numberBetween(2, 5))
                    ->create(['blog_id' => $blog->id]);
            });
    }
}
