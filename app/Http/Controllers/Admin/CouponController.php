<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'       => 'required|string|unique:coupons,code|max:50',
            'type'       => 'required|in:percent,fixed',
            'value'      => 'required|numeric|min:0',
            'min_order'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'code'       => strtoupper($request->code),
            'type'       => $request->type,
            'value'      => $request->value,
            'min_order'  => $request->min_order ?? 0,
            'max_uses'   => $request->max_uses,
            'is_active'  => $request->boolean('is_active', true),
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'تم إضافة الكوبون');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'       => 'required|string|unique:coupons,code,' . $coupon->id . '|max:50',
            'type'       => 'required|in:percent,fixed',
            'value'      => 'required|numeric|min:0',
            'min_order'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        $coupon->update([
            'code'       => strtoupper($request->code),
            'type'       => $request->type,
            'value'      => $request->value,
            'min_order'  => $request->min_order ?? 0,
            'max_uses'   => $request->max_uses,
            'is_active'  => $request->boolean('is_active'),
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'تم تحديث الكوبون');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'تم حذف الكوبون');
    }
}
