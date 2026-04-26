<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::active()
            ->parents()
            ->with([
                'children' => function ($q) {
                    $q->active()
                      ->withCount([
                          'products' => function ($q) {
                              $q->where('is_active', true);
                          }
                      ]);
                }
            ])
            ->withCount([
                'products' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->get();

        return view('frontend.categories.categories', compact('categories'));
    }
}
