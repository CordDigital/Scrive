<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders       = Order::with('items')->latest()->paginate(20);
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $pending      = Order::where('status', 'pending')->count();
        $delivered    = Order::where('status', 'delivered')->count();

        return view('admin.orders.index', compact('orders', 'totalRevenue', 'pending', 'delivered'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(\Illuminate\Http\Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed',
        ]);

        $order->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status ?? $order->payment_status,
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'تم حذف الطلب');
    }
}
