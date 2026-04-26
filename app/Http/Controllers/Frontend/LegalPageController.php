<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class LegalPageController extends Controller
{
    public function privacyPolicy()
    {
        $page = Page::where('slug', 'privacy-policy')->first();
        return view('frontend.pages.privacy-policy', compact('page'));
    }

    public function returnPolicy()
    {
        $page = Page::where('slug', 'return-policy')->first();
        return view('frontend.pages.return-policy', compact('page'));
    }
}
