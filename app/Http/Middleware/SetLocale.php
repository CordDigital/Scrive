<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SetLocale
{
  public function handle(Request $request, Closure $next)
{
    $locale = $request->route('locale'); // en من الـ URL

    // لو جاية من URL اكتبها في السيشن
    if ($locale === 'en' || $locale === 'ar') {
        session(['locale' => $locale]);
    } elseif (session()->has('locale')) {
        // لو مفيش prefix في الـ URL خد من السيشن (صفحات login/register)
        $locale = session('locale');
    }

    if ($locale === 'en') {
        App::setLocale('en');
    } else {
        App::setLocale('ar');
    }

    URL::defaults(['locale' => $locale === 'en' ? 'en' : null]);

    return $next($request);
}
}
