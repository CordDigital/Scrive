<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cart) {}

    // صفحة السلة
    public function index()
    {
        $items    = $this->cart->all();
        $subtotal = $this->cart->subtotal();
        $coupon   = session('coupon');
        $discount = $coupon ? $coupon['discount'] : 0;
        $total    = max(0, $subtotal - $discount);

        return view('frontend.cart.index', compact('items', 'subtotal', 'coupon', 'discount', 'total'));
    }

    // إضافة منتج للسلة
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
            'size'       => 'nullable|string',
            'color'      => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($this->cart->has($product->id, $request->size, $request->color)) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' ? 'المنتج موجود بالفعل في السلة' : 'Product already in cart'
            ]);
        }

        $this->cart->add($product, $request->quantity ?? 1, $request->size, $request->color);

        return response()->json([
            'success' => true,
            'count'   => $this->cart->count(),
            'message' => app()->getLocale() === 'ar' ? 'تمت الإضافة للسلة' : 'Added to cart',
        ]);
    }

    // تحديث الكمية
    public function update(Request $request, string $key)
    {
        $qty = (int)$request->quantity;
        $this->cart->update($key, $qty);

        $cart      = $this->cart->all();
        $itemTotal = isset($cart[$key]) ? $cart[$key]['price'] * $cart[$key]['quantity'] : 0;
        $subtotal  = $this->cart->subtotal();

        return response()->json([
            'success'   => true,
            'itemTotal' => $itemTotal,
            'subtotal'  => $subtotal,
            'total'     => $subtotal - (session('coupon')['discount'] ?? 0),
            'count'     => $this->cart->count(),
            'message'   => app()->getLocale() === 'ar' ? 'تم تعديل الكمية' : 'Quantity updated',
        ]);
    }

    // حذف المنتج
    public function remove(string $key)
    {
        $this->cart->remove($key);

        return response()->json([
            'success'   => true,
            'subtotal'  => $this->cart->subtotal(),
            'total'     => $this->cart->subtotal() - (session('coupon')['discount'] ?? 0),
            'count'     => $this->cart->count(),
            'message'   => app()->getLocale() === 'ar' ? 'تم الحذف' : 'Item removed',
        ]);
    }

    // تطبيق كوبون
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' ? 'كود الخصم غير صالح' : 'Invalid coupon code',
            ]);
        }

        $subtotal = $this->cart->subtotal();

        if ($subtotal < $coupon->min_order) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar'
                             ? "الحد الأدنى للطلب {$coupon->min_order}$"
                             : "Minimum order is \${$coupon->min_order}",
            ]);
        }

        $discount = $coupon->calcDiscount($subtotal);

        // لو في كوبون قديم في الجلسة، نقلل used_count بتاعه الأول
        $existing = session('coupon');
        if ($existing && $existing['code'] !== $coupon->code) {
            Coupon::where('code', $existing['code'])->decrement('used_count');
        }

        // نزود used_count فوراً عشان الكوبون ميتستخدمش تاني
        if (!$existing || $existing['code'] !== $coupon->code) {
            $coupon->increment('used_count');
        }

        session(['coupon' => [
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]]);

        return response()->json([
            'success'  => true,
            'discount' => $discount,
            'total'    => max(0, $subtotal - $discount),
            'message'  => app()->getLocale() === 'ar' ? 'تم تطبيق الكوبون!' : 'Coupon applied!',
        ]);
    }

    // إزالة الكوبون
    public function removeCoupon()
    {
        $existing = session('coupon');
        if ($existing) {
            Coupon::where('code', $existing['code'])->decrement('used_count');
        }
        session()->forget('coupon');
        return response()->json(['success' => true]);
    }
}
