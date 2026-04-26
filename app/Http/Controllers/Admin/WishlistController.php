<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
  public function index()
{
    $wishlists = Wishlist::with('user','product')->latest()->paginate(20);
    return view('admin.wishlists.index', compact('wishlists'));
}
    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->back()->with('success', 'Item removed from wishlist successfully.');
    }
}
