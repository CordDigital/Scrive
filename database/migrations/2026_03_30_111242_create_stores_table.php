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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();

            $table->string('name_ar');
            $table->string('name_en');

            // Slugs
            $table->string('slug_ar')->unique();
            $table->string('slug_en')->unique();

            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            $table->string('cover_image')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });

        Schema::create('store_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_images');
        Schema::dropIfExists('stores');
    }
};
