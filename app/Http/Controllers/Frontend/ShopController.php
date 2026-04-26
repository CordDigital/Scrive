<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()
            ->whereNull('parent_id')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->withCount('products')->orderBy('sort_order');
            }])
            ->withCount('products')
            ->get();

        $query = Product::where('is_active', true)->with('category');

        // Search by name
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('name_ar', 'like', "%{$q}%")
                   ->orWhere('name_en', 'like', "%{$q}%");
            });

            // AJAX live search
            if ($request->ajax() || $request->wantsJson()) {
                $results = (clone $query)->take(8)->get()->map(function ($p) {
                    $locale = app()->getLocale();
                    return [
                        'name'      => $locale === 'ar' ? $p->name_ar : $p->name_en,
                        'price'     => number_format($p->price, 2),
                        'old_price' => $p->old_price ? number_format($p->old_price, 2) : null,
                        'image'     => asset('storage/' . $p->image),
                        'url'       => route($locale === 'ar' ? 'shop.show' : 'en.shop.show', $p),
                    ];
                });
                return response()->json($results);
            }
        }

        // Filter by category (parent or child)
        if ($request->filled('category')) {
            $catName = $request->category;
            // Find matching category
            $filterCat = \App\Models\Category::where('name_en', $catName)->orWhere('name_ar', $catName)->first();
            if ($filterCat) {
                if ($filterCat->parent_id === null) {
                    // Parent: get products from parent + all children
                    $childIds = $filterCat->children()->pluck('id')->toArray();
                    $allIds = array_merge([$filterCat->id], $childIds);
                    $query->whereIn('category_id', $allIds);
                } else {
                    // Child: get products from this child only
                    $query->where('category_id', $filterCat->id);
                }
            }
        }

        // Filter by size
        if ($request->filled('size')) {
            $query->whereJsonContains('sizes', $request->size);
        }

        // Filter by price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter sale only
        if ($request->filled('sale')) {
            $query->whereNotNull('old_price');
        }

        // Sort
        match ($request->sort) {
            'newArrivals'       => $query->orderBy('created_at', 'desc'),
            'bestSelling'       => $query->where('is_featured', true)->orderBy('sort_order'),
            'priceHighToLow'    => $query->orderBy('price', 'desc'),
            'priceLowToHigh'    => $query->orderBy('price', 'asc'),
            'discountHighToLow' => $query->orderByRaw('(old_price - price) DESC'),
            default             => $query->orderBy('sort_order')->orderBy('id'),
        };

        $products = $query->paginate(12)->withQueryString();

        $brands = Product::where('is_active', true)
                         ->whereNotNull('brand')
                         ->where('brand', '!=', '')
                         ->orderBy('brand')
                         ->pluck('brand')
                         ->unique()
                         ->values();

        return view('frontend.shop.shop', compact('products', 'categories', 'brands'));
    }

    public function searchResult(Request $request)
    {
        $q = trim($request->get('q', ''));

        $products = collect();
        if (mb_strlen($q) >= 2) {
            $products = Product::where('is_active', true)
                ->where(function ($qb) use ($q) {
                    $qb->where('name_ar', 'like', "%{$q}%")
                       ->orWhere('name_en', 'like', "%{$q}%");
                })
                ->orderBy('sort_order')
                ->paginate(12)
                ->withQueryString();
        }

        return view('frontend.shop.search-result', compact('products', 'q'));
    }

    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if ($request->ajax() || $request->wantsJson()) {
            if (mb_strlen($q) < 2) {
                return response()->json([]);
            }

            $products = Product::where('is_active', true)
                ->where(function ($query) use ($q) {
                    $query->where('name_ar', 'like', "%{$q}%")
                          ->orWhere('name_en', 'like', "%{$q}%");
                })
                ->take(8)
                ->get()
                ->map(function ($p) {
                    $locale = app()->getLocale();
                    return [
                        'name'  => $locale === 'ar' ? $p->name_ar : $p->name_en,
                        'price' => number_format($p->price, 2),
                        'old_price' => $p->old_price ? number_format($p->old_price, 2) : null,
                        'image' => asset('storage/' . $p->image),
                        'url'   => route($locale === 'ar' ? 'shop.show' : 'en.shop.show', $p),
                    ];
                });

            return response()->json($products);
        }

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name_ar', 'like', "%{$q}%")
                      ->orWhere('name_en', 'like', "%{$q}%");
            })
            ->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::active()
            ->whereNull('parent_id')
            ->with(['children' => function ($q2) {
                $q2->where('is_active', true)->withCount('products')->orderBy('sort_order');
            }])
            ->withCount('products')
            ->get();
        $brands = Product::where('is_active', true)
                         ->whereNotNull('brand')
                         ->where('brand', '!=', '')
                         ->orderBy('brand')
                         ->pluck('brand')
                         ->unique()
                         ->values();

        return view('frontend.shop.shop', compact('products', 'categories', 'brands', 'q'));
    }

    public function show(Product $product)
    {
        $product->load('category', 'images');

        $related = Product::where('is_active', true)
                          ->where('category_id', $product->category_id)
                          ->where('id', '!=', $product->id)
                          ->orderBy('sort_order')
                          ->take(4)
                          ->get();

        return view('frontend.shop.product-detail', compact('product', 'related'));
    }
}
