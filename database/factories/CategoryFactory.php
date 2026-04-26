<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $categories = [
            ['ar' => 'فساتين', 'en' => 'Dresses'],
            ['ar' => 'بلوزات', 'en' => 'Blouses'],
            ['ar' => 'تنانير', 'en' => 'Skirts'],
            ['ar' => 'بناطيل', 'en' => 'Pants'],
            ['ar' => 'عبايات', 'en' => 'Abayas'],
            ['ar' => 'اكسسوارات', 'en' => 'Accessories'],
            ['ar' => 'حقائب', 'en' => 'Bags'],
            ['ar' => 'أحذية', 'en' => 'Shoes'],
        ];

        $cat = fake()->randomElement($categories);

        return [
            'name_ar'    => $cat['ar'],
            'name_en'    => $cat['en'],
            'image'      => null,
            'is_active'  => true,
            'sort_order' => fake()->numberBetween(0, 10),
            'parent_id'  => null,
        ];
    }

    /**
     * Indicate this category is a child of another.
     */
    public function child(int $parentId): static
    {
        return $this->state(fn () => [
            'parent_id' => $parentId,
        ]);
    }

    /**
     * Indicate this category is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
