<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add slug_en (copy from slug) and slug_ar
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug_en')->nullable()->after('slug');
            $table->string('slug_ar')->nullable()->after('slug_en');
        });

        // 2. Copy existing slug → slug_en, generate slug_ar from name_ar
        $products = DB::table('products')->get(['id', 'slug', 'name_ar']);
        foreach ($products as $product) {
            $slugAr = self::makeSlugAr($product->name_ar);
            // Ensure slug_ar uniqueness
            $base  = $slugAr;
            $count = 1;
            while (DB::table('products')->where('slug_ar', $slugAr)->where('id', '!=', $product->id)->exists()) {
                $slugAr = $base . '-' . $count++;
            }
            DB::table('products')->where('id', $product->id)->update([
                'slug_en' => $product->slug,
                'slug_ar' => $slugAr,
            ]);
        }

        // 3. Add unique indexes then drop old slug
        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug_en');
            $table->unique('slug_ar');
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name_en');
        });

        DB::table('products')->get(['id', 'slug_en'])->each(function ($p) {
            DB::table('products')->where('id', $p->id)->update(['slug' => $p->slug_en]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
            $table->dropColumn(['slug_en', 'slug_ar']);
        });
    }

    private static function makeSlugAr(string $name): string
    {
        // Keep Arabic + Latin + digits, replace everything else with hyphen
        $slug = preg_replace('/[^\p{Arabic}\p{Latin}\d]+/u', '-', trim($name));
        return mb_strtolower(trim($slug, '-'));
    }
};
