@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">
                    {{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Wishlist' }}
                </div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route('home') }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Wishlist' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wishlist-block md:py-20 py-10">
    <div class="container">

        @if($items->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-20">
            <i class="ph ph-heart text-gray-200" style="font-size:6rem;"></i>
            <div class="heading4 mt-6">
                {{ app()->getLocale() === 'ar' ? 'المفضلة فارغة' : 'Your wishlist is empty' }}
            </div>
            <p class="text-secondary mt-3">
                {{ app()->getLocale() === 'ar' ? 'أضف منتجات تعجبك لمتابعتها لاحقاً' : 'Save items you love to come back to them later' }}
            </p>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
               class="button-main mt-6 inline-block">
                {{ app()->getLocale() === 'ar' ? 'تسوق الآن' : 'Shop Now' }}
            </a>
        </div>

        @else

        {{-- Count --}}
        <div class="mb-8 flex items-center justify-between">
            <p class="text-secondary caption1">
                {{ $items->count() }}
                {{ app()->getLocale() === 'ar' ? 'منتج في المفضلة' : 'items in your wishlist' }}
            </p>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
               class="text-button hover:underline flex items-center gap-1">
                <i class="ph ph-arrow-left text-sm"></i>
                {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
            </a>
        </div>

        {{-- Grid --}}
        <div id="wishlist-grid"
             class="grid xl:grid-cols-4 lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 md:gap-[30px] gap-5">
            @foreach($items as $item)
            @php $product = $item->product; @endphp
            @if($product)
            <div class="wishlist-card"
                 id="wishlist-card-{{ $product->id }}"
                 style="transition: opacity 0.3s, transform 0.3s;">

                <div class="product-main block">
                    <div class="product-thumb bg-white relative overflow-hidden rounded-2xl group">

                        {{-- Sale Badge --}}
                        @if($product->old_price)
                        <div class="product-tag text-button-uppercase bg-green px-3 py-0.5 absolute top-3 left-3 z-[1]">
                            -{{ $product->discount_percent }}%
                        </div>
                        @endif

                        {{-- ✅ Remove from Wishlist Button --}}
                        <button type="button"
                                class="wishlist-toggle-btn absolute top-3 right-3 z-[2] w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-red-50 duration-200"
                                data-product-id="{{ $product->id }}"
                                data-url="{{ route(app()->getLocale() === 'ar' ? 'wishlist.toggle' : 'en.wishlist.toggle', $product) }}"
                                title="{{ app()->getLocale() === 'ar' ? 'إزالة من المفضلة' : 'Remove from wishlist' }}">
                            <i class="ph-fill ph-heart text-red-500 text-lg"></i>
                        </button>

                        {{-- Product Image → link to product --}}
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}">
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full aspect-[3/4] object-cover duration-700 group-hover:scale-105">
                        </a>

                        {{-- ✅ Add to Cart overlay --}}
                        <div class="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button type="button"
                                    class="add-to-cart-btn w-full py-2 text-xs text-button-uppercase text-center rounded-full bg-black text-white hover:bg-gray-800 duration-200"
                                    data-product-id="{{ $product->id }}">
                                <i class="ph ph-shopping-bag-open me-1"></i>
                                {{ app()->getLocale() === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}
                            </button>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="product-infor mt-4">
                        @if($product->category)
                        <div class="caption2 text-secondary">
                            {{ app()->getLocale() === 'ar'
                                ? ($product->category->name_ar ?? $product->category->name)
                                : ($product->category->name_en ?? $product->category->name) }}
                        </div>
                        @endif

                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}"
                           class="product-name text-title mt-1 duration-300 hover:underline block">
                            {{ $product->name }}
                        </a>

                        <div class="product-price-block flex items-center gap-2 mt-2">
                            <div class="product-price text-title">EGP{{ number_format($product->price, 2) }}</div>
                            @if($product->old_price)
                            <div class="product-origin-price caption1 text-secondary2">
                                <del>EGP{{ number_format($product->old_price, 2) }}</del>
                            </div>
                            @endif
                        </div>

                        {{-- Stock --}}
                        <div class="caption2 mt-1 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                            @if($product->stock > 0)
                                <i class="ph ph-check-circle me-1"></i>
                                {{ app()->getLocale() === 'ar' ? 'متوفر' : 'In Stock' }}
                            @else
                                <i class="ph ph-x-circle me-1"></i>
                                {{ app()->getLocale() === 'ar' ? 'غير متوفر' : 'Out of Stock' }}
                            @endif
                        </div>

                        {{-- ✅ Add to Cart button (always visible below info) --}}
                        <button type="button"
                                class="add-to-cart-btn mt-3 w-full py-2 text-button-uppercase text-center rounded-full border border-black hover:bg-black hover:text-white duration-200 text-sm"
                                data-product-id="{{ $product->id }}"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            {{ app()->getLocale() === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        @endif
    </div>
</div>

{{-- Toast --}}
<div id="wish-toast"
     class="fixed bottom-6 right-6 z-[999] px-5 py-3 rounded-xl shadow-lg text-sm font-semibold text-white opacity-0 pointer-events-none transition-opacity duration-300"
     style="background:#111;">
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var CSRF   = '{{ csrf_token() }}';
    var LOCALE = '{{ app()->getLocale() }}';

    // ── Toast ────────────────────────────────────────────────────────
    function showToast(msg, success) {
        var t = document.getElementById('wish-toast');
        t.textContent = msg;
        t.style.background = success === false ? '#ef4444' : '#111';
        t.style.opacity = '1';
        t.style.pointerEvents = 'auto';
        clearTimeout(t._t);
        t._t = setTimeout(function () {
            t.style.opacity = '0';
            t.style.pointerEvents = 'none';
        }, 2500);
    }

    // ── Update global wishlist count in header ────────────────────────
    function updateWishCount(count) {
        var el = document.getElementById('wishlist-count');
        if (el) el.textContent = count;
    }

    // ── Toggle Wishlist (Remove) ──────────────────────────────────────
    document.querySelectorAll('.wishlist-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var productId = this.getAttribute('data-product-id');
            var url       = this.getAttribute('data-url');
            var card      = document.getElementById('wishlist-card-' + productId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept':       'application/json',
                    'Content-Type': 'application/json',
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                showToast(data.message, true);
                updateWishCount(data.count);

                // لو اتحذف → اشيل الكارد
                if (!data.wishlisted && card) {
                    card.style.opacity    = '0';
                    card.style.transform  = 'scale(0.9)';
                    setTimeout(function () {
                        card.remove();
                        // لو الـ grid فاضي → reload عشان يظهر empty state
                        var remaining = document.querySelectorAll('.wishlist-card');
                        if (remaining.length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            });
        });
    });

    // ── Add to Cart ───────────────────────────────────────────────────
    document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var productId = this.getAttribute('data-product-id');
            var url       = LOCALE === 'en' ? '/en/cart/add' : '/cart/add';
            var self      = this;

            self.disabled = true;
            var orig = self.textContent;
            self.textContent = LOCALE === 'en' ? 'Adding...' : 'جاري الإضافة...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept':       'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                self.disabled = false;
                if (data.success) {
                    // تحديث عداد السلة
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function (el) {
                        el.textContent = data.count;
                    });
                    self.textContent = LOCALE === 'en' ? '✓ Added' : '✓ تمت الإضافة';
                    showToast(data.message || (LOCALE === 'en' ? 'Added to cart!' : 'تمت الإضافة للسلة!'), true);
                    setTimeout(function () { self.textContent = orig; }, 2000);
                } else {
                    self.textContent = orig;
                    showToast(data.message || (LOCALE === 'en' ? 'Error' : 'حدث خطأ'), false);
                }
            })
            .catch(function () {
                self.disabled = false;
                self.textContent = orig;
                showToast(LOCALE === 'en' ? 'Connection error' : 'خطأ في الاتصال', false);
            });
        });
    });
});
</script>
@endpush
