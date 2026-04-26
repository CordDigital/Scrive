@extends('frontend.layouts.app')

@push('styles')
<style>
.cart-wrap { padding: 60px 0 80px; background: #f9f9f9; min-height: 60vh; }
.cart-grid { display: grid; grid-template-columns: 1fr 380px; gap: 28px; align-items: start; }
@media(max-width:1100px){ .cart-grid { grid-template-columns: 1fr; } }

/* ── Items Card ── */
.cart-card { background: #fff; border-radius: 20px; border: 1px solid #efefef; overflow: hidden; }
.cart-card-head {
    display: grid; grid-template-columns: 2.5fr 1fr 1fr 1fr 40px;
    padding: 14px 24px; background: #fafafa; border-bottom: 1px solid #f0f0f0;
    font-size: 11px; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: .6px;
}
@media(max-width:700px){ .cart-card-head { display: none; } }

.cart-item {
    display: grid; grid-template-columns: 2.5fr 1fr 1fr 1fr 40px;
    padding: 20px 24px; border-bottom: 1px solid #f5f5f5; align-items: center;
    transition: background .15s;
}
.cart-item:last-child { border-bottom: none; }
.cart-item:hover { background: #fdfcfc; }
@media(max-width:700px){
    .cart-item { grid-template-columns: 1fr 1fr; grid-template-rows: auto auto; gap: 12px; }
}

.cart-product { display: flex; align-items: center; gap: 14px; }
.cart-img { width: 72px; height: 72px; border-radius: 14px; object-fit: cover; border: 1px solid #f0f0f0; flex-shrink: 0; }
.cart-name { font-size: 14px; font-weight: 700; color: #111; line-height: 1.4; }
.cart-variant { font-size: 12px; color: #aaa; margin-top: 3px; }
.cart-price { font-size: 14px; font-weight: 600; color: #555; text-align: center; }
.cart-total-cell { font-size: 15px; font-weight: 800; color: #111; text-align: center; }

/* Qty */
.cart-qty { display: flex; align-items: center; justify-content: center; }
.qty-wrap { display: flex; align-items: center; border: 1.5px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
.qty-btn {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    background: none; border: none; cursor: pointer; font-size: 18px; font-weight: 300;
    color: #555; transition: background .15s;
}
.qty-btn:hover { background: #f5f5f5; }
.qty-val { width: 36px; text-align: center; font-size: 14px; font-weight: 700; color: #111; border-left: 1.5px solid #e5e7eb; border-right: 1.5px solid #e5e7eb; height: 36px; line-height: 36px; }

/* Remove */
.remove-cart-btn {
    width: 32px; height: 32px; border-radius: 8px; border: none; background: #fef2f2;
    color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 16px; transition: background .2s; margin: auto;
}
.remove-cart-btn:hover { background: #fee2e2; }

/* ── Coupon ── */
.coupon-wrap { padding: 20px 24px; border-top: 1px solid #f0f0f0; background: #fff; }
.coupon-form { display: flex; gap: 10px; max-width: 420px; }
.coupon-input {
    flex: 1; padding: 11px 18px; border: 1.5px solid #e5e7eb; border-radius: 12px;
    font-size: 14px; outline: none; transition: border-color .2s; background: #fafafa;
}
.coupon-input:focus { border-color: #111; background: #fff; }
.coupon-btn {
    padding: 11px 22px; background: #111; color: #fff; border: none;
    border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; transition: background .2s; white-space: nowrap;
}
.coupon-btn:hover { background: #333; }

/* ── Summary ── */
.summary-card { background: #fff; border-radius: 20px; border: 1px solid #efefef; padding: 28px; position: sticky; top: 100px; }
.summary-title { font-size: 17px; font-weight: 800; color: #111; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0; }
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; font-size: 14px; }
.summary-label { color: #888; font-weight: 500; }
.summary-val { font-weight: 700; color: #111; }
.summary-discount { color: #16a34a; }
.summary-divider { height: 1px; background: #f0f0f0; margin: 16px 0; }
.summary-total-row { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; }
.summary-total-label { font-size: 16px; font-weight: 800; color: #111; }
.summary-total-val { font-size: 24px; font-weight: 900; color: #111; }

.checkout-btn {
    display: block; width: 100%; text-align: center; background: #111; color: #fff;
    padding: 16px; border-radius: 14px; font-size: 15px; font-weight: 800;
    margin-top: 22px; text-decoration: none; transition: all .25s; letter-spacing: .3px;
}
.checkout-btn:hover { background: #333; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,.15); color: #fff; }
.back-shop {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 14px; font-size: 13px; font-weight: 600; color: #888;
    text-decoration: none; transition: color .2s;
}
.back-shop:hover { color: #111; }

.trust-row { display: flex; justify-content: center; gap: 20px; margin-top: 22px; padding-top: 18px; border-top: 1px solid #f0f0f0; }
.trust-item { display: flex; flex-direction: column; align-items: center; gap: 4px; font-size: 11px; color: #bbb; font-weight: 600; }
.trust-item i { font-size: 20px; color: #ccc; }

/* ── Empty ── */
.cart-empty { text-align: center; padding: 80px 24px; }
.cart-empty i { font-size: 56px; color: #ddd; display: block; margin-bottom: 16px; }
.cart-empty p { font-size: 16px; color: #aaa; font-weight: 500; margin-bottom: 24px; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ app()->getLocale() === 'ar' ? 'سلة التسوق' : 'Shopping Cart' }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                    </a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'السلة' : 'Cart' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cart-wrap">
    <div class="container">

        @if(count($items) > 0)
        <div class="cart-grid">

            {{-- ── Left: Items ── --}}
            <div>
                <div class="cart-card">
                    <div class="cart-card-head">
                        <span>{{ app()->getLocale() === 'ar' ? 'المنتج' : 'Product' }}</span>
                        <span style="text-align:center;">{{ app()->getLocale() === 'ar' ? 'السعر' : 'Price' }}</span>
                        <span style="text-align:center;">{{ app()->getLocale() === 'ar' ? 'الكمية' : 'Qty' }}</span>
                        <span style="text-align:center;">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                        <span></span>
                    </div>

                    <div class="cart-table-section">
                        @foreach($items as $key => $item)
                        <div class="cart-item cart-row" data-key="{{ $key }}" data-price="{{ $item['price'] }}">

                            {{-- Product --}}
                            <div class="cart-product">
                                <img src="{{ Storage::url($item['image']) }}" class="cart-img product-img" alt="{{ $item['name'] }}">
                                <div>
                                    <div class="cart-name product-name">{{ $item['name'] }}</div>
                                    @if(!empty($item['size']) || !empty($item['color']))
                                    <div class="cart-variant">
                                        {{ $item['size'] ?? '' }}{{ !empty($item['size']) && !empty($item['color']) ? ' · ' : '' }}{{ $item['color'] ?? '' }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="cart-price">EGP{{ number_format($item['price'], 2) }}</div>

                            {{-- Qty --}}
                            <div class="cart-qty">
                                <div class="qty-wrap">
                                    <button type="button" class="qty-btn qty-minus"
                                            data-key="{{ $key }}"
                                            data-qty="{{ $item['quantity'] - 1 }}"
                                            data-url="{{ route(app()->getLocale() === 'ar' ? 'cart.update' : 'en.cart.update', $key) }}">−</button>
                                    <span class="qty-val">{{ $item['quantity'] }}</span>
                                    <button type="button" class="qty-btn qty-plus"
                                            data-key="{{ $key }}"
                                            data-qty="{{ $item['quantity'] + 1 }}"
                                            data-url="{{ route(app()->getLocale() === 'ar' ? 'cart.update' : 'en.cart.update', $key) }}">+</button>
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="cart-total-cell">EGP<span class="item-total">{{ number_format($item['price'] * $item['quantity'], 2) }}</span></div>

                            {{-- Remove --}}
                            <button type="button" class="remove-cart-btn"
                                    data-key="{{ $key }}"
                                    data-url="{{ route(app()->getLocale() === 'ar' ? 'cart.remove' : 'en.cart.remove', $key) }}"
                                    title="{{ app()->getLocale() === 'ar' ? 'حذف' : 'Remove' }}">
                                <i class="ph ph-trash"></i>
                            </button>

                        </div>
                        @endforeach
                    </div>

                    {{-- Coupon --}}
                    <div class="coupon-wrap">
                        <form id="coupon-form" class="coupon-form">
                            <input type="text" name="code" class="coupon-input"
                                   placeholder="{{ app()->getLocale() === 'ar' ? 'كود الخصم' : 'Coupon code' }}">
                            <button type="submit" class="coupon-btn">
                                {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Back to shop --}}
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="back-shop" style="margin-top:18px; display:inline-flex;">
                    <i class="ph ph-arrow-left"></i>
                    {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                </a>
            </div>

            {{-- ── Right: Summary ── --}}
            <div>
                <div class="summary-card">
                    <div class="summary-title">{{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}</div>

                    <div class="summary-row">
                        <span class="summary-label">{{ app()->getLocale() === 'ar' ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                        <span class="summary-val">EGP<span class="total-product">{{ number_format($subtotal, 2) }}</span></span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">{{ app()->getLocale() === 'ar' ? 'خصم الكوبون' : 'Coupon Discount' }}</span>
                        <span class="summary-val summary-discount">- EGP<span class="discount">{{ number_format($discount ?? 0, 2) }}</span></span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">{{ app()->getLocale() === 'ar' ? 'الشحن' : 'Shipping' }}</span>
                        <span style="font-size:12px; color:#bbb; font-weight:600;">{{ app()->getLocale() === 'ar' ? 'يُحسب لاحقاً' : 'Calculated at checkout' }}</span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total-row">
                        <span class="summary-total-label">{{ app()->getLocale() === 'ar' ? 'الإجمالي' : 'Total' }}</span>
                        <span class="summary-total-val">EGP<span class="total-cart">{{ number_format($total, 2) }}</span></span>
                    </div>

                    @auth
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'checkout' : 'en.checkout') }}" class="checkout-btn">
                        <i class="ph ph-lock-simple" style="margin-inline-end:6px;"></i>
                        {{ app()->getLocale() === 'ar' ? 'أكمل عملية الشراء' : 'Proceed to Checkout' }}
                    </a>
                    @else
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'login' : 'en.login', ['redirect' => url()->current()]) }}" class="checkout-btn">
                        <i class="ph ph-lock-simple" style="margin-inline-end:6px;"></i>
                        {{ app()->getLocale() === 'ar' ? 'أكمل عملية الشراء' : 'Proceed to Checkout' }}
                    </a>
                    @endauth

                    <div class="trust-row">
                        <div class="trust-item"><i class="ph ph-shield-check"></i>{{ app()->getLocale() === 'ar' ? 'آمن' : 'Secure' }}</div>
                        <div class="trust-item"><i class="ph ph-truck"></i>{{ app()->getLocale() === 'ar' ? 'شحن سريع' : 'Fast Ship' }}</div>
                        <div class="trust-item"><i class="ph ph-credit-card"></i>{{ app()->getLocale() === 'ar' ? 'دفع مرن' : 'Easy Pay' }}</div>
                    </div>
                </div>
            </div>

        </div>

        @else
        {{-- Empty Cart --}}
        <div class="cart-empty">
            <i class="ph ph-shopping-cart-simple"></i>
            <p>{{ app()->getLocale() === 'ar' ? 'سلتك فارغة' : 'Your cart is empty' }}</p>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="button-main">
                {{ app()->getLocale() === 'ar' ? 'تسوق الآن' : 'Start Shopping' }}
            </a>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function showToast(msg, type = 'success'){
        let t = document.getElementById('cart-toast');
        if(!t){
            t = document.createElement('div');
            t.id = 'cart-toast';
            t.style.cssText = 'position:fixed;bottom:28px;right:28px;z-index:9999;padding:14px 20px;border-radius:14px;font-size:14px;font-weight:700;transition:all .3s;opacity:0;transform:translateY(10px);display:flex;align-items:center;gap:8px;color:#fff;';
            document.body.appendChild(t);
        }
        t.style.background = type === 'success' ? '#111' : '#ef4444';
        t.innerHTML = `<i class="ph-bold ph-${type==='success'?'check-circle':'warning-circle'}" style="font-size:18px;"></i> ${msg}`;
        t.style.opacity = '1'; t.style.transform = 'translateY(0)';
        clearTimeout(t._t);
        t._t = setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateY(10px)'; }, 3000);
    }

    function updateCartRow(row, qty, itemTotal){
        row.querySelector('.qty-val').textContent = qty;
        row.querySelector('.qty-minus').dataset.qty = qty - 1;
        row.querySelector('.qty-plus').dataset.qty  = qty + 1;
        row.querySelector('.item-total').textContent = parseFloat(itemTotal).toLocaleString('en-US', {minimumFractionDigits:2});
    }

    function updateCartSummary(subtotal, total){
        const sub = parseFloat(subtotal), tot = parseFloat(total);
        document.querySelector('.total-product').textContent = sub.toLocaleString('en-US', {minimumFractionDigits:2});
        document.querySelector('.total-cart').textContent    = tot.toLocaleString('en-US', {minimumFractionDigits:2});
        document.querySelector('.discount').textContent      = (sub - tot).toLocaleString('en-US', {minimumFractionDigits:2});
    }

    // Qty + Remove
    document.querySelector('.cart-table-section').addEventListener('click', function(e){
        const target = e.target.closest('button');
        if(!target) return;
        const row = target.closest('.cart-row');

        if(target.classList.contains('qty-plus') || target.classList.contains('qty-minus')){
            const qty = parseInt(target.dataset.qty);
            if(qty <= 0){ removeItem(row); return; }
            const price = parseFloat(row.dataset.price);
            updateCartRow(row, qty, price * qty);
            fetch(target.dataset.url, {
                method:'PATCH',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify({quantity: qty})
            }).then(r=>r.json()).then(data=>{
                updateCartRow(row, qty, data.itemTotal);
                updateCartSummary(data.subtotal, data.total);
            });
        }

        if(target.classList.contains('remove-cart-btn')) removeItem(row);
    });

    function removeItem(row){
        const url = row.querySelector('.remove-cart-btn').dataset.url;
        row.style.opacity = '0.4'; row.style.pointerEvents = 'none';
        fetch(url,{ method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'} })
        .then(r=>r.json()).then(data=>{
            if(data && data.success){
                row.style.transform = 'translateX(30px)';
                setTimeout(()=>{ row.remove(); updateCartSummary(data.subtotal, data.total); }, 280);
                showToast('{{ app()->getLocale()==="ar"?"تم الحذف":"Item removed" }}');
            }
        });
    }

    // Coupon
    const couponForm = document.getElementById('coupon-form');
    if(couponForm){
        couponForm.addEventListener('submit', function(e){
            e.preventDefault();
            const code = this.querySelector('input[name="code"]').value.trim();
            if(!code) return;
            const url = '{{ route(app()->getLocale() === "ar" ? "cart.coupon" : "en.cart.coupon") }}';
            fetch(url, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify({code})
            }).then(r=>r.json()).then(data=>{
                if(data.success){
                    document.querySelector('.discount').textContent = parseFloat(data.discount).toLocaleString('en-US',{minimumFractionDigits:2});
                    document.querySelector('.total-cart').textContent = parseFloat(data.total).toLocaleString('en-US',{minimumFractionDigits:2});
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            }).catch(()=>showToast('Error','error'));
        });
    }
});
</script>
@endpush
