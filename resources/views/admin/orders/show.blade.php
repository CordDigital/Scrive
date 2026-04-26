@extends('admin.layouts.app')

@push('styles')
<style>
:root {
    --ind: #6366f1; --ind-l: #eef2ff;
    --grn: #10b981; --grn-l: #ecfdf5;
    --amb: #f59e0b; --amb-l: #fffbeb;
    --ros: #f43f5e; --ros-l: #fff1f2;
    --sky: #0ea5e9; --sky-l: #f0f9ff;
    --txt: #1e293b; --muted: #94a3b8;
    --bd:  #e8eaf0; --bg: #f1f5f9; --card: #fff;
}

.ow { padding: 26px 28px 48px; background: var(--bg); min-height: 100vh; }

/* ── Header ── */
.ow-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.ow-back { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #64748b; text-decoration: none; background: var(--card); border: 1px solid var(--bd); border-radius: 10px; padding: 7px 14px; }
.ow-back:hover { border-color: var(--ind); color: var(--ind); }
.ow-num { font-size: 20px; font-weight: 900; color: var(--txt); margin: 0; }
.ow-actions { display: flex; align-items: center; gap: 10px; }
.btn-print {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--txt); color: #fff; border: none; border-radius: 10px;
    padding: 9px 18px; font-size: 13px; font-weight: 700; cursor: pointer;
    text-decoration: none; transition: background .2s;
}
.btn-print:hover { background: #0f172a; color: #fff; }

/* ── Status pill ── */
.stt { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; }
.stt-pending    { background: #fef3c7; color: #92400e; }
.stt-processing { background: #dbeafe; color: #1e40af; }
.stt-shipped    { background: #e0e7ff; color: #4338ca; }
.stt-delivered  { background: #dcfce7; color: #166534; }
.stt-cancelled  { background: #fee2e2; color: #991b1b; }
.stt-paid   { background: #dcfce7; color: #166534; }
.stt-failed { background: #fee2e2; color: #991b1b; }
.stt-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

/* ── Timeline strip ── */
.timeline { display: flex; align-items: center; gap: 0; margin-bottom: 20px; background: var(--card); border-radius: 16px; border: 1px solid var(--bd); padding: 16px 24px; overflow-x: auto; }
.tl-step { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 70px; position: relative; }
.tl-step:not(:last-child)::after { content:''; position:absolute; top: 16px; left: 50%; width: 100%; height: 2px; background: #e2e8f0; z-index: 0; }
.tl-step.done:not(:last-child)::after { background: var(--grn); }
.tl-circle { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; background: #f1f5f9; color: var(--muted); border: 2px solid #e2e8f0; position: relative; z-index: 1; transition: all .3s; }
.tl-step.done .tl-circle { background: var(--grn); border-color: var(--grn); color: #fff; }
.tl-step.active .tl-circle { background: var(--ind); border-color: var(--ind); color: #fff; box-shadow: 0 0 0 4px var(--ind-l); }
.tl-step.cancelled .tl-circle { background: #fee2e2; border-color: #f43f5e; color: #f43f5e; }
.tl-label { font-size: 11px; font-weight: 700; color: var(--muted); margin-top: 6px; text-align: center; }
.tl-step.done .tl-label, .tl-step.active .tl-label { color: var(--txt); }

/* ── Layout ── */
.ow-grid { display: grid; grid-template-columns: 1fr 340px; gap: 18px; }
@media(max-width:1100px){ .ow-grid { grid-template-columns: 1fr; } }

/* ── Card ── */
.oc { background: var(--card); border-radius: 16px; border: 1px solid var(--bd); overflow: hidden; margin-bottom: 18px; }
.oc:last-child { margin-bottom: 0; }
.oc-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 22px 12px; border-bottom: 1px solid #f1f5f9; }
.oc-title { font-size: 14px; font-weight: 800; color: var(--txt); display: flex; align-items: center; gap: 8px; }
.oc-title-dot { width: 8px; height: 8px; border-radius: 50%; }
.oc-body { padding: 20px 22px; }

/* ── Items table ── */
.it { width: 100%; border-collapse: collapse; font-size: 13px; }
.it th { padding: 0 18px 10px; text-align: left; font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; }
.it td { padding: 14px 18px; border-top: 1px solid #f8fafc; color: #374151; vertical-align: middle; }
.it tr:hover td { background: #fafbff; }
.it tfoot td { padding: 10px 18px; border-top: 1px solid #f1f5f9; font-size: 13px; }
.it-img { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; background: #f1f5f9; flex-shrink: 0; }
.it-name { font-size: 13px; font-weight: 700; color: var(--txt); }
.it-meta { font-size: 11px; color: var(--muted); margin-top: 2px; }

/* ── Totals ── */
.tot-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: 13px; border-bottom: 1px solid #f8fafc; }
.tot-row:last-child { border-bottom: none; padding-top: 12px; font-size: 16px; font-weight: 900; color: var(--txt); }
.tot-label { color: #64748b; font-weight: 600; }
.tot-val { font-weight: 700; color: var(--txt); }
.tot-discount { color: #f43f5e; }

/* ── Info rows ── */
.inf-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #f8fafc; font-size: 13px; gap: 12px; }
.inf-row:last-child { border-bottom: none; }
.inf-key { color: var(--muted); font-weight: 600; flex-shrink: 0; }
.inf-val { font-weight: 700; color: var(--txt); text-align: right; }

/* ── Status form ── */
.sf-select {
    width: 100%; border: 1px solid var(--bd); border-radius: 10px;
    padding: 9px 14px; font-size: 13px; color: var(--txt); outline: none;
    background: #fff; margin-bottom: 12px;
}
.sf-select:focus { border-color: var(--ind); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.sf-label { font-size: 12px; font-weight: 700; color: #374151; margin-bottom: 5px; display: block; }
.btn-update { width: 100%; background: var(--ind); color: #fff; border: none; border-radius: 10px; padding: 10px; font-size: 14px; font-weight: 700; cursor: pointer; transition: background .2s; }
.btn-update:hover { background: #4f46e5; }
.btn-delete { width: 100%; background: #fff; color: #dc2626; border: 1px solid #fecaca; border-radius: 10px; padding: 9px; font-size: 13px; font-weight: 700; cursor: pointer; margin-top: 8px; transition: background .2s; }
.btn-delete:hover { background: #fee2e2; }

/* ══════════════════════════════
   PRINT STYLES
══════════════════════════════ */
@media print {
    body * { visibility: hidden !important; }
    #invoicePrint, #invoicePrint * { visibility: visible !important; }
    #invoicePrint { position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; background: #fff !important; padding: 0 !important; z-index: 99999 !important; }
    .ow { background: #fff !important; }
}

/* ── Invoice modal ── */
.inv-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 9998; align-items: center; justify-content: center; }
.inv-overlay.open { display: flex; }
.inv-box { background: #fff; border-radius: 20px; width: 720px; max-width: 96vw; max-height: 92vh; overflow-y: auto; box-shadow: 0 30px 80px rgba(0,0,0,.2); }
.inv-toolbar { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid #f1f5f9; background: #fff; position: sticky; top: 0; z-index: 1; border-radius: 20px 20px 0 0; }
.inv-toolbar-title { font-size: 15px; font-weight: 800; color: var(--txt); }
.inv-toolbar-btns { display: flex; gap: 8px; }
.inv-close { background: #f1f5f9; border: none; border-radius: 8px; width: 32px; height: 32px; cursor: pointer; font-size: 18px; color: #64748b; }
.inv-print-btn { background: var(--ind); color: #fff; border: none; border-radius: 8px; padding: 7px 16px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; }

/* ── Invoice body ── */
#invoicePrint { padding: 32px 40px; font-family: 'Segoe UI', sans-serif; }
.inv-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
.inv-brand { font-size: 22px; font-weight: 900; color: var(--ind); }
.inv-brand-sub { font-size: 12px; color: var(--muted); margin-top: 3px; }
.inv-meta { text-align: right; font-size: 13px; }
.inv-meta .inv-num { font-size: 18px; font-weight: 900; color: var(--txt); }
.inv-meta .inv-date { color: var(--muted); margin-top: 3px; }
.inv-divider { border: none; border-top: 2px solid #f1f5f9; margin: 20px 0; }
.inv-parties { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
.inv-party-label { font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
.inv-party-name { font-size: 15px; font-weight: 800; color: var(--txt); }
.inv-party-info { font-size: 12px; color: #64748b; line-height: 1.7; margin-top: 4px; }
.inv-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 24px; }
.inv-table th { background: #f8fafc; padding: 10px 14px; text-align: left; font-size: 10px; font-weight: 800; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
.inv-table th:last-child, .inv-table td:last-child { text-align: right; }
.inv-table td { padding: 12px 14px; border-bottom: 1px solid #f8fafc; color: #374151; }
.inv-table tfoot td { padding: 8px 14px; font-size: 13px; }
.inv-table tfoot tr:last-child td { font-size: 16px; font-weight: 900; color: var(--txt); border-top: 2px solid #f1f5f9; padding-top: 12px; }
.inv-footer { text-align: center; font-size: 12px; color: var(--muted); margin-top: 32px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
.inv-status-badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; background: #dcfce7; color: #166534; }
</style>
@endpush

@section('content')
<div class="ow">

    @if(session('success'))
    <div style="background:#dcfce7;color:#166534;border-radius:10px;padding:12px 18px;margin-bottom:16px;font-size:13px;font-weight:700;">
        <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
    </div>
    @endif

    {{-- ── Top Bar ── --}}
    <div class="ow-top">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <a href="{{ route('admin.orders.index') }}" class="ow-back">
                <i class="mdi mdi-arrow-left"></i> Orders
            </a>
            <div>
                <h1 class="ow-num">Order #{{ $order->order_number }}</h1>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;">
                    Placed {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                </div>
            </div>
            <span class="stt stt-{{ $order->status }}">
                <span class="stt-dot"></span> {{ ucfirst($order->status) }}
            </span>
            <span class="stt stt-{{ $order->payment_status }}">
                <i class="mdi mdi-cash-check" style="font-size:13px;"></i> {{ ucfirst($order->payment_status) }}
            </span>
        </div>
        <div class="ow-actions">
            <button class="btn-print" onclick="openInvoice()">
                <i class="mdi mdi-file-document-outline"></i> Print Invoice
            </button>
        </div>
    </div>

    {{-- ── Status Timeline ── --}}
    @php
        $steps = ['pending','processing','shipped','delivered'];
        $cancelled = $order->status === 'cancelled';
        $currentIdx = array_search($order->status, $steps);
    @endphp
    <div class="timeline">
        @foreach($steps as $idx => $step)
        @php
            $isDone   = !$cancelled && $currentIdx !== false && $idx < $currentIdx;
            $isActive = !$cancelled && $idx === $currentIdx;
        @endphp
        <div class="tl-step {{ $isDone ? 'done' : ($isActive ? 'active' : '') }} {{ $cancelled && $step === 'pending' ? 'cancelled' : '' }}">
            <div class="tl-circle">
                @if($cancelled && $step === 'pending')
                    <i class="mdi mdi-close"></i>
                @elseif($isDone)
                    <i class="mdi mdi-check"></i>
                @else
                    <i class="mdi mdi-{{ $step === 'pending' ? 'clock-outline' : ($step === 'processing' ? 'cog-outline' : ($step === 'shipped' ? 'truck-outline' : 'check-circle-outline')) }}"></i>
                @endif
            </div>
            <div class="tl-label">{{ ucfirst($step) }}</div>
        </div>
        @endforeach
        @if($cancelled)
        <div class="tl-step cancelled" style="margin-left:auto;">
            <div class="tl-circle"><i class="mdi mdi-close"></i></div>
            <div class="tl-label" style="color:#dc2626;">Cancelled</div>
        </div>
        @endif
    </div>

    {{-- ── Main Grid ── --}}
    <div class="ow-grid">

        {{-- LEFT: Items + Totals --}}
        <div>
            <div class="oc">
                <div class="oc-head">
                    <div class="oc-title">
                        <span class="oc-title-dot" style="background:var(--ind);"></span>
                        Order Items ({{ $order->items->count() }})
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="it">
                        <thead>
                            <tr>
                                <th style="padding-left:22px;">Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td style="padding-left:22px;">
                                    <div style="display:flex;align-items:center;gap:12px;">
                                        @if($item->product_image)
                                        <img src="{{ Storage::url($item->product_image) }}" class="it-img" alt="">
                                        @else
                                        <div class="it-img" style="display:flex;align-items:center;justify-content:center;">
                                            <i class="mdi mdi-image-off" style="color:#d1d5db;"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="it-name">{{ $item->product_name }}</div>
                                            @if($item->size || $item->color)
                                            <div class="it-meta">
                                                {{ $item->size }}{{ $item->size && $item->color ? ' · ' : '' }}{{ $item->color }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>EGP{{ number_format($item->price, 2) }}</td>
                                <td><span style="background:#f1f5f9;border-radius:6px;padding:3px 10px;font-weight:700;">{{ $item->quantity }}</span></td>
                                <td style="font-weight:800;">EGP{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals --}}
                <div style="padding:18px 22px;border-top:1px solid #f1f5f9;">
                    <div class="tot-row">
                        <span class="tot-label">Subtotal</span>
                        <span class="tot-val">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->shipping > 0)
                    <div class="tot-row">
                        <span class="tot-label">Shipping</span>
                        <span class="tot-val">${{ number_format($order->shipping, 2) }}</span>
                    </div>
                    @endif
                    @if($order->discount > 0)
                    <div class="tot-row">
                        <span class="tot-label">Discount <span style="font-size:11px;color:var(--muted);">({{ $order->coupon_code }})</span></span>
                        <span class="tot-val tot-discount">-${{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="tot-row">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($order->note)
            <div class="oc">
                <div class="oc-head">
                    <div class="oc-title"><span class="oc-title-dot" style="background:var(--amb);"></span>Order Note</div>
                </div>
                <div class="oc-body" style="color:#64748b;font-size:13px;">{{ $order->note }}</div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Sidebar --}}
        <div>

            {{-- Update Status --}}
            <div class="oc">
                <div class="oc-head">
                    <div class="oc-title"><span class="oc-title-dot" style="background:var(--grn);"></span>Update Status</div>
                </div>
                <div class="oc-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf @method('PATCH')
                        <label class="sf-label">Order Status</label>
                        <select name="status" class="sf-select">
                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <label class="sf-label">Payment Status</label>
                        <select name="payment_status" class="sf-select">
                            @foreach(['pending','paid','failed'] as $ps)
                            <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-update"><i class="mdi mdi-content-save me-1"></i>Save Changes</button>
                    </form>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                          onsubmit="return confirm('Delete this order permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete"><i class="mdi mdi-delete me-1"></i>Delete Order</button>
                    </form>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="oc">
                <div class="oc-head">
                    <div class="oc-title"><span class="oc-title-dot" style="background:var(--purple, #a855f7);"></span>Customer</div>
                </div>
                <div class="oc-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f1f5f9;">
                        <div style="width:42px;height:42px;border-radius:50%;background:#eef2ff;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:900;flex-shrink:0;">
                            {{ strtoupper(substr($order->first_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:800;color:var(--txt);">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div style="font-size:12px;color:var(--muted);">{{ $order->email }}</div>
                        </div>
                    </div>
                    <div class="inf-row"><span class="inf-key">Phone</span><span class="inf-val">{{ $order->phone }}</span></div>
                    <div class="inf-row"><span class="inf-key">Country</span><span class="inf-val">{{ $order->country }}</span></div>
                    <div class="inf-row"><span class="inf-key">City</span><span class="inf-val">{{ $order->city }}</span></div>
                    <div class="inf-row"><span class="inf-key">Address</span><span class="inf-val">{{ $order->address }}</span></div>
                    @if($order->postal_code)
                    <div class="inf-row"><span class="inf-key">Postal</span><span class="inf-val">{{ $order->postal_code }}</span></div>
                    @endif
                    <div class="inf-row">
                        <span class="inf-key">Payment</span>
                        <span class="inf-val" style="text-transform:capitalize;">{{ str_replace('_',' ', $order->payment_method) }}</span>
                    </div>
                </div>
            </div>

            {{-- Order Meta --}}
            <div class="oc">
                <div class="oc-head">
                    <div class="oc-title"><span class="oc-title-dot" style="background:var(--sky);"></span>Order Info</div>
                </div>
                <div class="oc-body">
                    <div class="inf-row"><span class="inf-key">Order #</span><span class="inf-val" style="color:var(--ind);">#{{ $order->order_number }}</span></div>
                    <div class="inf-row"><span class="inf-key">Placed</span><span class="inf-val">{{ $order->created_at->format('M d, Y') }}</span></div>
                    <div class="inf-row"><span class="inf-key">Updated</span><span class="inf-val">{{ $order->updated_at->format('M d, Y') }}</span></div>
                    <div class="inf-row"><span class="inf-key">Items</span><span class="inf-val">{{ $order->items->count() }}</span></div>
                    @if($order->coupon_code)
                    <div class="inf-row"><span class="inf-key">Coupon</span><span class="inf-val" style="color:var(--grn);">{{ $order->coupon_code }}</span></div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ══════════════════════════════════
     INVOICE MODAL
══════════════════════════════════ --}}
<div class="inv-overlay" id="invOverlay" onclick="closeInvoiceOutside(event)">
    <div class="inv-box">
        <div class="inv-toolbar">
            <span class="inv-toolbar-title"><i class="mdi mdi-file-document-outline me-2" style="color:var(--ind);"></i>Invoice Preview</span>
            <div class="inv-toolbar-btns">
                <button class="inv-print-btn" onclick="printInvoice()">
                    <i class="mdi mdi-printer"></i> Print
                </button>
                <button class="inv-close" onclick="closeInvoice()">×</button>
            </div>
        </div>

        {{-- Invoice content --}}
        <div id="invoicePrint">

            {{-- Invoice Header --}}
            <div class="inv-header">
                <div>
                    <div class="inv-brand">🌿 NaturePlant</div>
                    <div class="inv-brand-sub">Premium Natural Products</div>
                </div>
                <div class="inv-meta">
                    <div class="inv-num">#{{ $order->order_number }}</div>
                    <div class="inv-date">{{ $order->created_at->format('M d, Y') }}</div>
                    <div style="margin-top:6px;">
                        <span class="inv-status-badge">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>

            <hr class="inv-divider">

            {{-- From / To --}}
            <div class="inv-parties">
                <div>
                    <div class="inv-party-label">From</div>
                    <div class="inv-party-name">NaturePlant Store</div>
                    <div class="inv-party-info">
                        support@natureplant.com<br>
                        natureplant.com
                    </div>
                </div>
                <div>
                    <div class="inv-party-label">Bill To</div>
                    <div class="inv-party-name">{{ $order->first_name }} {{ $order->last_name }}</div>
                    <div class="inv-party-info">
                        {{ $order->email }}<br>
                        {{ $order->phone }}<br>
                        {{ $order->address }}, {{ $order->city }}, {{ $order->country }}
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <table class="inv-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Product</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td style="font-weight:700;">{{ $item->product_name }}
                            @if($item->size || $item->color)
                            <span style="font-size:11px;color:#94a3b8;font-weight:400;"> ({{ implode(', ', array_filter([$item->size, $item->color])) }})</span>
                            @endif
                        </td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="font-weight:700;">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;color:#64748b;">Subtotal</td>
                        <td>${{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->shipping > 0)
                    <tr>
                        <td colspan="3" style="text-align:right;color:#64748b;">Shipping</td>
                        <td>${{ number_format($order->shipping, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->discount > 0)
                    <tr>
                        <td colspan="3" style="text-align:right;color:#f43f5e;">Discount ({{ $order->coupon_code }})</td>
                        <td style="color:#f43f5e;">-${{ number_format($order->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" style="text-align:right;">Total</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            {{-- Payment info --}}
            <div style="display:flex;justify-content:space-between;align-items:center;background:#f8fafc;border-radius:12px;padding:14px 18px;font-size:13px;">
                <div>
                    <span style="color:#94a3b8;font-weight:600;">Payment Method: </span>
                    <span style="font-weight:800;text-transform:capitalize;">{{ str_replace('_',' ', $order->payment_method) }}</span>
                </div>
                <div>
                    <span style="font-weight:800;color:#166534;background:#dcfce7;padding:4px 12px;border-radius:20px;">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="inv-footer">
                Thank you for your order! Questions? Contact us at support@natureplant.com
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openInvoice()  { document.getElementById('invOverlay').classList.add('open'); }
function closeInvoice() { document.getElementById('invOverlay').classList.remove('open'); }
function closeInvoiceOutside(e) { if(e.target === document.getElementById('invOverlay')) closeInvoice(); }
function printInvoice() { window.print(); }
</script>
@endpush
