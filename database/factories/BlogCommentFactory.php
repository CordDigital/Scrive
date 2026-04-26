<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogComment>
 */
class BlogCommentFactory extends Factory
{
    protected $model = BlogComment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'blog_id'     => Blog::inRandomOrder()->value('id') ?? Blog::factory(),
            'name'        => fake()->name(),
            'email'       => fake()->safeEmail(),
            'message'     => fake()->paragraph(),
            'rating'      => fake()->numberBetween(3, 5),
            'is_approved' => fake()->boolean(70),
        ];
    }

    /**
     * Indicate that the comment is approved.
     */
    public function approved(): static
    {
        return $this->state(fn () => [
            'is_approved' => true,
        ]);
    }
}
