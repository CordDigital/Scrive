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
        Schema::create('contact_page', function (Blueprint $table) {
            $table->id();
            $table->text('address_ar');
            $table->text('address_en');
            $table->string('phone');
            $table->string('email');
            $table->string('map_url');
            // Open Hours
            $table->string('mon_fri_ar')->default('');
            $table->string('mon_fri_en')->default('');
            $table->string('saturday_ar')->default('');
            $table->string('saturday_en')->default('');
            $table->string('sunday_ar')->default('');
            $table->string('sunday_en')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_page');
    }
};
