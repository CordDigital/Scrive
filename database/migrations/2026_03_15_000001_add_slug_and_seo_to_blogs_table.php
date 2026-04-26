<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('slug_ar')->nullable()->after('title_en');
            $table->string('slug_en')->nullable()->after('slug_ar');
            $table->string('meta_title_ar')->nullable()->after('content_en');
            $table->string('meta_title_en')->nullable()->after('meta_title_ar');
            $table->text('meta_description_ar')->nullable()->after('meta_title_en');
            $table->text('meta_description_en')->nullable()->after('meta_description_ar');
            $table->string('meta_keywords_ar')->nullable()->after('meta_description_en');
            $table->string('meta_keywords_en')->nullable()->after('meta_keywords_ar');
            $table->string('og_image')->nullable()->after('meta_keywords_en');
        });

        // Generate slugs for existing blogs
        $usedEn = [];
        $usedAr = [];

        DB::table('blogs')->orderBy('id')->each(function ($blog) use (&$usedEn, &$usedAr) {
            // English slug
            $baseEn = Str::slug($blog->title_en ?: $blog->title_ar) ?: 'post';
            $slugEn = $baseEn;
            $c = 1;
            while (in_array($slugEn, $usedEn)) { $slugEn = $baseEn . '-' . $c++; }
            $usedEn[] = $slugEn;

            // Arabic slug
            $baseAr = preg_replace('/[^\p{Arabic}\p{Latin}\d]+/u', '-', trim($blog->title_ar ?: $blog->title_en));
            $baseAr = mb_strtolower(trim($baseAr, '-')) ?: 'post';
            $slugAr = $baseAr;
            $c = 1;
            while (in_array($slugAr, $usedAr)) { $slugAr = $baseAr . '-' . $c++; }
            $usedAr[] = $slugAr;

            DB::table('blogs')->where('id', $blog->id)->update([
                'slug_en' => $slugEn,
                'slug_ar' => $slugAr,
            ]);
        });

        // Now add unique constraints
        Schema::table('blogs', function (Blueprint $table) {
            $table->unique('slug_en');
            $table->unique('slug_ar');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'slug_ar', 'slug_en',
                'meta_title_ar', 'meta_title_en',
                'meta_description_ar', 'meta_description_en',
                'meta_keywords_ar', 'meta_keywords_en',
                'og_image',
            ]);
        });
    }
};
