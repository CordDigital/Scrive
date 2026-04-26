<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('frontend.profile.index', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'                 => 'required|string|max:100',
            'email'                => 'required|email|unique:users,email,' . $user->id,
            'current_password'     => 'nullable|string',
            'password'             => 'nullable|string|min:8|confirmed',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', app()->getLocale() === 'ar' ? 'تم تحديث البيانات' : 'Profile updated');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->with('items')
                       ->latest()
                       ->paginate(10);

        return view('frontend.profile.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load('items');
        return view('frontend.profile.order-show', compact('order'));
    }
}
