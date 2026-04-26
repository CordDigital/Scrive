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
        Schema::create('about_page', function (Blueprint $table) {
            $table->id();
            $table->text('description_ar');
            $table->text('description_en');
            $table->string('image_1');
            $table->string('image_2');
            $table->string('image_3');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_page');
    }
};
