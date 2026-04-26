<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed products using the ProductFactory.
     */
    public function run(): void
    {
        // Get all child categories (categories that have a parent_id)
        $childCategories = Category::whereNotNull('parent_id')->pluck('id')->toArray();

        // If no child categories, use all categories
        if (empty($childCategories)) {
            $childCategories = Category::pluck('id')->toArray();
        }

        // Create 30 products distributed across categories
        foreach ($childCategories as $categoryId) {
            Product::factory()
                ->count(fake()->numberBetween(2, 5))
                ->create(['category_id' => $categoryId])
                ->each(function (Product $product) {
                    // Create 2-4 product images for each product
                    ProductImage::factory()
                        ->count(fake()->numberBetween(2, 4))
                        ->create(['product_id' => $product->id]);
                });
        }

        // Create some featured products
        Product::factory()
            ->count(5)
            ->featured()
            ->create([
                'category_id' => fake()->randomElement($childCategories),
            ]);
    }
}
