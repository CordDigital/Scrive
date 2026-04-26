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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('og_site_name')->nullable()->after('og_image');
            $table->string('og_type')->nullable()->after('og_site_name');
            $table->string('twitter_card')->nullable()->after('og_type');
            $table->string('twitter_handle')->nullable()->after('twitter_card');
            $table->string('google_analytics')->nullable()->after('twitter_handle');
            $table->string('google_verification')->nullable()->after('google_analytics');
            $table->string('facebook_pixel')->nullable()->after('google_verification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn([
                'og_site_name', 'og_type', 'twitter_card', 'twitter_handle',
                'google_analytics', 'google_verification', 'facebook_pixel',
            ]);
        });
    }
};
