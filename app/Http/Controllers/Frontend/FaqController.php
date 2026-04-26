<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        // جلب الـ FAQs مجمعة حسب الـ category_en
        $faqs = Faq::active()->get()->groupBy('category_en');

        return view('frontend.faqs.index', compact('faqs'));
    }
}
