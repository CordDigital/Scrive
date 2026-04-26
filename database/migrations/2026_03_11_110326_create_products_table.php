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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->string('name_ar');
        $table->string('name_en');
        $table->text('description_ar')->nullable();
        $table->text('description_en')->nullable();
        $table->decimal('price', 10, 2);
        $table->decimal('old_price', 10, 2)->nullable();
        $table->string('image');
        $table->json('gallery')->nullable();
        $table->json('sizes')->nullable();
        $table->json('colors')->nullable();
        $table->string('brand')->nullable();
        $table->integer('stock')->default(0);
        $table->boolean('is_active')->default(true);
        $table->boolean('is_featured')->default(false);
        $table->integer('sort_order')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
