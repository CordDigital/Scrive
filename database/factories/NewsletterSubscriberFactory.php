<?php

namespace Database\Factories;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsletterSubscriber>
 */
class NewsletterSubscriberFactory extends Factory
{
    protected $model = NewsletterSubscriber::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email'     => fake()->unique()->safeEmail(),
            'is_active' => true,
        ];
    }
}
