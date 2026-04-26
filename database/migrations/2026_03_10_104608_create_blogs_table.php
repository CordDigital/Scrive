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
    Schema::create('blogs', function (Blueprint $table) {
        $table->id();
        $table->string('title_ar');
        $table->string('title_en');
        $table->string('category_ar');
        $table->string('category_en');
        $table->string('author');
        $table->text('content_ar');
        $table->text('content_en');
        $table->string('image');
        $table->boolean('is_active')->default(true);
        $table->integer('sort_order')->default(0);
        $table->timestamp('published_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
