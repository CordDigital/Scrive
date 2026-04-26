<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name'    => fake()->name(),
            'email'   => fake()->safeEmail(),
            'message' => fake()->paragraphs(2, true),
            'is_read' => fake()->boolean(40),
        ];
    }
}
