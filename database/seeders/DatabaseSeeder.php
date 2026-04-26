<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * Seeders are ordered by dependency:
     * 1. Independent tables (no foreign keys)
     * 2. Tables depending on tier-1 (Category → Product, Blog)
     * 3. Tables depending on tier-2 (Order → OrderItem, Wishlist)
     */
    public function run(): void
    {
        $this->call([
            // ── Tier 0: Independent / Config tables ─────────────────────
            UserSeeder::class,
            CategorySeeder::class,
            SiteSettingSeeder::class,
            AboutPageSeeder::class,
            ContactPageSeeder::class,
            PageSeeder::class,
            SocialLinkSeeder::class,
            AnnouncementSeeder::class,
            BenefitSeeder::class,
            SliderSeeder::class,
            FaqSeeder::class,
            CouponSeeder::class,
            FlashSaleSeeder::class,
            InstagramImageSeeder::class,
            NewsletterSubscriberSeeder::class,
            TestimonialSeeder::class,

            // ── Tier 1: Depends on User / Category ──────────────────────
            StoreSeeder::class,          // Store + StoreImages
            ProductSeeder::class,        // Product + ProductImages (needs Category)
            BlogSeeder::class,           // Blog + BlogComments   (needs Category)
            ContactMessageSeeder::class,

            // ── Tier 2: Depends on User + Product ───────────────────────
            OrderSeeder::class,          // Order + OrderItems (needs User + Product)
            WishlistSeeder::class,       // Wishlist (needs User + Product)
        ]);
    }
}
