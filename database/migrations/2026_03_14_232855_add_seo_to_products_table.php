<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('meta_title_ar')->nullable()->after('video_en');
            $table->string('meta_title_en')->nullable()->after('meta_title_ar');
            $table->text('meta_description_ar')->nullable()->after('meta_title_en');
            $table->text('meta_description_en')->nullable()->after('meta_description_ar');
            $table->string('meta_keywords_ar')->nullable()->after('meta_description_en');
            $table->string('meta_keywords_en')->nullable()->after('meta_keywords_ar');
            $table->string('og_image')->nullable()->after('meta_keywords_en');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title_ar', 'meta_title_en',
                'meta_description_ar', 'meta_description_en',
                'meta_keywords_ar', 'meta_keywords_en',
                'og_image',
            ]);
        });
    }
};
