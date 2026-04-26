<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Toggle wishlist (Add / Remove)
    public function toggle(Product $product)
    {
        $user = Auth::user();

        // نحاول نحذف أولاً، إذا موجود يحذف
        $deleted = Wishlist::where('user_id', $user->id)
                           ->where('product_id', $product->id)
                           ->delete();

        if($deleted) {
            $wishlisted = false;
        } else {
            // إذا مش موجود نضيفه
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $wishlisted = true;
        }

        return response()->json([
            'success'    => true,
            'wishlisted' => $wishlisted,
            'count'      => $user->wishlist()->count(),
            'message'    => $wishlisted
                ? (app()->getLocale() === 'ar' ? 'تمت الإضافة للمفضلة' : 'Added to wishlist')
                : (app()->getLocale() === 'ar' ? 'تمت الإزالة من المفضلة' : 'Removed from wishlist'),
        ]);
    }

    // صفحة المفضلة
    public function index()
    {
        $items = Auth::user()
                     ->wishlist()
                     ->with('product.category')
                     ->latest()
                     ->get();

        return view('frontend.wishlist.index', compact('items'));
    }

    // Optional: حذف عنصر محدد مباشرة
    public function remove(Product $product)
    {
        $user = Auth::user();

        Wishlist::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();

        return response()->json([
            'message' => app()->getLocale() === 'ar' ? 'تمت الإزالة من المفضلة' : 'Removed from wishlist',
            'count'   => $user->wishlist()->count()
        ]);
    }
}
