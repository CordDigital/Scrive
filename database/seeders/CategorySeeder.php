<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed categories with realistic bilingual data.
     */
    public function run(): void
    {
        $parents = [
            ['name_ar' => 'ملابس نسائية',  'name_en' => 'Women\'s Clothing', 'sort_order' => 1],
            ['name_ar' => 'اكسسوارات',     'name_en' => 'Accessories',       'sort_order' => 2],
            ['name_ar' => 'أحذية وحقائب',  'name_en' => 'Shoes & Bags',      'sort_order' => 3],
            ['name_ar' => 'عبايات',        'name_en' => 'Abayas',            'sort_order' => 4],
        ];

        foreach ($parents as $parent) {
            Category::create(array_merge($parent, ['is_active' => true]));
        }

        $clothing = Category::where('name_en', 'Women\'s Clothing')->first();
        $accessories = Category::where('name_en', 'Accessories')->first();
        $shoesAndBags = Category::where('name_en', 'Shoes & Bags')->first();

        $children = [
            // Children of Women's Clothing
            ['parent_id' => $clothing->id, 'name_ar' => 'فساتين',     'name_en' => 'Dresses',    'sort_order' => 1],
            ['parent_id' => $clothing->id, 'name_ar' => 'بلوزات',     'name_en' => 'Blouses',    'sort_order' => 2],
            ['parent_id' => $clothing->id, 'name_ar' => 'تنانير',     'name_en' => 'Skirts',     'sort_order' => 3],
            ['parent_id' => $clothing->id, 'name_ar' => 'بناطيل',     'name_en' => 'Pants',      'sort_order' => 4],
            ['parent_id' => $clothing->id, 'name_ar' => 'جاكيتات',    'name_en' => 'Jackets',    'sort_order' => 5],

            // Children of Accessories
            ['parent_id' => $accessories->id, 'name_ar' => 'أوشحة',   'name_en' => 'Scarves',    'sort_order' => 1],
            ['parent_id' => $accessories->id, 'name_ar' => 'مجوهرات', 'name_en' => 'Jewelry',    'sort_order' => 2],
            ['parent_id' => $accessories->id, 'name_ar' => 'نظارات',  'name_en' => 'Sunglasses', 'sort_order' => 3],

            // Children of Shoes & Bags
            ['parent_id' => $shoesAndBags->id, 'name_ar' => 'حقائب يد',  'name_en' => 'Handbags',   'sort_order' => 1],
            ['parent_id' => $shoesAndBags->id, 'name_ar' => 'أحذية كعب', 'name_en' => 'Heels',      'sort_order' => 2],
            ['parent_id' => $shoesAndBags->id, 'name_ar' => 'أحذية فلات','name_en' => 'Flats',      'sort_order' => 3],
        ];

        foreach ($children as $child) {
            Category::create(array_merge($child, ['is_active' => true]));
        }
    }
}
