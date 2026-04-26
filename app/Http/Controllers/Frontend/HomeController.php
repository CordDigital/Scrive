<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Blog;
use App\Models\Category;
use App\Models\FlashSale;
use App\Models\InstagramImage;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        // Sliders
        $sliders = Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Benefits
        $benefits = Benefit::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Instagram Images
        $InstagramImages = InstagramImage::where('is_active', true)->get();

        // Testimonials
        $testimonials = Testimonial::active()
            ->orderBy('sort_order')
            ->get();

        // Flash Sale
        $flashSale = FlashSale::where('is_active', true)
            ->where('ends_at', '>', now())
            ->first();

        // Products by category (What's New tabs) - parent categories with children
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)
                  ->orderBy('sort_order'); // ترتيب الأبناء
            }])
            ->orderBy('sort_order') // ترتيب الأب حسب sort_order
            ->take(4)
            ->get();

        $productsByCategory = [];
        foreach ($categories as $cat) {
            $childIds = $cat->children->pluck('id')->toArray();
            $allIds = array_merge([$cat->id], $childIds);
            $productsByCategory[$cat->id] = Product::whereIn('category_id', $allIds)
                ->where('is_active', true)
                ->with('images')
                ->latest()
                ->take(8)
                ->get();
        }

        // Best Sellers
        $bestSellers = Product::where('is_active', true)
            ->with('images')
            ->latest()
            ->take(8)
            ->get();

        // Latest Blog Posts
        $latestBlogs = Blog::where('is_active', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.home.home', compact(
            'sliders',
            'benefits',
            'InstagramImages',
            'categories',
            'productsByCategory',
            'testimonials',
            'flashSale',
            'bestSellers',
            'latestBlogs'
        ));
    }
}
