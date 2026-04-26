<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\Benefit;
use App\Models\InstagramImage;

class AboutController extends Controller
{
    public function index()
    {
        $AboutPage = AboutPage::first();
          $benefits = Benefit::active()->get();
          $InstagramImages = InstagramImage::active()->get();
        return view('frontend.about.about', compact('AboutPage', 'benefits', 'InstagramImages'));
    }
}