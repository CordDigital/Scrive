<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')
                  ->constrained('categories')->nullOnDelete();
        });

        // Try to match existing category_ar/en text to categories table
        DB::table('blogs')->get()->each(function ($blog) {
            $category = DB::table('categories')
                ->where('name_ar', $blog->category_ar)
                ->orWhere('name_en', $blog->category_en)
                ->first();
            if ($category) {
                DB::table('blogs')->where('id', $blog->id)
                    ->update(['category_id' => $category->id]);
            }
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['category_ar', 'category_en']);
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('category_ar')->nullable()->after('slug_en');
            $table->string('category_en')->nullable()->after('category_ar');
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
