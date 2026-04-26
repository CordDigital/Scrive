@extends('admin.layouts.app')

@push('styles')
<style>
/* ══════════════════════════════════════════════════
   TOKENS
══════════════════════════════════════════════════ */
:root {
    --indigo: #6366f1; --indigo-l: #eef2ff; --indigo-d: #4f46e5;
    --green:  #10b981; --green-l:  #ecfdf5;
    --amber:  #f59e0b; --amber-l:  #fffbeb;
    --rose:   #f43f5e; --rose-l:   #fff1f2;
    --purple: #a855f7; --purple-l: #faf5ff;
    --sky:    #0ea5e9; --sky-l:    #f0f9ff;
    --card:   #ffffff; --bg: #f1f5f9;
    --border: #e8eaf0; --text: #1e293b; --muted: #94a3b8;
    --radius: 18px;
}

/* ══ Layout ══ */
.dw { padding: 26px 28px 0; background: var(--bg); }

/* ══ Header ══ */
.dw-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 26px; flex-wrap: wrap; gap: 12px;
}
.dw-title { font-size: 24px; font-weight: 900; color: var(--text); margin: 0; letter-spacing: -.4px; }
.dw-sub   { font-size: 13px; color: var(--muted); margin-top: 3px; }
.dw-badge {
    display: flex; align-items: center; gap: 8px;
    background: var(--card); border: 1px solid var(--border);
    border-radius: 12px; padding: 8px 16px; font-size: 13px; color: #64748b;
}
.dw-badge span { font-weight: 700; color: var(--text); }

/* ══ KPI Grid ══ */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 20px;
}
@media(max-width:1200px){ .kpi-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:600px){  .kpi-grid { grid-template-columns: 1fr; } }

.kpi {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); padding: 22px 22px 16px;
    position: relative; overflow: hidden;
    transition: box-shadow .25s, transform .2s;
}
.kpi:hover { box-shadow: 0 10px 40px rgba(0,0,0,.08); transform: translateY(-3px); }
.kpi-bg {
    position: absolute; right: -14px; top: -14px;
    width: 80px; height: 80px; border-radius: 50%; opacity: .07;
}
.kpi-top { display: flex; align-items: flex-start; justify-content: space-between; }
.kpi-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 22px;
}
.kpi-trend {
    font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 20px;
    display: inline-flex; align-items: center; gap: 3px; letter-spacing: .2px;
}
.up   { background: #dcfce7; color: #15803d; }
.down { background: #fee2e2; color: #dc2626; }
.flat { background: #f1f5f9; color: #64748b; }
.kpi-value { font-size: 32px; font-weight: 900; color: var(--text); margin: 16px 0 4px; line-height: 1; letter-spacing: -1px; }
.kpi-label { font-size: 13px; color: var(--muted); font-weight: 600; }
.kpi-foot  { font-size: 12px; color: #64748b; margin-top: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9; }
.kpi-bar   { height: 5px; border-radius: 5px; background: #f1f5f9; margin-top: 14px; overflow: hidden; }
.kpi-bar-f { height: 100%; border-radius: 5px; transition: width .8s ease; }

/* ══ Rows ══ */
.row2  { display: grid; grid-template-columns: 7fr 4fr; gap: 16px; margin-bottom: 16px; }
.row3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.row22 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
@media(max-width:1150px){ .row2,.row3,.row22 { grid-template-columns: 1fr; } }

/* ══ Cards ══ */
.card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); overflow: hidden;
}
.card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px 14px; border-bottom: 1px solid #f1f5f9;
}
.card-title { font-size: 14px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px; }
.card-title-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.card-link { font-size: 12px; color: var(--indigo); font-weight: 700; text-decoration: none; }
.card-link:hover { text-decoration: underline; }
.card-body { padding: 18px 22px; }

/* ══ Chart wrapper ══ */
.ch { position: relative; }

/* ══ Table ══ */
.dt { width: 100%; border-collapse: collapse; font-size: 13px; }
.dt th { padding: 0 18px 10px; text-align: left; font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; }
.dt td { padding: 13px 18px; border-top: 1px solid #f8fafc; color: #374151; vertical-align: middle; }
.dt tr:hover td { background: #fafbff; }

/* Status pill */
.st { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; }
.st-pending    { background: #fef3c7; color: #92400e; }
.st-processing { background: #dbeafe; color: #1e40af; }
.st-shipped    { background: #e0e7ff; color: #4338ca; }
.st-delivered  { background: #dcfce7; color: #166534; }
.st-cancelled  { background: #fee2e2; color: #991b1b; }

/* ══ Top products list ══ */
.tp-item { display: flex; align-items: center; gap: 12px; padding: 11px 0; border-bottom: 1px solid #f8fafc; }
.tp-item:last-child { border-bottom: none; }
.tp-img { width: 42px; height: 42px; border-radius: 11px; object-fit: cover; background: #f1f5f9; flex-shrink: 0; }
.tp-name { font-size: 13px; font-weight: 700; color: var(--text); flex: 1; min-width: 0; }
.tp-sold { font-size: 11px; color: var(--muted); margin-top: 2px; }
.tp-rev  { font-size: 13px; font-weight: 800; color: var(--text); white-space: nowrap; }
.tp-bar  { height: 3px; border-radius: 3px; background: var(--indigo-l); margin-top: 4px; }
.tp-bar-f { height: 100%; background: var(--indigo); border-radius: 3px; }

/* ══ Low stock ══ */
.sk-item { display: flex; align-items: center; justify-content: space-between; padding: 11px 0; border-bottom: 1px solid #f8fafc; }
.sk-item:last-child { border-bottom: none; }
.sk-name  { font-size: 13px; font-weight: 700; color: var(--text); }
.sk-price { font-size: 11px; color: var(--muted); margin-top: 2px; }
.sk-qty   { font-size: 12px; font-weight: 800; padding: 3px 10px; border-radius: 8px; }
.sq-0     { background: #fee2e2; color: #dc2626; }
.sq-low   { background: #fef3c7; color: #92400e; }

/* ══ Recent users ══ */
.ru-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f8fafc; }
.ru-item:last-child { border-bottom: none; }
.ru-av { width: 34px; height: 34px; border-radius: 50%; background: var(--purple-l); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: var(--purple); flex-shrink: 0; }
.ru-name { font-size: 13px; font-weight: 700; color: var(--text); }
.ru-mail { font-size: 11px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
.ru-time { font-size: 11px; color: #d1d5db; margin-left: auto; flex-shrink: 0; }

/* ══ Messages ══ */
.msg-item { display: flex; align-items: flex-start; gap: 10px; padding: 11px 0; border-bottom: 1px solid #f8fafc; }
.msg-item:last-child { border-bottom: none; }
.msg-av { width: 34px; height: 34px; border-radius: 50%; background: var(--sky-l); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: var(--sky); flex-shrink: 0; }
.msg-name { font-size: 13px; font-weight: 700; color: var(--text); }
.msg-text { font-size: 12px; color: var(--muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }
.msg-time { font-size: 11px; color: #d1d5db; margin-left: auto; flex-shrink: 0; }
.unread-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--rose); display: inline-block; margin-left: 4px; }

/* ══ Calendar popup ══ */
.cal-wrap { position: relative; }
.cal-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--card); border: 1px solid var(--border);
    border-radius: 12px; padding: 8px 16px; font-size: 13px; color: #64748b;
    cursor: pointer; transition: border .2s, box-shadow .2s; user-select: none;
}
.cal-btn:hover { border-color: var(--indigo); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.cal-btn .cal-date-text { font-weight: 700; color: var(--text); }
.cal-popup {
    display: none; position: absolute; top: calc(100% + 10px); right: 0;
    background: var(--card); border: 1px solid var(--border); border-radius: 18px;
    box-shadow: 0 20px 60px rgba(0,0,0,.14); padding: 20px; z-index: 9999; width: 300px;
}
.cal-popup.open { display: block; }
.cal-nav { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.cal-nav-btn { background: #f1f5f9; border: none; border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 16px; color: #374151; display: flex; align-items: center; justify-content: center; }
.cal-nav-btn:hover { background: var(--indigo-l); color: var(--indigo); }
.cal-month-label { font-size: 14px; font-weight: 800; color: var(--text); }
.cal-grid { display: grid; grid-template-columns: repeat(7,1fr); gap: 2px; text-align: center; }
.cal-day-name { font-size: 10px; font-weight: 800; color: var(--muted); padding: 4px 0 8px; text-transform: uppercase; }
.cal-day {
    font-size: 13px; font-weight: 600; color: #374151;
    padding: 7px 4px; border-radius: 8px; cursor: pointer; transition: background .15s;
}
.cal-day:hover { background: var(--indigo-l); color: var(--indigo); }
.cal-day.today { background: var(--indigo); color: #fff; font-weight: 800; }
.cal-day.other-month { color: #d1d5db; }
.cal-day.selected { background: var(--indigo); color: #fff; }

/* ══ Donut legend ══ */
.dl { display: flex; flex-direction: column; gap: 8px; margin-top: 14px; }
.dl-row { display: flex; align-items: center; justify-content: space-between; font-size: 12px; }
.dl-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; margin-right: 7px; }
.dl-lbl { display: flex; align-items: center; flex: 1; color: #374151; font-weight: 600; }
.dl-val { font-weight: 800; color: var(--text); }

/* ══ Payment pills ══ */
.pay-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f8fafc; font-size: 13px; }
.pay-row:last-child { border-bottom: none; }
.pay-name { display: flex; align-items: center; gap: 8px; font-weight: 700; color: var(--text); text-transform: capitalize; }
.pay-bar-wrap { flex: 1; margin: 0 14px; height: 6px; background: #f1f5f9; border-radius: 6px; overflow: hidden; }
.pay-bar-fill { height: 100%; border-radius: 6px; }
.pay-cnt { font-size: 12px; font-weight: 800; color: var(--text); white-space: nowrap; }

/* ══ Quick stats strip ══ */
.qs-strip { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
.qs { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 14px 20px; flex: 1; min-width: 130px; display: flex; align-items: center; gap: 12px; }
.qs-ic { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
.qs-val { font-size: 19px; font-weight: 900; color: var(--text); line-height: 1; }
.qs-lbl { font-size: 11px; color: var(--muted); font-weight: 600; margin-top: 2px; }
</style>
@endpush

@section('content')
<div class="dw">

    {{-- ══ Header ══ --}}
    <div class="dw-header">
        <div>
            <h1 class="dw-title">Analytics Dashboard</h1>
            <div class="dw-sub">Welcome back, <strong>{{ Auth::user()->name }}</strong> — here's your store overview.</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">

            {{-- Add User button --}}
            <a href="{{ route('admin.users.create') }}"
               style="display:inline-flex;align-items:center;gap:7px;background:var(--indigo);color:#fff;border-radius:12px;padding:9px 18px;font-size:13px;font-weight:700;text-decoration:none;transition:background .2s;"
               onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='var(--indigo)'">
                <i class="mdi mdi-account-plus" style="font-size:16px;"></i> Add User
            </a>

            {{-- Calendar button --}}
            <div class="cal-wrap" id="calWrap">
                <div class="cal-btn" onclick="toggleCal()">
                    <i class="mdi mdi-calendar-range" style="color:var(--indigo);font-size:16px;"></i>
                    <span class="cal-date-text" id="calLabel">{{ now()->format('l') }}, {{ now()->format('M d Y') }}</span>
                    <i class="mdi mdi-chevron-down" style="font-size:14px;color:var(--muted);"></i>
                </div>
                <div class="cal-popup" id="calPopup">
                    <div class="cal-nav">
                        <button class="cal-nav-btn" onclick="changeMonth(-1)">&#8249;</button>
                        <span class="cal-month-label" id="calMonthLabel"></span>
                        <button class="cal-nav-btn" onclick="changeMonth(1)">&#8250;</button>
                    </div>
                    <div class="cal-grid" id="calGrid"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ Quick Stats Strip ══ --}}
    <div class="qs-strip">
        <div class="qs">
            <div class="qs-ic" style="background:var(--indigo-l);color:var(--indigo);"><i class="mdi mdi-post-outline"></i></div>
            <div><div class="qs-val">{{ $totalBlogs }}</div><div class="qs-lbl">Blog Posts</div></div>
        </div>
        <div class="qs">
            <div class="qs-ic" style="background:var(--amber-l);color:var(--amber);"><i class="mdi mdi-ticket-percent"></i></div>
            <div><div class="qs-val">{{ $activeCoupons }}</div><div class="qs-lbl">Active Coupons</div></div>
        </div>
        <div class="qs">
            <div class="qs-ic" style="background:var(--rose-l);color:var(--rose);"><i class="mdi mdi-message-text"></i></div>
            <div><div class="qs-val">{{ $unreadMessages }}</div><div class="qs-lbl">Unread Messages</div></div>
        </div>
        <div class="qs">
            <div class="qs-ic" style="background:var(--green-l);color:var(--green);"><i class="mdi mdi-cash"></i></div>
            <div><div class="qs-val">EGP{{ number_format($avgOrderValue, 0) }}</div><div class="qs-lbl">Avg Order Value</div></div>
        </div>
        <div class="qs">
            <div class="qs-ic" style="background:var(--sky-l);color:var(--sky);"><i class="mdi mdi-shopping"></i></div>
            <div><div class="qs-val">EGP{{ number_format($thisMonthOrders, 0) }}</div><div class="qs-lbl">Orders This Month</div></div>
        </div>
        <div class="qs">
            <div class="qs-ic" style="background:var(--purple-l);color:var(--purple);"><i class="mdi mdi-account-plus"></i></div>
            <div><div class="qs-val">EGP{{ number_format($thisMonthUsers, 0) }}</div><div class="qs-lbl">New Users</div></div>
        </div>
    </div>

    {{-- ══ KPI Cards ══ --}}
    <div class="kpi-grid">

        {{-- Revenue --}}
        <div class="kpi">
            <div class="kpi-bg" style="background:var(--indigo);"></div>
            <div class="kpi-top">
                <div class="kpi-icon" style="background:var(--indigo-l);color:var(--indigo);">
                    <i class="mdi mdi-cash-multiple"></i>
                </div>
                <span class="kpi-trend {{ $revenueGrowth >= 0 ? 'up' : 'down' }}">
                    <i class="mdi mdi-trending-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($revenueGrowth) }}%
                </span>
            </div>
            <div class="kpi-value">EGP{{ number_format($totalRevenue, 0) }}</div>
            <div class="kpi-label">Total Revenue</div>
            <div class="kpi-foot">
                <span style="color:var(--green);font-weight:800;">EGP{{ number_format($thisMonth, 0) }}</span>
                this month &nbsp;·&nbsp; vs EGP{{ number_format($thisMonth - ($thisMonth / max(1 + $revenueGrowth/100, 0.01)), 0) }} last
            </div>
            <div class="kpi-bar">
                <div class="kpi-bar-f" style="width:{{ min(100, ($thisMonth / max($totalRevenue,1)) * 100) }}%;background:var(--indigo);"></div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="kpi">
            <div class="kpi-bg" style="background:var(--green);"></div>
            <div class="kpi-top">
                <div class="kpi-icon" style="background:var(--green-l);color:var(--green);">
                    <i class="mdi mdi-shopping-outline"></i>
                </div>
                <span class="kpi-trend {{ $ordersGrowth >= 0 ? 'up' : 'down' }}">
                    <i class="mdi mdi-trending-{{ $ordersGrowth >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($ordersGrowth) }}%
                </span>
            </div>
            <div class="kpi-value">{{ number_format($totalOrders) }}</div>
            <div class="kpi-label">Total Orders</div>
            <div class="kpi-foot">
                <span style="color:var(--amber);font-weight:800;">{{ $pendingOrders }}</span> pending
                &nbsp;·&nbsp; {{ $thisMonthOrders }} this month
            </div>
            <div class="kpi-bar">
                <div class="kpi-bar-f" style="width:{{ min(100, ($thisMonthOrders / max($totalOrders,1)) * 100) }}%;background:var(--green);"></div>
            </div>
        </div>

        {{-- Products --}}
        <div class="kpi">
            <div class="kpi-bg" style="background:var(--amber);"></div>
            <div class="kpi-top">
                <div class="kpi-icon" style="background:var(--amber-l);color:var(--amber);">
                    <i class="mdi mdi-package-variant-closed"></i>
                </div>
                <a href="{{ route('admin.products.index') }}" class="kpi-trend flat">View All</a>
            </div>
            <div class="kpi-value">{{ number_format($totalProducts) }}</div>
            <div class="kpi-label">Products</div>
            <div class="kpi-foot">
                @if($lowStock->count())
                    <span style="color:var(--rose);font-weight:800;">{{ $lowStock->count() }}</span> low stock items
                @else
                    <span style="color:var(--green);font-weight:800;">All stocked</span>
                @endif
            </div>
            <div class="kpi-bar">
                <div class="kpi-bar-f" style="width:{{ max(5, 100 - ($lowStock->count() / max($totalProducts,1)) * 100) }}%;background:var(--amber);"></div>
            </div>
        </div>

        {{-- Users --}}
        <div class="kpi">
            <div class="kpi-bg" style="background:var(--purple);"></div>
            <div class="kpi-top">
                <div class="kpi-icon" style="background:var(--purple-l);color:var(--purple);">
                    <i class="mdi mdi-account-group"></i>
                </div>
                <span class="kpi-trend {{ $usersGrowth >= 0 ? 'up' : 'down' }}">
                    <i class="mdi mdi-trending-{{ $usersGrowth >= 0 ? 'up' : 'down' }}"></i>
                    {{ abs($usersGrowth) }}%
                </span>
            </div>
            <div class="kpi-value">{{ number_format($totalUsers) }}</div>
            <div class="kpi-label">Registered Users</div>
            <div class="kpi-foot">
                <span style="color:var(--purple);font-weight:800;">{{ $thisMonthUsers }}</span> joined this month
            </div>
            <div class="kpi-bar">
                <div class="kpi-bar-f" style="width:{{ min(100, ($thisMonthUsers / max($totalUsers,1)) * 100) }}%;background:var(--purple);"></div>
            </div>
        </div>

    </div>

    {{-- ══ Row 1: Revenue Line + Order Donut ══ --}}
    <div class="row2">
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--indigo);"></span>
                    Revenue &amp; Orders — Last 30 Days
                </div>
                <a href="{{ route('admin.orders.index') }}" class="card-link">View Orders →</a>
            </div>
            <div class="card-body">
                <div class="ch" style="height:250px;"><canvas id="revChart"></canvas></div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--amber);"></span>
                    Order Status
                </div>
            </div>
            <div class="card-body">
                <div class="ch" style="height:160px;"><canvas id="statusChart"></canvas></div>
                <div class="dl">
                    @php
                        $sc = ['pending'=>'#f59e0b','processing'=>'#3b82f6','shipped'=>'#6366f1','delivered'=>'#10b981','cancelled'=>'#f43f5e'];
                        $sl = ['pending'=>'Pending','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'];
                    @endphp
                    @foreach($sc as $k => $c)
                    @if(isset($ordersByStatus[$k]) && $ordersByStatus[$k] > 0)
                    <div class="dl-row">
                        <div class="dl-lbl"><span class="dl-dot" style="background:{{$c}};"></span>{{ $sl[$k] }}</div>
                        <span class="dl-val">{{ $ordersByStatus[$k] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══ Row 2: Monthly Bar + Users Growth ══ --}}
    <div class="row22">
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--green);"></span>
                    Monthly Revenue — Last 6 Months
                </div>
            </div>
            <div class="card-body">
                <div class="ch" style="height:210px;"><canvas id="monthlyChart"></canvas></div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--purple);"></span>
                    User Growth — Last 6 Months
                </div>
                <a href="{{ route('admin.users.index') }}" class="card-link">All Users →</a>
            </div>
            <div class="card-body">
                <div class="ch" style="height:210px;"><canvas id="usersChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- ══ Row 3: Category Revenue + Payment Methods ══ --}}
    <div class="row22">
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--sky);"></span>
                    Revenue by Category
                </div>
            </div>
            <div class="card-body">
                <div class="ch" style="height:210px;"><canvas id="catChart"></canvas></div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--rose);"></span>
                    Payment Methods
                </div>
            </div>
            <div class="card-body" style="padding-top:10px;">
                @php
                    $payTotal = array_sum($paymentBreakdown);
                    $payColors = ['stripe'=>'#6366f1','paypal'=>'#0ea5e9','cod'=>'#f59e0b','cash'=>'#10b981'];
                    $payIcons  = ['stripe'=>'mdi-credit-card','paypal'=>'mdi-paypal','cod'=>'mdi-truck','cash'=>'mdi-cash'];
                @endphp
                @if($payTotal > 0)
                    @foreach($paymentBreakdown as $method => $cnt)
                    @php
                        $pct = round(($cnt / $payTotal) * 100);
                        $col = $payColors[$method] ?? '#94a3b8';
                        $ico = $payIcons[$method]  ?? 'mdi-cash';
                    @endphp
                    <div class="pay-row">
                        <div class="pay-name" style="min-width:120px;">
                            <i class="mdi {{ $ico }}" style="color:{{$col}};font-size:16px;"></i>
                            {{ ucfirst($method) }}
                        </div>
                        <div class="pay-bar-wrap">
                            <div class="pay-bar-fill" style="width:{{$pct}}%;background:{{$col}};"></div>
                        </div>
                        <div class="pay-cnt">{{ $cnt }} <span style="color:var(--muted);font-size:11px;">({{$pct}}%)</span></div>
                    </div>
                    @endforeach
                @else
                    <p style="color:var(--muted);font-size:13px;text-align:center;padding:40px 0;">No payment data yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ Recent Orders Table ══ --}}
    <div class="card" style="margin-bottom:16px;">
        <div class="card-head">
            <div class="card-title">
                <span class="card-title-dot" style="background:var(--indigo);"></span>
                Recent Orders
            </div>
            <a href="{{ route('admin.orders.index') }}" class="card-link">View All →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="dt">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $o)
                    <tr>
                        <td><span style="font-weight:800;color:var(--indigo);">#{{ $o->order_number }}</span></td>
                        <td>
                            <div style="font-weight:700;">{{ $o->first_name }} {{ $o->last_name }}</div>
                            <div style="font-size:11px;color:var(--muted);">{{ $o->email }}</div>
                        </td>
                        <td style="color:#64748b;">{{ $o->created_at->format('M d, Y') }}</td>
                        <td><span style="font-weight:700;">{{ $o->items->count() ?? '—' }}</span></td>
                        <td><span style="font-weight:800;">EGP{{ number_format($o->total, 2) }}</span></td>
                        <td><span style="font-size:11px;color:#64748b;text-transform:capitalize;">{{ $o->payment_method }}</span></td>
                        <td><span class="st st-{{ $o->status }}">{{ ucfirst($o->status) }}</span></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $o) }}"
                               style="font-size:12px;color:var(--indigo);font-weight:700;text-decoration:none;">View →</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:36px;">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ Row: Top Products + Low Stock + Recent Users + Messages ══ --}}
    <div class="row22" style="margin-bottom:0;">

        {{-- Top Products --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--indigo);"></span>
                    Top Selling Products
                </div>
                <a href="{{ route('admin.products.index') }}" class="card-link">All →</a>
            </div>
            <div class="card-body">
                @php $maxSold = $topProducts->max('sold') ?: 1; @endphp
                @forelse($topProducts as $p)
                <div class="tp-item">
                    @if($p->product_image)
                        <img src="{{ Storage::url($p->product_image) }}" class="tp-img" alt="">
                    @else
                        <div class="tp-img" style="display:flex;align-items:center;justify-content:center;">
                            <i class="mdi mdi-image-off" style="color:#d1d5db;font-size:18px;"></i>
                        </div>
                    @endif
                    <div style="flex:1;min-width:0;">
                        <div class="tp-name">{{ Str::limit($p->product_name, 26) }}</div>
                        <div class="tp-bar"><div class="tp-bar-f" style="width:{{ ($p->sold / $maxSold) * 100 }}%;"></div></div>
                        <div class="tp-sold">{{ $p->sold }} sold</div>
                    </div>
                    <div class="tp-rev">EGP{{ number_format($p->revenue, 0) }}</div>
                </div>
                @empty
                <p style="color:var(--muted);font-size:13px;text-align:center;padding:24px 0;">No sales data yet</p>
                @endforelse
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--rose);"></span>
                    Low Stock Alert
                    @if($lowStock->count())
                    <span style="background:var(--rose-l);color:var(--rose);font-size:11px;padding:1px 8px;border-radius:20px;">{{ $lowStock->count() }}</span>
                    @endif
                </div>
                <a href="{{ route('admin.products.index') }}" class="card-link">Manage →</a>
            </div>
            <div class="card-body">
                @forelse($lowStock as $p)
                <div class="sk-item">
                    <div>
                        <div class="sk-name">{{ $p->name }}</div>
                        <div class="sk-price">EGP{{ number_format($p->price, 2) }}</div>
                    </div>
                    <span class="sk-qty {{ $p->stock == 0 ? 'sq-0' : 'sq-low' }}">
                        {{ $p->stock == 0 ? 'Out of stock' : $p->stock . ' left' }}
                    </span>
                </div>
                @empty
                <div style="text-align:center;padding:28px 0;">
                    <i class="mdi mdi-check-circle" style="font-size:32px;color:var(--green);display:block;margin-bottom:8px;"></i>
                    <span style="font-size:13px;font-weight:700;color:var(--green);">All products stocked!</span>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="row22" style="margin-top:16px;">

        {{-- Recent Users --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--purple);"></span>
                    Recent Users
                </div>
                <a href="{{ route('admin.users.index') }}" class="card-link">All Users →</a>
            </div>
            <div class="card-body">
                @forelse($recentUsers as $u)
                <div class="ru-item">
                    <div class="ru-av">{{ strtoupper(substr($u->name,0,1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="ru-name">{{ $u->name }}</div>
                        <div class="ru-mail">{{ $u->email }}</div>
                    </div>
                    <div class="ru-time">{{ $u->created_at->diffForHumans(null,true) }}</div>
                </div>
                @empty
                <p style="color:var(--muted);font-size:13px;text-align:center;padding:20px 0;">No users yet</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Messages --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <span class="card-title-dot" style="background:var(--sky);"></span>
                    Recent Messages
                    @if($unreadMessages > 0)
                    <span style="background:var(--rose-l);color:var(--rose);font-size:11px;padding:1px 8px;border-radius:20px;">{{ $unreadMessages }} new</span>
                    @endif
                </div>
                <a href="{{ route('admin.contact.messages') }}" class="card-link">All →</a>
            </div>
            <div class="card-body">
                @forelse($recentContacts as $m)
                <div class="msg-item">
                    <div class="msg-av">{{ strtoupper(substr($m->name,0,1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="msg-name">
                            {{ $m->name }}
                            @if(!$m->is_read)<span class="unread-dot"></span>@endif
                        </div>
                        <div class="msg-text">{{ $m->message }}</div>
                    </div>
                    <div class="msg-time">{{ $m->created_at->diffForHumans(null,true) }}</div>
                </div>
                @empty
                <p style="color:var(--muted);font-size:13px;text-align:center;padding:20px 0;">No messages</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
(function(){

    // ── Shared tooltip style (v2) ─────────────────────────
    var ttBase = {
        backgroundColor: '#1e293b',
        titleFontColor:  '#f8fafc',
        bodyFontColor:   '#cbd5e1',
        cornerRadius:    10,
        xPadding:        12,
        yPadding:        10,
        mode:            'index',
        intersect:       false
    };

    // ── Shared axis tick style ────────────────────────────
    var tickStyle = { fontColor: '#94a3b8', fontSize: 10 };

    // ── Gradient helper ───────────────────────────────────
    function grad(ctx, h, r, g, b, a1, a2) {
        var gr = ctx.createLinearGradient(0, 0, 0, h);
        gr.addColorStop(0, 'rgba('+r+','+g+','+b+','+a1+')');
        gr.addColorStop(1, 'rgba('+r+','+g+','+b+','+a2+')');
        return gr;
    }

    // ── 1. Revenue & Orders — dual axis line ─────────────
    (function(){
        var labels  = @json($chartDates);
        var revenue = @json($chartRevenue);
        var orders  = @json($chartOrders);
        var ctx = document.getElementById('revChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue ($)',
                        data: revenue,
                        borderColor: '#6366f1',
                        backgroundColor: grad(ctx, 250, 99, 102, 241, 0.25, 0.01),
                        borderWidth: 2.5,
                        fill: true,
                        lineTension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: '#6366f1',
                        yAxisID: 'y-rev'
                    },
                    {
                        label: 'Orders',
                        data: orders,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 4],
                        fill: false,
                        lineTension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                        pointHoverBackgroundColor: '#10b981',
                        yAxisID: 'y-orders'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'top',
                    labels: { boxWidth: 10, fontSize: 11, fontColor: '#64748b', usePointStyle: true }
                },
                tooltips: Object.assign({}, ttBase, {
                    callbacks: {
                        label: function(item) {
                            return item.datasetIndex === 0
                                ? ' $' + parseFloat(item.yLabel).toLocaleString()
                                : ' ' + item.yLabel + ' orders';
                        }
                    }
                }),
                scales: {
                    xAxes: [{ gridLines: { display: false }, ticks: Object.assign({ maxTicksLimit: 10 }, tickStyle) }],
                    yAxes: [
                        { id: 'y-rev',    position: 'left',  gridLines: { color: '#f1f5f9' }, ticks: Object.assign({ callback: function(v){ return '$'+v.toLocaleString(); } }, tickStyle) },
                        { id: 'y-orders', position: 'right', gridLines: { display: false },   ticks: tickStyle }
                    ]
                }
            }
        });
    })();

    // ── 2. Order Status Donut ────────────────────────────
    (function(){
        var sd  = @json($ordersByStatus);
        var map = { pending:['Pending','#f59e0b'], processing:['Processing','#3b82f6'], shipped:['Shipped','#6366f1'], delivered:['Delivered','#10b981'], cancelled:['Cancelled','#f43f5e'] };
        var labels=[], data=[], colors=[];
        Object.keys(map).forEach(function(k){ if(sd[k]){ labels.push(map[k][0]); data.push(sd[k]); colors.push(map[k][1]); } });
        if(!data.length){ data.push(1); labels.push('No Orders'); colors.push('#e2e8f0'); }
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderWidth: 0 }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 74,
                legend: { display: false },
                tooltips: Object.assign({}, ttBase, {
                    mode: 'single',
                    callbacks: { label: function(item, d){ return ' '+d.labels[item.index]+': '+d.datasets[0].data[item.index]; } }
                })
            }
        });
    })();

    // ── 3. Monthly Revenue Bar ───────────────────────────
    (function(){
        var labels = @json($monthLabels);
        var data   = @json($monthlyData);
        var bgColors = labels.map(function(_, i){ return i === labels.length - 1 ? '#6366f1' : '#c7d2fe'; });
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: { labels: labels, datasets: [{ label: 'Revenue ($)', data: data, backgroundColor: bgColors }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                tooltips: Object.assign({}, ttBase, { mode: 'index', callbacks: { label: function(item){ return ' $'+parseFloat(item.yLabel).toLocaleString(); } } }),
                scales: {
                    xAxes: [{ gridLines: { display: false }, ticks: tickStyle }],
                    yAxes: [{ gridLines: { color: '#f1f5f9' }, ticks: Object.assign({ callback: function(v){ return '$'+v.toLocaleString(); } }, tickStyle) }]
                }
            }
        });
    })();

    // ── 4. Users Growth Line ─────────────────────────────
    (function(){
        var labels = @json($monthLabels);
        var data   = @json($usersMonthly);
        var ctx = document.getElementById('usersChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'New Users',
                    data: data,
                    borderColor: '#a855f7',
                    backgroundColor: grad(ctx, 210, 168, 85, 247, 0.2, 0.01),
                    borderWidth: 2.5,
                    fill: true,
                    lineTension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#a855f7',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                tooltips: Object.assign({}, ttBase, { mode: 'index', callbacks: { label: function(item){ return ' '+item.yLabel+' users'; } } }),
                scales: {
                    xAxes: [{ gridLines: { display: false }, ticks: tickStyle }],
                    yAxes: [{ gridLines: { color: '#f1f5f9' }, ticks: Object.assign({ beginAtZero: true }, tickStyle) }]
                }
            }
        });
    })();

    // ── 5. Category Revenue Horizontal Bar ───────────────
    (function(){
        var cats   = @json($categoryRevenue->pluck('category'));
        var revs   = @json($categoryRevenue->pluck('revenue'));
        var colors = ['#6366f1','#0ea5e9','#10b981','#f59e0b','#f43f5e'];
        new Chart(document.getElementById('catChart'), {
            type: 'horizontalBar',
            data: { labels: cats, datasets: [{ label: 'Revenue ($)', data: revs, backgroundColor: colors.slice(0, cats.length) }] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                tooltips: Object.assign({}, ttBase, { mode: 'index', callbacks: { label: function(item){ return ' $'+parseFloat(item.xLabel).toLocaleString(); } } }),
                scales: {
                    xAxes: [{ gridLines: { color: '#f1f5f9' }, ticks: Object.assign({ callback: function(v){ return '$'+v.toLocaleString(); } }, tickStyle) }],
                    yAxes: [{ gridLines: { display: false }, ticks: { fontColor: '#374151', fontSize: 12, fontStyle: 'bold' } }]
                }
            }
        });
    })();

})();

// ── Mini Calendar ─────────────────────────────────────
(function(){
    var today     = new Date();
    var cur       = new Date(today.getFullYear(), today.getMonth(), 1);
    var selected  = null;
    var months    = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    var days      = ['Su','Mo','Tu','We','Th','Fr','Sa'];

    function render() {
        var grid  = document.getElementById('calGrid');
        var label = document.getElementById('calMonthLabel');
        label.textContent = months[cur.getMonth()] + ' ' + cur.getFullYear();
        grid.innerHTML = '';

        // Day name headers
        days.forEach(function(d){
            var el = document.createElement('div');
            el.className = 'cal-day-name'; el.textContent = d;
            grid.appendChild(el);
        });

        var firstDay = new Date(cur.getFullYear(), cur.getMonth(), 1).getDay();
        var daysInMonth = new Date(cur.getFullYear(), cur.getMonth()+1, 0).getDate();
        var daysInPrev  = new Date(cur.getFullYear(), cur.getMonth(), 0).getDate();

        // Prev month filler
        for(var i = firstDay - 1; i >= 0; i--){
            var el = document.createElement('div');
            el.className = 'cal-day other-month';
            el.textContent = daysInPrev - i;
            grid.appendChild(el);
        }

        // Current month days
        for(var d2 = 1; d2 <= daysInMonth; d2++){
            var el = document.createElement('div');
            el.className = 'cal-day';
            el.textContent = d2;
            var isToday = (d2 === today.getDate() && cur.getMonth() === today.getMonth() && cur.getFullYear() === today.getFullYear());
            if(isToday) el.classList.add('today');
            if(selected && selected.getDate()===d2 && selected.getMonth()===cur.getMonth() && selected.getFullYear()===cur.getFullYear() && !isToday)
                el.classList.add('selected');
            (function(day){ el.addEventListener('click', function(){ selectDay(day); }); })(d2);
            grid.appendChild(el);
        }

        // Next month filler
        var total = firstDay + daysInMonth;
        var remaining = total % 7 === 0 ? 0 : 7 - (total % 7);
        for(var n = 1; n <= remaining; n++){
            var el = document.createElement('div');
            el.className = 'cal-day other-month'; el.textContent = n;
            grid.appendChild(el);
        }
    }

    function selectDay(day) {
        selected = new Date(cur.getFullYear(), cur.getMonth(), day);
        var m = months[selected.getMonth()];
        document.getElementById('calLabel').textContent = m.substring(0,3)+' '+day+', '+selected.getFullYear();
        render();
        setTimeout(function(){ document.getElementById('calPopup').classList.remove('open'); }, 180);
    }

    window.changeMonth = function(dir) { cur.setMonth(cur.getMonth() + dir); render(); };

    window.toggleCal = function() {
        var popup = document.getElementById('calPopup');
        popup.classList.toggle('open');
        if(popup.classList.contains('open')) render();
    };

    // Close on outside click
    document.addEventListener('click', function(e){
        var wrap = document.getElementById('calWrap');
        if(wrap && !wrap.contains(e.target))
            document.getElementById('calPopup').classList.remove('open');
    });
})();
</script>
@endpush
