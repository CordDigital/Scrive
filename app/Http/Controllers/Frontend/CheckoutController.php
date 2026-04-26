<?php

namespace App\Http\Controllers\Frontend;

use \Log;
use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmed;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'السلة فارغة');
        }

        $items    = $this->cart->all();
        $subtotal = $this->cart->subtotal();
        $coupon   = session('coupon');
        $discount = $coupon ? $coupon['discount'] : 0;
        $total    = max(0, $subtotal - $discount);

        return view('frontend.checkout.index', compact('items', 'subtotal', 'coupon', 'discount', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'country'        => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'address'        => 'required|string|max:255',
            'postal_code'    => 'nullable|string|max:20',
            'note'           => 'nullable|string',
            'payment_method' => 'required|in:cash,credit_card,paypal',
        ]);

        if ($this->cart->isEmpty()) {
            return redirect()->route('cart');
        }

        $subtotal = $this->cart->subtotal();
        $coupon   = session('coupon');
        $discount = $coupon ? $coupon['discount'] : 0;
        $total    = max(0, $subtotal - $discount);

        // إنشاء الأوردر
        $order = Order::create([
            'user_id'        => Auth::id(),
            'order_number'   => 'ORD-' . strtoupper(uniqid()),
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'country'        => $request->country,
            'city'           => $request->city,
            'address'        => $request->address,
            'postal_code'    => $request->postal_code,
            'note'           => $request->note,
            'coupon_code'    => $coupon['code'] ?? null,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'shipping'       => 0,
            'total'          => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'status'         => 'pending',
        ]);

        // حفظ الـ items
        foreach ($this->cart->all() as $item) {
            $order->items()->create([
                'product_id'    => $item['product_id'],
                'product_name'  => $item['name_ar'] . ' / ' . $item['name_en'],
                'product_image' => $item['image'],
                'price'         => $item['price'],
                'quantity'      => $item['quantity'],
                'size'          => $item['size'],
                'color'         => $item['color'],
                'total'         => $item['price'] * $item['quantity'],
            ]);
        }

        // used_count اتزاد مسبقاً عند تطبيق الكوبون في الكارت

        $this->cart->clear();
try {
    Mail::to($order->email)->send(new OrderConfirmed($order));
} catch (\Exception $e) {
    // مش هيوقف الأوردر لو الميل فشل
    Log::error('Order mail failed: ' . $e->getMessage());
}
        return redirect()->route(
            app()->getLocale() === 'ar' ? 'order.success' : 'en.order.success',
            $order
        );
    }

    public function success(Order $order)
    {
        // تأكيد إن الأوردر بتاع اليوزر ده
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('frontend.checkout.success', compact('order'));
    }
}
