<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\FooterComposer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            View::share('siteSettings', \App\Models\SiteSetting::current());
        } catch (\Exception $e) {
            View::share('siteSettings', new \App\Models\SiteSetting());
        }
        View::composer('frontend.layouts.footer',  FooterComposer::class);
        View::composer('frontend.layouts.top-nav', function ($view) {
            $view->with('topNavSocials', \App\Models\SocialLink::active()->get());
            $view->with('announcement', \App\Models\Announcement::active()->first());
        });
    }
}
