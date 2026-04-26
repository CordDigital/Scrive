<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class ZiziWorldController extends Controller
{

    public function index()
    {

        $stores = Store::orderBy('sort_order', 'asc')->get();

        return view('frontend.zizi_world.index', compact('stores'));
    }


  public function show($slug)
    {
        // البحث عن المتجر بالسلوج
        $store = Store::where('slug_ar', $slug)
                      ->orWhere('slug_en', $slug)
                      ->firstOrFail();

        // تحميل الصور الفرعية
        $store->load('images');

        // جلب باقي المتاجر لعرضها في السلايدر السفلي (باستثناء الحالي)
        $otherStores = Store::where('id', '!=', $store->id)
                            ->orderBy('sort_order', 'asc')
                            ->limit(10)
                            ->get();

        return view('frontend.zizi_world.show', compact('store', 'otherStores'));
    }
}
