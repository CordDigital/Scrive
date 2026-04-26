@extends('frontend.layouts.app')

@push('styles')
<style>
/* ── Breadcrumb tabs ── */
.filter-type .tab-item { font-size: 12px; font-weight: 700; letter-spacing: .8px; padding-bottom: 6px; color: #888; transition: color .2s; }
.filter-type .tab-item.active,
.filter-type .tab-item:hover { color: #000000; }
.filter-type .tab-item.active { border-bottom: 2px solid #fff; }

/* ── Layout ── */
.shop-layout { display: grid; grid-template-columns: 260px 1fr; gap: 36px; padding: 48px 0 80px; }

/* ── Sidebar ── */
.shop-sidebar { position: sticky; top: 100px; align-self: start; }

/* ── Filter Toggle Button (mobile only) ── */
.filter-toggle-btn {
    display: none; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 700; color: #111; background: #fff;
    border: 1.5px solid #e5e7eb; padding: 8px 16px; border-radius: 10px;
    cursor: pointer; transition: all .15s;
}
.filter-toggle-btn:hover, .filter-toggle-btn.active { border-color: #111; background: #111; color: #fff; }
.filter-toggle-btn .filter-arrow { transition: transform .3s; }
.filter-toggle-btn.active .filter-arrow { transform: rotate(180deg); }

/* ── Mobile: sidebar becomes collapsible dropdown ── */
@media(max-width:900px) {
    .shop-layout { grid-template-columns: 1fr; gap: 0; }
    .filter-toggle-btn { display: flex; }
    .shop-sidebar {
        display: none; position: static;
        background: #fff; border: 1px solid #f0f0f0; border-radius: 14px;
        padding: 20px 24px; margin-bottom: 22px;
    }
    .shop-sidebar.open { display: block; }
}
.sb-section { margin-bottom: 30px; padding-bottom: 28px; border-bottom: 1px solid #f0f0f0; }
.sb-section:last-child { border-bottom: none; margin-bottom: 0; }
.sb-heading { font-size: 13px; font-weight: 800; color: #111; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 14px; }

/* Categories */
.sb-cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 10px; border-radius: 10px; font-size: 13px; color: #666;
    text-decoration: none; transition: background .15s, color .15s; cursor: pointer;
}
.sb-cat-item:hover, .sb-cat-item.active { background: #f5f5f5; color: #111; font-weight: 600; }
.sb-cat-count { font-size: 11px; color: #bbb; background: #f3f4f6; padding: 1px 7px; border-radius: 20px; }

/* Sizes */
.sb-sizes { display: flex; flex-wrap: wrap; gap: 8px; }
.sb-size {
    width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
    border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 12px; font-weight: 600;
    color: #555; text-decoration: none; transition: all .15s; cursor: pointer;
}
.sb-size.freesize { width: auto; padding: 0 12px; }
.sb-size:hover, .sb-size.active { border-color: #111; background: #111; color: #fff; }

/* Price */
.price-display { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #555; font-weight: 600; margin-bottom: 10px; }
.price-val-badge { background: #f3f4f6; border-radius: 8px; padding: 4px 10px; font-weight: 700; color: #111; font-size: 13px; }
.price-track { position: relative; height: 4px; background: #e5e7eb; border-radius: 4px; margin: 20px 0 10px; }
.price-track-fill { position: absolute; height: 100%; background: #111; border-radius: 4px; pointer-events: none; }
.price-range-input {
    width: 100%; -webkit-appearance: none; appearance: none;
    height: 4px; background: transparent; outline: none; margin: 0;
    position: absolute; top: 0; left: 0; pointer-events: none;
}
.price-range-input::-webkit-slider-thumb {
    -webkit-appearance: none; appearance: none; pointer-events: all;
    width: 20px; height: 20px; border-radius: 50%;
    background: #fff; border: 2.5px solid #111; cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,.15); transition: border-color .15s;
}
.price-range-input::-webkit-slider-thumb:hover { border-color: #333; }
.price-range-input::-moz-range-thumb {
    pointer-events: all; width: 20px; height: 20px; border-radius: 50%;
    background: #fff; border: 2.5px solid #111; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,.15);
}
.price-apply-btn {
    margin-top: 14px; font-size: 12px; font-weight: 700; color: #111;
    background: none; border: 1.5px solid #111; padding: 7px 18px;
    border-radius: 8px; cursor: pointer; transition: all .15s; display: block; width: 100%;
}
.price-apply-btn:hover { background: #111; color: #fff; }

/* ── Filter Bar ── */
.filter-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; background: #fff; border-radius: 14px;
    border: 1px solid #f0f0f0; margin-bottom: 22px; flex-wrap: wrap; gap: 10px;
}
.filter-count { font-size: 13px; color: #888; font-weight: 500; }
.filter-count span { font-weight: 800; color: #111; }
.filter-bar-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

.sale-check-label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #555; cursor: pointer; }
.sale-check-label input { width: 15px; height: 15px; accent-color: #111; cursor: pointer; }

.sort-select {
    padding: 8px 36px 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: 13px; font-weight: 600; color: #111; background: #fff;
    appearance: none; cursor: pointer; outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23999'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    transition: border-color .15s;
}
.sort-select:focus { border-color: #111; }

/* ── Product Card ── */
.products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media(max-width:1100px){ .products-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:500px){  .products-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

.shop-card { border-radius: 18px; overflow: hidden; background: #fff; border: 1px solid #f0f0f0; transition: box-shadow .25s, transform .25s; }
.shop-card:hover { box-shadow: 0 10px 36px rgba(0,0,0,.08); transform: translateY(-3px); }

.shop-card-thumb { position: relative; aspect-ratio: 3/4; overflow: hidden; background: #f8f8f8; }
.shop-card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s cubic-bezier(.25,.46,.45,.94); }
.shop-card-thumb .img-main  { position: absolute; inset: 0; }
.shop-card-thumb .img-hover { position: absolute; inset: 0; opacity: 0; transition: opacity .4s; }
.shop-card:hover .img-hover { opacity: 1; }
.shop-card:hover .img-main  { opacity: 0; }
.shop-card:hover .shop-card-thumb img { transform: scale(1.04); }

.shop-card-badge {
    position: absolute; top: 12px; left: 12px; z-index: 2;
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: .3px;
}
.badge-new  { background: #111; color: #fff; }
.badge-sale { background: #ef4444; color: #fff; }

.shop-card-wish {
    position: absolute; top: 12px; right: 12px; z-index: 2;
    width: 32px; height: 32px; border-radius: 50%; background: #fff;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s; text-decoration: none; cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.shop-card:hover .shop-card-wish { opacity: 1; }

.shop-card-actions {
    position: absolute; bottom: 0; left: 0; right: 0; padding: 12px;
    display: flex; gap: 8px;
    transform: translateY(100%); transition: transform .3s cubic-bezier(.25,.46,.45,.94);
    background: linear-gradient(to top, rgba(0,0,0,.04), transparent);
}
.shop-card:hover .shop-card-actions { transform: translateY(0); }

.action-btn {
    flex: 1; padding: 9px 8px; border-radius: 10px; font-size: 12px; font-weight: 700;
    text-align: center; cursor: pointer; transition: all .2s; border: none; text-decoration: none;
    display: flex; align-items: center; justify-content: center; gap: 4px;
}
.action-view, .action-cart { background: #fff; color: #111; }
.action-view:hover, .action-cart:hover { background: #111; color: #fff; }

.shop-card-body { padding: 14px 16px 16px; }
.shop-card-name { font-size: 14px; font-weight: 700; color: #111; line-height: 1.4; text-decoration: none; display: block; }
.shop-card-name:hover { color: #555; }

.shop-card-colors { display: flex; gap: 5px; margin-top: 7px; flex-wrap: wrap; }
.shop-card-color-img { width: 26px; height: 26px; border-radius: 6px; object-fit: cover; border: 1.5px solid #e5e7eb; cursor: pointer; transition: border-color .15s; }
.shop-card-color-img:hover { border-color: #111; }

.shop-card-price-row { display: flex; align-items: center; gap: 8px; margin-top: 8px; flex-wrap: wrap; }
.shop-card-price { font-size: 15px; font-weight: 800; color: #111; }
.shop-card-old   { font-size: 13px; color: #bbb; text-decoration: line-through; }
.shop-card-discount { font-size: 11px; font-weight: 700; background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 20px; }

.shop-card-stock { font-size: 11px; color: #bbb; margin-top: 6px; }
.shop-card-stock.low { color: #ef4444; font-weight: 600; }

/* Quick shop popup */
.quick-shop-popup {
    position: absolute; inset: 0; background: rgba(255,255,255,.97);
    border-radius: 18px; padding: 18px; z-index: 10;
    display: none; flex-direction: column; align-items: center; justify-content: center; gap: 12px;
}
.quick-shop-sizes { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; }
.qs-size {
    width: 38px; height: 38px; border-radius: 8px; border: 1.5px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .15s; color: #555;
}
.qs-size:hover, .qs-size.active { border-color: #111; background: #111; color: #fff; }
.qs-close { position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 18px; cursor: pointer; color: #999; }

/* ── Pagination ── */
.shop-pagination { display: flex; justify-content: center; margin-top: 40px; }
.shop-pagination .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; }
.shop-pagination .page-item .page-link {
    width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #555;
    border: 1.5px solid #e5e7eb; text-decoration: none; transition: all .15s;
}
.shop-pagination .page-item.active .page-link,
.shop-pagination .page-item .page-link:hover { border-color: #111; background: #111; color: #fff; }
.shop-pagination .page-item.disabled .page-link { opacity: .4; pointer-events: none; }

/* ── Empty ── */
.shop-empty { text-align: center; padding: 80px 0; }
.shop-empty i { font-size: 52px; color: #ddd; display: block; margin-bottom: 14px; }
.shop-empty p { font-size: 15px; color: #aaa; margin-bottom: 20px; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="shop-layout">

        {{-- ── Sidebar (desktop: always visible, mobile: collapsible) ── --}}
        <aside class="shop-sidebar" id="shopSidebar">

            {{-- Categories --}}
            <div class="sb-section">
                <div class="sb-heading">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</div>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
                   class="sb-cat-item {{ !request('category') ? 'active' : '' }}">
                    <span>{{ app()->getLocale() === 'ar' ? 'الكل' : 'All Products' }}</span>
                    <span class="sb-cat-count">{{ $categories->sum('products_count') + $categories->sum(fn($c) => $c->children->sum('products_count')) }}</span>
                </a>
                @foreach($categories as $cat)
                @php
                    $isParentActive = request('category') == $cat->name_en;
                    $isChildActive  = $cat->children->contains(fn($c) => request('category') == $c->name_en);
                    $isOpen         = $isParentActive || $isChildActive;
                    $parentTotal    = $cat->products_count + $cat->children->sum('products_count');
                @endphp
                @if($cat->children->count() > 0)
                <div class="sb-cat-parent {{ $isOpen ? 'open' : '' }}" style="cursor:pointer; margin-bottom:4px;">
                    <div class="sb-cat-item" style="font-weight:700; font-size:15px; display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #eee;">
                        <span>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</span>
                        <span style="display:flex; align-items:center; gap:6px;">
                            <span class="sb-cat-count" style="background:#f3f4f6; padding:2px 8px; border-radius:10px; font-size:12px;">{{ $parentTotal }}</span>
                            <i class="ph ph-caret-down" style="font-size:12px; transition:transform 0.3s; {{ $isOpen ? 'transform:rotate(180deg)' : '' }}"></i>
                        </span>
                    </div>
                    <div class="sb-cat-children" style="overflow:hidden; max-height:{{ $isOpen ? '500px' : '0' }}; transition:max-height 0.3s ease; background:#fafafa; border-radius:0 0 8px 8px;">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($cat->name_en) }}"
                           class="sb-cat-item {{ $isParentActive ? 'active' : '' }}" style="padding:8px 20px; font-size:13px; color:#666;">
                            <span>{{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View All' }}</span>
                            <span class="sb-cat-count" style="font-size:11px; color:#999;">{{ $parentTotal }}</span>
                        </a>
                        @foreach($cat->children as $child)
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($child->name_en) }}"
                           class="sb-cat-item {{ request('category') == $child->name_en ? 'active' : '' }}" style="padding:8px 20px; font-size:13px; color:#666;">
                            <span>{{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}</span>
                            <span class="sb-cat-count" style="font-size:11px; color:#999;">{{ $child->products_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($cat->name_en) }}"
                   class="sb-cat-item {{ $isParentActive ? 'active' : '' }}" style="font-weight:700; font-size:15px; padding:10px 0; border-bottom:1px solid #eee;">
                    <span>{{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}</span>
                    <span class="sb-cat-count" style="background:#f3f4f6; padding:2px 8px; border-radius:10px; font-size:12px;">{{ $parentTotal }}</span>
                </a>
                @endif
                @endforeach
            </div>

            {{-- Sizes --}}
            <div class="sb-section">
                <div class="sb-heading">{{ app()->getLocale() === 'ar' ? 'المقاسات' : 'Size' }}</div>
                <div class="sb-sizes">
                    @foreach(['XS','S','M','L','XL','2XL','Freesize'] as $size)
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('size'), ['size' => $size])) }}"
                       class="sb-size {{ $size === 'Freesize' ? 'freesize' : '' }} {{ request('size') == $size ? 'active' : '' }}">
                        {{ $size }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Price Range --}}
            <div class="sb-section">
                <div class="sb-heading">{{ app()->getLocale() === 'ar' ? 'نطاق السعر' : 'Price Range' }}</div>
                <form method="GET" action="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" id="priceForm">
                    @foreach(request()->except(['min_price','max_price']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <div class="price-display">
                        <span class="price-val-badge">EGP <span class="min-price-lbl">{{ request('min_price', 0) }}</span></span>
                        <span style="color:#ccc;">—</span>
                        <span class="price-val-badge">EGP <span class="max-price-lbl">{{ request('max_price', 10000) }}</span></span>
                    </div>
                    <div class="price-track">
                        <div class="price-track-fill" id="priceRangeFill"></div>
                        <input class="price-range-input" id="rangeMin" type="range" name="min_price" min="0" max="10000" value="{{ request('min_price', 0) }}" style="z-index:3;">
                        <input class="price-range-input" id="rangeMax" type="range" name="max_price" min="0" max="10000" value="{{ request('max_price', 10000) }}" style="z-index:4;">
                    </div>
                    <button type="submit" class="price-apply-btn">
                        {{ app()->getLocale() === 'ar' ? 'تطبيق' : 'Apply' }}
                    </button>
                </form>
            </div>

        </aside>

        {{-- ── Products ── --}}
        <div>
            {{-- Filter Bar --}}
            <div class="filter-bar">
                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <button type="button" class="filter-toggle-btn" id="filterToggleBtn">
                        <i class="ph ph-funnel" style="font-size:16px;"></i>
                        {{ app()->getLocale() === 'ar' ? 'فلتر' : 'Filter' }}
                        <i class="ph ph-caret-down filter-arrow" style="font-size:12px;"></i>
                    </button>
                    <div class="filter-count">
                        <span>{{ $products->total() }}</span>
                        {{ app()->getLocale() === 'ar' ? ' منتج' : ' products found' }}
                    </div>
                    @if(request()->hasAny(['category', 'size', 'min_price', 'max_price', 'sale', 'sort', 'q']))
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
                       style="font-size:12px; font-weight:700; color:#ef4444; text-decoration:none; display:flex; align-items:center; gap:4px;">
                        <i class="ph ph-x" style="font-size:14px;"></i>
                        {{ app()->getLocale() === 'ar' ? 'مسح الفلاتر' : 'Clear' }}
                    </a>
                    @endif
                </div>
                <div class="filter-bar-right">
                    <label class="sale-check-label">
                        <input type="checkbox" id="filter-sale"
                               {{ request('sale') ? 'checked' : '' }}
                               onchange="window.location='{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->all(), ['sale' => 1])) }}'">
                        {{ app()->getLocale() === 'ar' ? 'العروض فقط' : 'On Sale Only' }}
                    </label>
                    <select class="sort-select"
                            onchange="window.location='{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?sort='+this.value+'{{ request()->except('sort') ? '&'.http_build_query(request()->except('sort')) : '' }}'">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'الترتيب' : 'Default' }}</option>
                        <option value="newArrivals"       {{ request('sort') == 'newArrivals'       ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'وصل حديثاً'          : 'New Arrivals' }}</option>
                        <option value="bestSelling"       {{ request('sort') == 'bestSelling'       ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الأكثر مبيعاً'       : 'Best Selling' }}</option>
                        <option value="priceLowToHigh"   {{ request('sort') == 'priceLowToHigh'   ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'السعر: الأقل أولاً'  : 'Price: Low to High' }}</option>
                        <option value="priceHighToLow"   {{ request('sort') == 'priceHighToLow'   ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'السعر: الأعلى أولاً' : 'Price: High to Low' }}</option>
                        <option value="discountHighToLow" {{ request('sort') == 'discountHighToLow' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'أفضل خصم' : 'Best Discount' }}</option>
                    </select>
                </div>
            </div>

            {{-- Grid --}}
            @if($products->count())
            <div class="products-grid">
                @foreach($products as $product)
                @php
                    $mainImg  = $product->image;
                    $hoverImg = $product->images->first()->image ?? $mainImg;
                @endphp
                <div class="shop-card product-main" data-product-id="{{ $product->id }}"
                     data-url="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}">

                    <div class="shop-card-thumb">

                        {{-- Badge --}}
                        @if($product->is_featured)
                            <span class="shop-card-badge badge-new">{{ app()->getLocale() === 'ar' ? 'جديد' : 'New' }}</span>
                        @elseif($product->old_price)
                            <span class="shop-card-badge badge-sale">-{{ $product->discount_percent }}%</span>
                        @endif

                        {{-- Wishlist --}}
                        @auth
                            @php $wishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                            <div class="shop-card-wish wishlist-btn"
                                 data-url="{{ route(app()->getLocale() === 'ar' ? 'wishlist.toggle' : 'en.wishlist.toggle', $product) }}">
                                <i class="{{ $wishlisted ? 'ph-fill text-red-500' : 'ph text-secondary' }} ph-heart text-base"></i>
                            </div>
                        @else
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'login' : 'en.login') }}" class="shop-card-wish">
                                <i class="ph ph-heart text-secondary text-base"></i>
                            </a>
                        @endauth

                        {{-- Images --}}
                        <img src="{{ asset('storage/'.$mainImg) }}"  alt="{{ $product->name }}" class="img-main">
                        <img src="{{ asset('storage/'.$hoverImg) }}" alt="{{ $product->name }}" class="img-hover">

                        {{-- Actions --}}
                        <div class="shop-card-actions">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}"
                               class="action-btn action-view">
                                <i class="ph ph-eye"></i>
                                <span class="max-sm:hidden">{{ app()->getLocale() === 'ar' ? 'عرض' : 'View' }}</span>
                            </a>
                            <button type="button" class="action-btn action-cart add-cart-btn" data-product-id="{{ $product->id }}">
                                <i class="ph ph-shopping-bag-open"></i>
                                <span class="max-sm:hidden">{{ app()->getLocale() === 'ar' ? 'أضف' : 'Add' }}</span>
                            </button>
                        </div>

                    </div>

                    <div class="shop-card-body">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}"
                           class="shop-card-name">{{ $product->name }}</a>

                        @if($product->images->count())
                        <div class="shop-card-colors">
                            @foreach($product->images->take(4) as $img)
                                <img src="{{ asset('storage/'.$img->image) }}" class="shop-card-color-img"
                                     title="{{ app()->getLocale() === 'ar' ? $img->color_ar : $img->color_en }}">
                            @endforeach
                        </div>
                        @endif

                        @if($product->price)
                        <div class="shop-card-price-row">
                            <span class="shop-card-price">EGP {{ number_format($product->price, 2) }}</span>
                            @if($product->old_price)
                                <span class="shop-card-old">EGP {{ number_format($product->old_price, 2) }}</span>
                                <span class="shop-card-discount">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        @endif

                        @if($product->stock > 0 && $product->stock <= 5)
                            <div class="shop-card-stock low">
                                <i class="ph ph-warning"></i>
                                {{ app()->getLocale() === 'ar' ? 'باقي '.$product->stock.' فقط' : 'Only '.$product->stock.' left!' }}
                            </div>
                        @elseif($product->stock == 0)
                            <div class="shop-card-stock">
                                {{ app()->getLocale() === 'ar' ? 'نفذ المخزون' : 'Out of stock' }}
                            </div>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>

            <div class="shop-pagination">
                {{ $products->links() }}
            </div>

            @else
            <div class="shop-empty">
                <i class="ph ph-package"></i>
                <p>{{ app()->getLocale() === 'ar' ? 'لا توجد منتجات' : 'No products found' }}</p>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="button-main">
                    {{ app()->getLocale() === 'ar' ? 'إزالة الفلاتر' : 'Clear Filters' }}
                </a>
            </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locale = document.documentElement.lang || 'ar';

    // ============================================================
    // Price Range
    // ============================================================
    const minR = document.getElementById('rangeMin');
    const maxR = document.getElementById('rangeMax');
    const minL = document.querySelector('.min-price-lbl');
    const maxL = document.querySelector('.max-price-lbl');
    const fill = document.getElementById('priceRangeFill');
    const GAP  = 200;

    function updateRange() {
        let mn = parseInt(minR.value);
        let mx = parseInt(maxR.value);
        if (mn > mx - GAP) {
            if (this === minR) { mn = mx - GAP; minR.value = mn; }
            else               { mx = mn + GAP; maxR.value = mx; }
        }
        if (minL) minL.textContent = mn;
        if (maxL) maxL.textContent = mx;
        if (fill) {
            fill.style.left  = (mn / 100) + '%';
            fill.style.right = (100 - mx / 100) + '%';
        }
    }
    if (minR && maxR) {
        minR.addEventListener('input', updateRange);
        maxR.addEventListener('input', updateRange);
        updateRange.call(minR);
    }

    // ============================================================
    // Toast
    // ============================================================
    function showToast(msg, type) {
        let t = document.getElementById('cart-toast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'cart-toast';
            t.style.cssText = 'position:fixed;right:24px;z-index:9999;padding:14px 20px;border-radius:14px;font-size:14px;font-weight:700;transition:opacity .3s;opacity:0;display:flex;align-items:center;gap:8px;color:#fff;';
            document.body.appendChild(t);
        }
        t.style.bottom = window.innerWidth < 640 ? '90px' : '28px';
        t.style.background = type === 'success' ? '#111' : '#ef4444';
        t.innerHTML = `<i class="ph-bold ph-check-circle" style="font-size:18px;"></i> ${msg}`;
        t.style.opacity = '1';
        clearTimeout(t._t);
        t._t = setTimeout(() => { t.style.opacity = '0'; }, 3000);
    }

    // ============================================================
    // Add to Cart
    // ============================================================
    function addToCart(productId, size, btn) {
        const url = locale === 'en' ? '/en/cart/add' : '/cart/add';
        if (btn) { btn.style.opacity = '.6'; btn.style.pointerEvents = 'none'; }
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
                'Accept'       : 'application/json'
            },
            body: JSON.stringify({ product_id: productId, size: size || '', quantity: 1 })
        })
        .then(r => r.json())
        .then(function (data) {
            if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = ''; }
            if (data.success) {
                document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(el => el.textContent = data.count);
                if (btn) {
                    var icon = btn.querySelector('i');
                    var span = btn.querySelector('span');
                    var origClass = icon ? icon.className : '';
                    if (icon) icon.className = 'ph ph-check';
                    if (span) span.style.display = 'none';
                    setTimeout(function(){
                        if (icon) icon.className = origClass;
                        if (span) span.style.display = '';
                    }, 2000);
                }
                showToast(data.message || (locale === 'en' ? 'Added to cart!' : 'تمت الإضافة!'), 'success');
            } else {
                showToast(data.message || (locale === 'en' ? 'Error' : 'خطأ'), 'error');
            }
        })
        .catch(function () {
            if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = ''; }
            showToast(locale === 'en' ? 'Error, try again' : 'حدث خطأ', 'error');
        });
    }

    // ============================================================
    // ✅ Click handler — listener واحد على document
    // ============================================================
    document.addEventListener('click', function (e) {

        // Add to Cart
        const cartBtn = e.target.closest('.add-cart-btn');
        if (cartBtn) {
            e.preventDefault();
            e.stopPropagation();
            const card      = cartBtn.closest('[data-product-id]');
            if (!card) return;
            const productId = card.dataset.productId;
            const size      = card.querySelector('.qs-size.active')?.textContent.trim() || '';
            addToCart(productId, size, cartBtn);
            return;
        }

        // Navigate to product page (wishlist is handled by global handler in app.blade.php)
        const productCard = e.target.closest('.product-main[data-product-id]');
        if (productCard && !e.target.closest('a, button, .wishlist-btn, .add-cart-btn, .quick-shop-popup')) {
            const url = productCard.getAttribute('data-url');
            if (url) window.location.href = url;
        }
    });

    // ============================================================
    // Filter toggle
    // ============================================================
    const filterBtn = document.getElementById('filterToggleBtn');
    const sidebar   = document.getElementById('shopSidebar');
    if (filterBtn && sidebar) {
        // Auto-open if filters are active
        @if(request()->hasAny(['category', 'size', 'min_price', 'max_price']))
        sidebar.classList.add('open');
        filterBtn.classList.add('active');
        @endif

        filterBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            this.classList.toggle('active');
        });
    }

});

// ============================================================
// Quick Shop helpers (خارج DOMContentLoaded عشان تشتغل مع onclick)
// ============================================================
function selectQsSize(el, id) {
    document.querySelectorAll('#qsp-' + id + ' .qs-size').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
}

function closeQsp(id) {
    const p = document.getElementById('qsp-' + id);
    if (p) p.style.display = 'none';
}

// ============================================================
// Category accordion
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const parent = e.target.closest('.sb-cat-parent');
        if (!parent) return;
        if (e.target.closest('.sb-cat-children')) return;

        const children = parent.querySelector('.sb-cat-children');
        const icon     = parent.querySelector('.ph-caret-down');

        if (parent.classList.contains('open')) {
            children.style.maxHeight = '0';
            if (icon) icon.style.transform = 'rotate(0deg)';
            parent.classList.remove('open');
        } else {
            children.style.maxHeight = children.scrollHeight + 'px';
            if (icon) icon.style.transform = 'rotate(180deg)';
            parent.classList.add('open');
        }
    });
});
</script>
@endpush
