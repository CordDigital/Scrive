<?php

use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LegalPageController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\ZiziWorldController;
use Illuminate\Support\Facades\Route;


// ============================================================
// Locale-prefixed auth shortcuts (save locale then redirect to real auth pages)
// ============================================================
Route::middleware(['locale', 'guest'])->group(function () {
    Route::get('/en/login',    function () { session(['locale' => 'en']); return view('auth.login'); })->name('en.login');
    Route::get('/en/register', function () { session(['locale' => 'en']); return view('auth.register'); })->name('en.register');
    Route::post('/en/login',    [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::post('/en/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
});
Route::middleware(['locale', 'auth'])->group(function () {
    Route::post('/en/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('en.logout');
});
// Switch Language
// ============================================================
Route::get('/switch-lang/{locale}', function ($locale) {
    if (!in_array($locale, ['ar', 'en'])) {
        $locale = 'ar';
    }
    session(['locale' => $locale]);
    $path = parse_url(url()->previous(), PHP_URL_PATH) ?? '/';
    $path = preg_replace('#^/en#', '', $path) ?: '/';
    return redirect($locale === 'en' ? '/en' . $path : $path);
})->name('switch.lang');

// Legacy redirect
Route::get('/search-result.html', function () {
    $q = request('query') ?? request('q');
    return redirect(app()->getLocale() === 'ar'
        ? route('search-result', ['q' => $q])
        : route('en.search-result', ['q' => $q]));
});

// ============================================================
// Frontend Routes (AR + EN)
// ============================================================
foreach (['', 'en'] as $locale) {
    $prefix  = $locale ? "/$locale" : '';
    $namePre = $locale ? "$locale." : '';

    Route::middleware('locale')->group(function () use ($prefix, $namePre) {

        // Public
        Route::get("$prefix/",        [HomeController::class,     'index'])->name("{$namePre}home");
        Route::get("$prefix/about",   [AboutController::class,    'index'])->name("{$namePre}about");
        Route::get("$prefix/shop",    [ShopController::class,     'index'])->name("{$namePre}shop");
        Route::get("$prefix/shop/{product}", [ShopController::class, 'show'])->name("{$namePre}shop.show");
        Route::get("$prefix/categories", [CategoryController::class, 'index'])->name("{$namePre}categories");
        Route::get("$prefix/search", [ShopController::class, 'search'])->name("{$namePre}search");
        Route::get("$prefix/search-result", [ShopController::class, 'searchResult'])->name("{$namePre}search-result");
        Route::get("$prefix/faqs",    [\App\Http\Controllers\Frontend\FaqController::class,  'index'])->name("{$namePre}faqs");

        // Blog
        Route::get("$prefix/blog",              [BlogController::class, 'index'])->name("{$namePre}blog");
        Route::get("$prefix/blog/{blog}",       [BlogController::class, 'show'])->name("{$namePre}blog.show");
        Route::post("$prefix/blog/{blog}/comment", [BlogController::class, 'storeComment'])->name("{$namePre}blog.comment");

        // Contact
        Route::get("$prefix/contact",       [ContactController::class, 'index'])->name("{$namePre}contact");
        Route::post("$prefix/contact/send", [ContactController::class, 'send'])->name("{$namePre}contact.send");

        // Legal Pages
        Route::get("$prefix/privacy-policy", [LegalPageController::class, 'privacyPolicy'])->name("{$namePre}privacy-policy");
        Route::get("$prefix/return-policy",  [LegalPageController::class, 'returnPolicy'])->name("{$namePre}return-policy");

        // Newsletter
        Route::post("$prefix/newsletter/subscribe", [\App\Http\Controllers\Frontend\NewsletterController::class, 'subscribe'])->name("{$namePre}newsletter.subscribe");

        // Cart
        Route::get("$prefix/cart",                  [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name("{$namePre}cart");
        Route::post("$prefix/cart/add",             [\App\Http\Controllers\Frontend\CartController::class, 'add'])->name("{$namePre}cart.add");
        Route::patch("$prefix/cart/{key}",          [\App\Http\Controllers\Frontend\CartController::class, 'update'])->name("{$namePre}cart.update");
        Route::delete("$prefix/cart/{key}",         [\App\Http\Controllers\Frontend\CartController::class, 'remove'])->name("{$namePre}cart.remove");
        Route::post("$prefix/cart/coupon",          [\App\Http\Controllers\Frontend\CartController::class, 'applyCoupon'])->name("{$namePre}cart.coupon");
        Route::delete("$prefix/cart/coupon/remove", [\App\Http\Controllers\Frontend\CartController::class, 'removeCoupon'])->name("{$namePre}cart.coupon.remove");



Route::get("$prefix/zizi-world", [ZiziWorldController::class, 'index'])
    ->name("{$namePre}zizi-world.index");

Route::get("$prefix/zizi-world/{slug}", [ZiziWorldController::class, 'show'])
    ->name("{$namePre}zizi-world.show"); 
        // Auth-required routes
        Route::middleware('auth')->group(function () use ($prefix, $namePre) {

            // Checkout
            Route::get("$prefix/checkout",              [\App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name("{$namePre}checkout");
            Route::post("$prefix/checkout",             [\App\Http\Controllers\Frontend\CheckoutController::class, 'store'])->name("{$namePre}checkout.store");
            Route::get("$prefix/order/success/{order}", [\App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name("{$namePre}order.success");

            // Payment
            Route::post("$prefix/payment/stripe",        [\App\Http\Controllers\Frontend\PaymentController::class, 'stripeCharge'])->name("{$namePre}payment.stripe");
            Route::get("$prefix/payment/paypal",         [\App\Http\Controllers\Frontend\PaymentController::class, 'paypalRedirect'])->name("{$namePre}payment.paypal");
            Route::get("$prefix/payment/paypal/success", [\App\Http\Controllers\Frontend\PaymentController::class, 'paypalSuccess'])->name("{$namePre}payment.paypal.success");
            Route::get("$prefix/payment/paypal/cancel",  [\App\Http\Controllers\Frontend\PaymentController::class, 'paypalCancel'])->name("{$namePre}payment.paypal.cancel");

            // Wishlist
            Route::post("$prefix/wishlist/{product}/toggle", [\App\Http\Controllers\Frontend\WishlistController::class, 'toggle'])->name("{$namePre}wishlist.toggle");
            Route::get("$prefix/wishlist",                   [\App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name("{$namePre}wishlist");
            Route::delete("$prefix/wishlist/{product}/remove", [\App\Http\Controllers\Frontend\WishlistController::class, 'remove'])
                ->name("$prefix.wishlist.remove");
            // Profile
            Route::get("$prefix/profile", [\App\Http\Controllers\Frontend\ProfileController::class, 'index'])->name("{$namePre}profile");
            Route::put("$prefix/profile", [\App\Http\Controllers\Frontend\ProfileController::class, 'update'])->name("{$namePre}profile.update");

            // My Orders
            Route::get("$prefix/my-orders",             [\App\Http\Controllers\Frontend\ProfileController::class, 'orders'])->name("{$namePre}my.orders");
            Route::get("$prefix/my-orders/{order}",     [\App\Http\Controllers\Frontend\ProfileController::class, 'orderShow'])->name("{$namePre}my.orders.show");
        });
    });
}

// ============================================================
// Admin Routes
// ============================================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Home Page
        Route::resource('sliders',  \App\Http\Controllers\Admin\SliderController::class);
        Route::resource('benefits', \App\Http\Controllers\Admin\BenefitController::class);


        Route::get('wishlists', [\App\Http\Controllers\Admin\WishlistController::class, 'index'])->name('wishlists.index');
        Route::delete('wishlists/{wishlist}', [\App\Http\Controllers\Admin\WishlistController::class, 'destroy'])->name('wishlists.destroy');

        // About
        Route::get('/about', [\App\Http\Controllers\Admin\AboutPageController::class, 'edit'])->name('about.edit');
        Route::put('/about', [\App\Http\Controllers\Admin\AboutPageController::class, 'update'])->name('about.update');

        // Instagram
        Route::resource('instagram', \App\Http\Controllers\Admin\InstagramImageController::class);

        // Contact
        Route::get('/contact',             [\App\Http\Controllers\Admin\ContactPageController::class,    'edit'])->name('contact.edit');
        Route::put('/contact',             [\App\Http\Controllers\Admin\ContactPageController::class,    'update'])->name('contact.update');
        Route::get('/messages',            [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('contact.messages');
        Route::get('/messages/{message}',  [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('contact.show');
        Route::delete('/messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('contact.destroy');

        // Social
        Route::resource('social', \App\Http\Controllers\Admin\SocialLinkController::class);

        // Blog
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);

        // Comments
        Route::get('/comments',                     [\App\Http\Controllers\Admin\BlogCommentController::class, 'index'])->name('comments.index');
        Route::patch('/comments/{comment}/approve', [\App\Http\Controllers\Admin\BlogCommentController::class, 'approve'])->name('comments.approve');
        Route::delete('/comments/{comment}',        [\App\Http\Controllers\Admin\BlogCommentController::class, 'destroy'])->name('comments.destroy');

        // Shop
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::get('/products/trashed', [\App\Http\Controllers\Admin\ProductController::class, 'trashed'])->name('products.trashed');
        Route::post('/products/{id}/restore', [\App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/products/{id}/force-delete', [\App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::resource('products',   \App\Http\Controllers\Admin\ProductController::class);
        Route::delete('/product-images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('product-images.destroy');

        // FAQs
        Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);

        // Newsletter
        Route::get('/newsletter',                [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
        Route::get('/newsletter/export',         [\App\Http\Controllers\Admin\NewsletterController::class, 'export'])->name('newsletter.export');
        Route::patch('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'toggle'])->name('newsletter.toggle');
        Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletter.destroy');

        // Coupons
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);

        // Orders
        Route::get('/orders',            [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}',    [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}',  [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update');
        Route::delete('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');

        // Users
        Route::get('/users',              [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',       [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users',             [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',  [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',       [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',    [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Testimonials
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);

        // Flash Sale
        Route::get('/flash-sale', [\App\Http\Controllers\Admin\FlashSaleController::class, 'edit'])->name('flash-sale.edit');
        Route::put('/flash-sale', [\App\Http\Controllers\Admin\FlashSaleController::class, 'update'])->name('flash-sale.update');

        // Site Settings
        Route::get('/site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'edit'])->name('site-settings.edit');
        Route::put('/site-settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('site-settings.update');

        // Announcement Bar
        Route::get('/announcement', [\App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcement.edit');
        Route::put('/announcement', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcement.update');

        // Privacy Policy
        Route::get('/privacy-policy', [\App\Http\Controllers\Admin\PrivacyPolicyController::class, 'edit'])->name('privacy-policy.edit');
        Route::put('/privacy-policy', [\App\Http\Controllers\Admin\PrivacyPolicyController::class, 'update'])->name('privacy-policy.update');

        // Return Policy
        Route::get('/return-policy', [\App\Http\Controllers\Admin\ReturnPolicyController::class, 'edit'])->name('return-policy.edit');
        Route::put('/return-policy', [\App\Http\Controllers\Admin\ReturnPolicyController::class, 'update'])->name('return-policy.update');



       Route::resource('stores', StoreController::class);

    Route::get('stores/{store}/images', [StoreController::class, 'imagesIndex']);
    Route::post('stores/{store}/images', [StoreController::class, 'imagesUpload']);
    Route::delete('stores/images/{image}', [StoreController::class, 'imageDestroy']);
    });

// ============================================================
// Auth (Laravel Breeze/Fortify)
// ============================================================
require __DIR__ . '/auth.php';
