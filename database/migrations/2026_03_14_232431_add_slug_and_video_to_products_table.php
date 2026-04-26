<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name_en');
            $table->string('video_ar')->nullable()->after('description_en');
            $table->string('video_en')->nullable()->after('video_ar');
        });

        // Generate slugs for existing products
        $products = DB::table('products')->orderBy('id')->get();
        foreach ($products as $product) {
            $base  = Str::slug($product->name_en ?: $product->name_ar);
            $slug  = $base;
            $count = 1;
            while (DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $base . '-' . $count++;
            }
            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'video_ar', 'video_en']);
        });
    }
};
