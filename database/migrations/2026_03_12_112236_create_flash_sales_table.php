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
    Schema::create('flash_sales', function (Blueprint $table) {
        $table->id();
        $table->string('title_ar');
        $table->string('title_en');
        $table->string('subtitle_ar')->nullable();
        $table->string('subtitle_en')->nullable();
        $table->decimal('discount_percent', 5, 2)->default(20);
        $table->decimal('min_amount', 10, 2)->default(120);
        $table->timestamp('ends_at');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
