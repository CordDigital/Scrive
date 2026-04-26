<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now   = Carbon::now();
        $start = $now->copy()->subDays(29)->startOfDay();

        // ── Stats ──────────────────────────────────────────
        $totalRevenue   = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalOrders    = Order::count();
        $pendingOrders  = Order::where('status', 'pending')->count();
        $totalProducts  = Product::count();
        $totalBlogs     = Blog::count();
        $unreadMessages = ContactMessage::where('is_read', false)->count();

        // Month-over-month
        $thisMonth  = Order::whereNotIn('status', ['cancelled'])
                           ->whereMonth('created_at', $now->month)
                           ->whereYear('created_at',  $now->year)
                           ->sum('total');
        $lastMonth  = Order::whereNotIn('status', ['cancelled'])
                           ->whereMonth('created_at', $now->copy()->subMonth()->month)
                           ->whereYear('created_at',  $now->copy()->subMonth()->year)
                           ->sum('total');
        $revenueGrowth = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0;

        $thisMonthOrders = Order::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $lastMonthOrders = Order::whereMonth('created_at', $now->copy()->subMonth()->month)->whereYear('created_at', $now->copy()->subMonth()->year)->count();
        $ordersGrowth = $lastMonthOrders > 0 ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) : 0;

        // ── Revenue last 30 days (line chart) ─────────────
        $dailyRevenue = Order::whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', $start)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartDates   = [];
        $chartRevenue = [];
        $chartOrders  = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = $now->copy()->subDays($i)->format('Y-m-d');
            $chartDates[]   = $now->copy()->subDays($i)->format('M d');
            $chartRevenue[] = isset($dailyRevenue[$d]) ? round($dailyRevenue[$d]->revenue, 2) : 0;
            $chartOrders[]  = isset($dailyRevenue[$d]) ? $dailyRevenue[$d]->orders_count : 0;
        }

        // ── Monthly revenue last 6 months (bar chart) ─────
        $monthlyData = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $monthLabels[] = $m->format('M Y');
            $monthlyData[] = round(Order::whereNotIn('status', ['cancelled'])
                ->whereMonth('created_at', $m->month)
                ->whereYear('created_at', $m->year)
                ->sum('total'), 2);
        }

        // ── Users growth last 6 months ─────────────────────
        $usersMonthly = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = $now->copy()->subMonths($i);
            $usersMonthly[] = User::where('role', 'user')
                ->whereMonth('created_at', $m->month)
                ->whereYear('created_at', $m->year)
                ->count();
        }

        // ── Order status breakdown ─────────────────────────
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // ── Top categories by revenue ──────────────────────
        $categoryRevenue = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->selectRaw('categories.name_en as category, SUM(order_items.total) as revenue')
            ->groupBy('categories.name_en')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // ── Recent orders ──────────────────────────────────
        $recentOrders = Order::latest()->take(8)->get();

        // ── Top products ───────────────────────────────────
        $topProducts = OrderItem::selectRaw('product_name, product_image, SUM(quantity) as sold, SUM(total) as revenue')
            ->groupBy('product_name', 'product_image')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        // ── Low stock ──────────────────────────────────────
        $lowStock = Product::where('is_active', true)
            ->where('stock', '<=', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        // ── Recent contacts ────────────────────────────────
        $recentContacts = ContactMessage::latest()->take(5)->get();

        // ── Coupons ────────────────────────────────────────
        $activeCoupons = Coupon::where('is_active', true)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->count();

        // ── Users ──────────────────────────────────────────
        $totalUsers      = User::where('role', 'user')->count();
        $thisMonthUsers  = User::where('role', 'user')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at',  $now->year)
            ->count();
        $lastMonthUsers  = User::where('role', 'user')
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->whereYear('created_at',  $now->copy()->subMonth()->year)
            ->count();
        $usersGrowth = $lastMonthUsers > 0 ? round((($thisMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();

        // ── Payment methods breakdown ──────────────────────
        $paymentBreakdown = Order::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        // ── Avg order value ────────────────────────────────
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

        return view('admin.index', compact(
            'totalRevenue', 'totalOrders', 'pendingOrders', 'totalProducts',
            'totalBlogs', 'unreadMessages',
            'thisMonth', 'revenueGrowth', 'thisMonthOrders', 'ordersGrowth',
            'chartDates', 'chartRevenue', 'chartOrders',
            'monthLabels', 'monthlyData', 'usersMonthly',
            'ordersByStatus', 'recentOrders',
            'topProducts', 'lowStock',
            'recentContacts', 'activeCoupons',
            'totalUsers', 'thisMonthUsers', 'usersGrowth', 'recentUsers',
            'categoryRevenue', 'paymentBreakdown', 'avgOrderValue'
        ));
    }
}
