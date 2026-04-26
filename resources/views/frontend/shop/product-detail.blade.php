@extends('frontend.layouts.app')

@section('seo_title',       $product->meta_title)
@section('seo_description', $product->meta_description)
@section('seo_keywords',    $product->meta_keywords)
@section('og_image',        $product->og_image_url)
@section('og_type',         'product')

@push('seo')
@if($product->price)
<meta property="product:price:amount" content="{{ $product->price }}">
@endif
    <meta property="product:price:currency" content="USD">
    @if($product->brand)
    <meta property="product:brand" content="{{ $product->brand }}">
    @endif
    <meta property="product:availability" content="{{ $product->stock > 0 ? 'in stock' : 'out of stock' }}">
@endpush

@push('styles')
<style>
/* ── Layout ── */
.pd-wrap { padding: 60px 0 80px; }
.pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
@media(max-width:1024px){ .pd-grid { grid-template-columns: 1fr; gap: 40px; } }

/* ── Gallery ── */
.pd-gallery { position: sticky; top: 100px; }
.pd-main-img {
    border-radius: 24px; overflow: hidden; background: #f8f8f8;
    aspect-ratio: 1/1; position: relative; cursor: zoom-in;
}
.pd-main-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .6s cubic-bezier(.25,.46,.45,.94);
    pointer-events: none;
    user-select: none;
}
.pd-badge-sale {
    position: absolute; top: 16px; left: 16px; z-index: 2;
    background: #16a34a; color: #fff; font-size: 12px; font-weight: 700;
    padding: 4px 12px; border-radius: 20px; letter-spacing: .5px;
}

/* ── Zoom Lens (hover) ── */
.pd-zoom-lens {
    position: absolute;
    border: 2px solid #111;
    border-radius: 50%;
    width: 120px; height: 120px;
    pointer-events: none;
    display: none;
    z-index: 5;
    background: rgba(0,0,0,.04);
    box-shadow: 0 0 0 1px rgba(255,255,255,.6) inset;
}
.pd-main-img:hover .pd-zoom-lens { display: block; }

/* ── Zoom Preview Box ── */
.pd-zoom-preview {
    position: absolute;
    right: calc(100% + 16px);
    top: 0;
    width: 380px;
    height: 380px;
    border-radius: 20px;
    overflow: hidden;
    background: #f8f8f8;
    display: none;
    z-index: 100;
    border: 1px solid #e5e7eb;
    box-shadow: 0 20px 60px rgba(0,0,0,.15);
    pointer-events: none;
}
.pd-zoom-preview img {
    position: absolute;
    max-width: none;
    width: auto;
    height: auto;
    pointer-events: none;
    user-select: none;
}
@media(max-width:1200px){
    .pd-zoom-preview { right: auto; left: 0; top: calc(100% + 10px); width: 100%; height: 300px; }
}
@media(max-width:1024px){ .pd-zoom-preview { display: none !important; } }

/* ── Lightbox ── */
#pd-lightbox {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.92);
    display: none; align-items: center; justify-content: center;
    animation: lbFadeIn .25s ease;
}
#pd-lightbox.open { display: flex; }
@keyframes lbFadeIn { from{opacity:0} to{opacity:1} }
.pd-lb-inner {
    position: relative;
    max-width: 90vw; max-height: 90vh;
    display: flex; align-items: center; justify-content: center;
    cursor: zoom-out;
}
.pd-lb-inner img {
    max-width: 90vw; max-height: 90vh;
    object-fit: contain;
    border-radius: 12px;
    transform: scale(1);
    transition: transform .35s cubic-bezier(.25,.46,.45,.94);
    user-select: none;
    -webkit-user-drag: none;
}
.pd-lb-inner img.zoomed { transform: scale(2); cursor: crosshair; }
.pd-lb-close {
    position: fixed; top: 20px; right: 24px;
    width: 44px; height: 44px; border-radius: 50%;
    background: rgba(255,255,255,.12); color: #fff;
    border: none; cursor: pointer; font-size: 22px;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
    z-index: 10;
}
.pd-lb-close:hover { background: rgba(255,255,255,.25); }
.pd-lb-hint {
    position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
    color: rgba(255,255,255,.5); font-size: 12px; letter-spacing: .5px;
    pointer-events: none;
}

.pd-thumbs { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
.pd-thumb {
    width: 76px; height: 76px; border-radius: 14px; overflow: hidden;
    cursor: pointer; border: 2px solid transparent;
    transition: border-color .2s, transform .2s;
    background: #f8f8f8;
}
.pd-thumb:hover { transform: translateY(-2px); }
.pd-thumb.active { border-color: #111; }
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* ── Info ── */
.pd-info {}
.pd-cat {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #888; letter-spacing: 1px;
    text-transform: uppercase;
}
.pd-cat::before { content:''; display:inline-block; width:20px; height:2px; background:#888; border-radius:2px; }
.pd-title { font-size: 32px; font-weight: 800; color: #111; line-height: 1.25; margin: 10px 0 0; }
@media(max-width:600px){ .pd-title { font-size: 24px; } }

.pd-price-row { display: flex; align-items: center; gap: 14px; margin-top: 20px; }
.pd-price { font-size: 34px; font-weight: 800; color: #111; }
.pd-old-price { font-size: 20px; color: #bbb; text-decoration: line-through; }
.pd-discount-badge {
    background: #fef2f2; color: #dc2626; font-size: 13px; font-weight: 700;
    padding: 4px 12px; border-radius: 20px;
}

.pd-divider { height: 1px; background: #f0f0f0; margin: 24px 0; }

/* Description */
.pd-desc { font-size: 15px; color: #666; line-height: 1.8; }
.pd-desc p { margin: 0 0 10px; }

/* Video */
.pd-video { border-radius: 16px; overflow: hidden; margin-top: 20px; position: relative; padding-top: 56.25%; }
.pd-video iframe { position: absolute; inset: 0; width: 100%; height: 100%; }

/* Sizes */
.pd-label { font-size: 13px; font-weight: 700; color: #111; margin-bottom: 10px; }
.pd-sizes { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-size-label { cursor: pointer; }
.pd-size-label input { display: none; }
.pd-size-btn {
    width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;
    border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 13px; font-weight: 600;
    color: #555; transition: all .2s; background: #fff;
}
.pd-size-label input:checked + .pd-size-btn,
.pd-size-btn:hover { border-color: #111; background: #111; color: #fff; }

/* Colors */
.pd-colors { display: flex; gap: 10px; flex-wrap: wrap; }
.pd-color-label { cursor: pointer; }
.pd-color-label input { display: none; }
.pd-color-btn {
    padding: 8px 18px; border: 1.5px solid #e5e7eb; border-radius: 24px;
    font-size: 13px; font-weight: 600; color: #555; transition: all .2s; background: #fff;
}
.pd-color-label input:checked + .pd-color-btn,
.pd-color-btn:hover { border-color: #111; background: #111; color: #fff; }

/* Actions */
.pd-actions { display: flex; gap: 12px; margin-top: 28px; align-items: center; flex-wrap: wrap; }
.pd-qty {
    display: flex; align-items: center; border: 1.5px solid #e5e7eb;
    border-radius: 14px; overflow: hidden; background: #fff;
}
.pd-qty-btn {
    width: 44px; height: 50px; display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 300; cursor: pointer; color: #555;
    transition: background .2s; border: none; background: transparent;
}
.pd-qty-btn:hover { background: #f5f5f5; }
.pd-qty-input {
    width: 52px; height: 50px; text-align: center; border: none;
    font-size: 15px; font-weight: 700; color: #111; outline: none;
    border-left: 1.5px solid #e5e7eb; border-right: 1.5px solid #e5e7eb;
}
.pd-add-btn {
    flex: 1; min-width: 180px; height: 50px; display: flex; align-items: center;
    justify-content: center; gap: 8px; background: #111; color: #fff;
    border: none; border-radius: 14px; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: all .25s; letter-spacing: .3px;
}
.pd-add-btn:hover { background: #333; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,.2); }
.pd-continue-btn { background: #fff; color: #111; border: 1.5px solid #111; text-decoration: none; }
.pd-continue-btn:hover { background: #111; color: #fff; }
.pd-add-btn:active { transform: translateY(0); }
.pd-add-btn.loading { opacity: .7; pointer-events: none; }

/* Stock */
.pd-stock { display: flex; align-items: center; gap: 6px; font-size: 13px; margin-top: 16px; color: #888; }
.pd-stock-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pd-stock-dot.in  { background: #16a34a; box-shadow: 0 0 0 3px #dcfce7; }
.pd-stock-dot.out { background: #dc2626; box-shadow: 0 0 0 3px #fee2e2; }

/* Trust */
.pd-trust { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-top: 28px; }
.pd-trust-item {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 14px 8px; background: #f8f8f8; border-radius: 14px;
    font-size: 11px; font-weight: 600; color: #555; text-align: center; line-height: 1.4;
}
.pd-trust-item i { font-size: 22px; color: #111; }

/* Tabs */
.pd-tabs-wrap { margin-top: 72px; }
.pd-tabs-nav { display: flex; gap: 4px; border-bottom: 1px solid #e5e7eb; margin-bottom: 32px; }
.pd-tab-btn {
    padding: 12px 24px; font-size: 14px; font-weight: 600; color: #888;
    border: none; background: none; cursor: pointer; border-bottom: 2px solid transparent;
    margin-bottom: -1px; transition: color .2s, border-color .2s;
}
.pd-tab-btn.active { color: #111; border-bottom-color: #111; }
.pd-tab-panel { display: none; animation: fadeIn .3s; }
.pd-tab-panel.active { display: block; }
@keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
.pd-tab-content { font-size: 15px; color: #555; line-height: 1.9; max-width: 720px; }
.pd-tab-content p { margin: 0 0 14px; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ __('Product Details') }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route('home') }}">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}">{{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ $product->name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pd-wrap">
    <div class="container">
        <div class="pd-grid">

            {{-- ── Gallery ── --}}
            <div class="pd-gallery">
                {{-- Zoom Preview Box (desktop only, appears beside image) --}}
                <div style="position:relative;">
                    <div class="pd-main-img" id="pd-main-img-wrap">
                        @if($product->old_price)
                        <div class="pd-badge-sale">-{{ $product->discount_percent }}%</div>
                        @endif
                        {{-- Lens circle --}}
                        <div class="pd-zoom-lens" id="pd-zoom-lens"></div>
                        <img id="main-product-img"
                             src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}">
                    </div>
                    {{-- Zoom preview panel --}}
                    <div class="pd-zoom-preview" id="pd-zoom-preview">
                        <img id="pd-zoom-preview-img"
                             src="{{ Storage::url($product->image) }}"
                             alt="zoom">
                    </div>
                </div>

                @if($product->images->count() > 0)
                <div class="pd-thumbs">
                    <div class="pd-thumb active"
                         onclick="changeImg('{{ Storage::url($product->image) }}', this, '')"
                         data-color="">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                    </div>
                    @foreach($product->images as $img)
                    <div class="pd-thumb"
                         onclick="changeImg('{{ Storage::url($img->image) }}', this, '{{ addslashes($img->color) }}')"
                         data-color="{{ $img->color }}">
                        <img src="{{ Storage::url($img->image) }}" alt="{{ $img->color }}">
                    </div>
                    @endforeach
                </div>
                <div id="active-color-label" style="margin-top:8px; font-size:13px; font-weight:600; color:#555; min-height:18px;"></div>
                @endif
            </div>

            {{-- ── Info ── --}}
            <div class="pd-info">

                @if($product->category)
                <div class="pd-cat">
                    {{ app()->getLocale() === 'ar'
                        ? ($product->category->name_ar ?? $product->category->name)
                        : ($product->category->name_en ?? $product->category->name) }}
                </div>
                @endif

                <h1 class="pd-title">{{ $product->name }}</h1>

                <div class="pd-price-row">
    @if($product->price)
        <div class="pd-price">EGP{{ number_format($product->price, 2) }}</div>
        @if($product->old_price)
            <div class="pd-old-price">EGP{{ number_format($product->old_price, 2) }}</div>
            <div class="pd-discount-badge">
                {{ app()->getLocale() === 'ar' ? 'خصم' : 'Save' }} {{ $product->discount_percent }}%
            </div>
        @endif
    @else
        <div class="pd-price" style="font-size:24px;">
            {{ app()->getLocale() === 'ar' ? 'السعر حسب الطلب' : 'Price on Request' }}
        </div>
    @endif
</div>

                <div class="pd-divider"></div>

                @if($product->description)
                <div class="pd-desc">{!! $product->description !!}</div>
                @endif

                @php
                    $video = app()->getLocale() === 'ar' ? $product->video_ar : $product->video_en;
                    if (!$video) $video = $product->video_en ?: $product->video_ar;
                @endphp
                @if($video)
                <div class="pd-video">
                    <iframe src="{{ $video }}" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
                @endif

                <div class="pd-divider"></div>

                <form id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Sizes --}}
                    @php
                        $sizes = $product->sizes;
                        if (is_string($sizes)) $sizes = json_decode($sizes, true) ?? [];
                        if (!is_array($sizes)) $sizes = [];
                    @endphp
                    @if(count($sizes) > 0)
                    <div style="margin-bottom:20px;">
                        <div class="pd-label">{{ app()->getLocale() === 'ar' ? 'المقاس' : 'Select Size' }}</div>
                        <div class="pd-sizes">
                            @foreach($sizes as $size)
                            <label class="pd-size-label">
                                <input type="radio" name="size" value="{{ $size }}">
                                <div class="pd-size-btn">{{ $size }}</div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Colors --}}
                    @php
                        $colors = $product->colors;
                        if (is_string($colors)) $colors = json_decode($colors, true) ?? [];
                        if (!is_array($colors)) $colors = [];
                    @endphp
                    @if(count($colors) > 0)
                    <div style="margin-bottom:20px;">
                        <div class="pd-label">{{ app()->getLocale() === 'ar' ? 'اللون' : 'Select Color' }}</div>
                        <div class="pd-colors">
                            @foreach($colors as $color)
                            <label class="pd-color-label">
                                <input type="radio" name="color" value="{{ $color }}">
                                <div class="pd-color-btn">{{ $color }}</div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Qty + Add to Cart --}}
                    <div class="pd-actions">
                        <div class="pd-qty">
                            <button type="button" class="pd-qty-btn" onclick="changeQty(-1)">−</button>
                            <input type="number" name="quantity" id="qty-input"
                                   value="1" min="1" max="{{ $product->stock }}"
                                   class="pd-qty-input">
                            <button type="button" class="pd-qty-btn" onclick="changeQty(1)">+</button>
                        </div>

                        <button type="button" id="add-to-cart-btn" class="pd-add-btn">
                            <i class="ph ph-shopping-bag-open" style="font-size:18px;"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}</span>
                        </button>

                        <a href="{{ route(app()->getLocale() === 'ar' ? 'cart' : 'en.cart') }}" class="pd-add-btn pd-continue-btn">
                            <i class="ph ph-shopping-cart" style="font-size:18px;"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}</span>
                        </a>
                    </div>

                    {{-- Stock --}}
                    <div class="pd-stock">
                        @if($product->stock > 0)
                            <span class="pd-stock-dot in"></span>
                            {{ app()->getLocale() === 'ar' ? "متوفر ({$product->stock} قطعة)" : "In Stock — {$product->stock} left" }}
                        @else
                            <span class="pd-stock-dot out"></span>
                            {{ app()->getLocale() === 'ar' ? 'غير متوفر' : 'Out of Stock' }}
                        @endif
                    </div>
                </form>

                {{-- Trust Badges --}}
                <div class="pd-trust">
                    <div class="pd-trust-item">
                        <i class="ph ph-truck"></i>
                        {{ app()->getLocale() === 'ar' ? 'شحن سريع' : 'Fast Shipping' }}
                    </div>
                    <div class="pd-trust-item">
                        <i class="ph ph-shield-check"></i>
                        {{ app()->getLocale() === 'ar' ? 'دفع آمن' : 'Secure Payment' }}
                    </div>
                    <div class="pd-trust-item">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                        {{ app()->getLocale() === 'ar' ? 'إرجاع مجاني' : 'Free Returns' }}
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Tabs ── --}}
        <div class="pd-tabs-wrap">
            <div class="pd-tabs-nav">
                <button class="pd-tab-btn active" onclick="switchTab('desc', this)">
                    {{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}
                </button>
                @if($product->additional_info ?? false)
                <button class="pd-tab-btn" onclick="switchTab('info', this)">
                    {{ app()->getLocale() === 'ar' ? 'معلومات إضافية' : 'Additional Info' }}
                </button>
                @endif
            </div>

            <div id="tab-desc" class="pd-tab-panel active">
                <div class="pd-tab-content">
                    {!! $product->description !!}
                </div>
            </div>

            @if($product->additional_info ?? false)
            <div id="tab-info" class="pd-tab-panel">
                <div class="pd-tab-content">
                    {!! $product->additional_info !!}
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- ── Lightbox ── --}}
<div id="pd-lightbox">
    <button class="pd-lb-close" id="pd-lb-close" title="Close">✕</button>
    <div class="pd-lb-inner" id="pd-lb-inner">
        <img id="pd-lb-img" src="" alt="zoom" draggable="false">
    </div>
    <div class="pd-lb-hint">
        {{ app()->getLocale() === 'ar' ? 'انقر للتكبير • اسحب للتحريك • ESC للإغلاق' : 'Click to zoom • Drag to pan • ESC to close' }}
    </div>
</div>

@endsection

@push('scripts')
<script>
var CSRF   = '{{ csrf_token() }}';
var LOCALE = '{{ app()->getLocale() }}';

/* ══════════════════════════════════════════
   IMAGE ZOOM — Hover Preview + Lightbox
══════════════════════════════════════════ */
(function(){
    var wrap        = document.getElementById('pd-main-img-wrap');
    var mainImg     = document.getElementById('main-product-img');
    var lens        = document.getElementById('pd-zoom-lens');
    var preview     = document.getElementById('pd-zoom-preview');
    var previewImg  = document.getElementById('pd-zoom-preview-img');
    var lightbox    = document.getElementById('pd-lightbox');
    var lbImg       = document.getElementById('pd-lb-img');
    var lbInner     = document.getElementById('pd-lb-inner');
    var lbClose     = document.getElementById('pd-lb-close');

    var ZOOM_FACTOR = 3.5;  /* how much the preview magnifies */
    var lbZoomed    = false;
    var isDragging  = false;
    var dragStart   = { x:0, y:0 };
    var imgOffset   = { x:0, y:0 };

    /* ── Hover zoom (desktop ≥1025px) ── */
    if(wrap && lens && preview && previewImg){
        wrap.addEventListener('mousemove', function(e){
            var rect    = wrap.getBoundingClientRect();
            var lensW   = lens.offsetWidth;
            var lensH   = lens.offsetHeight;

            /* Cursor position relative to image */
            var cx = e.clientX - rect.left;
            var cy = e.clientY - rect.top;

            /* Clamp lens inside image */
            var lx = Math.max(lensW/2, Math.min(rect.width  - lensW/2, cx)) - lensW/2;
            var ly = Math.max(lensH/2, Math.min(rect.height - lensH/2, cy)) - lensH/2;

            lens.style.left = lx + 'px';
            lens.style.top  = ly + 'px';

            /* Move preview image */
            var ratioX = (lx + lensW/2) / rect.width;
            var ratioY = (ly + lensH/2) / rect.height;
            var pw = preview.offsetWidth;
            var ph = preview.offsetHeight;
            var iw = mainImg.naturalWidth  || rect.width;
            var ih = mainImg.naturalHeight || rect.height;
            var scale = Math.max(pw/rect.width, ph/rect.height) * ZOOM_FACTOR;

            previewImg.style.width  = (rect.width  * scale) + 'px';
            previewImg.style.height = (rect.height * scale) + 'px';
            previewImg.style.left   = -(ratioX * rect.width  * scale - pw/2) + 'px';
            previewImg.style.top    = -(ratioY * rect.height * scale - ph/2) + 'px';

            preview.style.display = 'block';
        });

        wrap.addEventListener('mouseleave', function(){
            preview.style.display = 'none';
        });
    }

    /* ── Click → open lightbox ── */
    if(wrap && lightbox && lbImg){
        wrap.addEventListener('click', function(){
            lbImg.src     = mainImg.src;
            previewImg && (previewImg.src = mainImg.src);
            lbZoomed      = false;
            lbImg.classList.remove('zoomed');
            lbImg.style.transform      = 'scale(1)';
            lbImg.style.transformOrigin = 'center center';
            imgOffset = {x:0, y:0};
            lightbox.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }

    /* Close lightbox */
    function closeLightbox(){
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
        lbZoomed = false;
        lbImg.classList.remove('zoomed');
        lbImg.style.transform = 'scale(1)';
        imgOffset = {x:0, y:0};
    }

    if(lbClose) lbClose.addEventListener('click', closeLightbox);
    if(lightbox){
        lightbox.addEventListener('click', function(e){
            if(e.target === lightbox) closeLightbox();
        });
    }

    /* ESC to close */
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') closeLightbox();
    });

    /* Click inside lightbox → toggle zoom */
    if(lbInner && lbImg){
        lbInner.addEventListener('click', function(e){
            if(isDragging) return;
            if(e.target === lbImg){
                lbZoomed = !lbZoomed;
                if(lbZoomed){
                    var rect   = lbImg.getBoundingClientRect();
                    var ox     = ((e.clientX - rect.left) / rect.width  * 100).toFixed(1) + '%';
                    var oy     = ((e.clientY - rect.top)  / rect.height * 100).toFixed(1) + '%';
                    lbImg.style.transformOrigin = ox + ' ' + oy;
                    lbImg.style.transform       = 'scale(2.5)';
                    lbImg.style.cursor          = 'grab';
                } else {
                    lbImg.style.transform       = 'scale(1)';
                    lbImg.style.transformOrigin = 'center center';
                    lbImg.style.cursor          = 'zoom-in';
                    imgOffset = {x:0, y:0};
                }
            }
        });

        /* Drag-to-pan when zoomed */
        lbImg.addEventListener('mousedown', function(e){
            if(!lbZoomed) return;
            isDragging = false;
            var startX = e.clientX - imgOffset.x;
            var startY = e.clientY - imgOffset.y;
            lbImg.style.cursor = 'grabbing';
            var moved = false;

            function onMove(ev){
                moved = true;
                isDragging = true;
                imgOffset.x = ev.clientX - startX;
                imgOffset.y = ev.clientY - startY;
                lbImg.style.transform = 'scale(2.5) translate(' + (imgOffset.x/2.5) + 'px,' + (imgOffset.y/2.5) + 'px)';
            }
            function onUp(){
                lbImg.style.cursor = 'grab';
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
                setTimeout(function(){ isDragging = false; }, 10);
            }
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
            e.preventDefault();
        });

        /* Touch pinch / pan */
        var lastDist = 0;
        lbImg.addEventListener('touchstart', function(e){
            if(e.touches.length === 2){
                lastDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
            }
        }, {passive:true});
        lbImg.addEventListener('touchmove', function(e){
            if(e.touches.length === 2){
                var dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                var scale = Math.min(4, Math.max(1, 2.5 * (dist/lastDist)));
                lbImg.style.transform = 'scale('+scale+')';
                if(scale > 1) lbZoomed = true;
            }
        }, {passive:true});
    }

    /* ── Sync preview image when main image changes ── */
    window._syncZoomSrc = function(src){
        if(previewImg) previewImg.src = src;
    };
})();

/* ══════════════════════════════════════════
   Gallery
══════════════════════════════════════════ */
function changeImg(src, el, colorName){
    document.getElementById('main-product-img').src = src;
    window._syncZoomSrc && window._syncZoomSrc(src);
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    var label = document.getElementById('active-color-label');
    if(label) label.textContent = colorName || '';
}

/* ══════════════════════════════════════════
   Quantity
══════════════════════════════════════════ */
function changeQty(delta){
    var input = document.getElementById('qty-input');
    var min = parseInt(input.min) || 1;
    var max = parseInt(input.max) || 99;
    var val = (parseInt(input.value) || min) + delta;
    if(val < min) val = min;
    if(val > max) val = max;
    input.value = val;
}

/* ══════════════════════════════════════════
   Tabs
══════════════════════════════════════════ */
function switchTab(id, btn){
    document.querySelectorAll('.pd-tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.pd-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}

/* ══════════════════════════════════════════
   Toast
══════════════════════════════════════════ */
function showToast(msg, type){
    type = type || 'success';
    var t = document.getElementById('cart-toast');
    if(!t){
        t = document.createElement('div');
        t.id = 'cart-toast';
        t.style.cssText = 'position:fixed;bottom:28px;right:28px;z-index:9999;padding:14px 22px;border-radius:14px;font-size:14px;font-weight:700;transition:all .35s;opacity:0;transform:translateY(10px);display:flex;align-items:center;gap:8px;';
        document.body.appendChild(t);
    }
    t.style.background = type === 'success' ? '#111' : '#dc2626';
    t.style.color = '#fff';
    t.innerHTML = '<i class="ph-bold ph-' + (type==='success'?'check-circle':'warning-circle') + '" style="font-size:18px;"></i> ' + msg;
    t.style.opacity = '1';
    t.style.transform = 'translateY(0)';
    clearTimeout(t._t);
    t._t = setTimeout(function(){ t.style.opacity='0'; t.style.transform='translateY(10px)'; }, 3000);
}

/* ══════════════════════════════════════════
   Add to Cart
══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function(){
    var qtyInput = document.getElementById('qty-input');
    if(qtyInput){
        qtyInput.addEventListener('change', function(){
            var min = parseInt(this.min) || 1;
            var max = parseInt(this.max) || 99;
            var val = parseInt(this.value) || min;
            if(val < min) val = min;
            if(val > max) val = max;
            this.value = val;
        });
    }

    var addBtn = document.getElementById('add-to-cart-btn');
    if(addBtn){
        addBtn.addEventListener('click', function(){
            var form      = document.getElementById('add-to-cart-form');
            var productId = form.querySelector('input[name="product_id"]').value;
            var sizeEl    = form.querySelector('input[name="size"]:checked');
            var colorEl   = form.querySelector('input[name="color"]:checked');
            var size      = sizeEl  ? sizeEl.value  : '';
            var color     = colorEl ? colorEl.value : '';
            var qty       = parseInt(form.querySelector('input[name="quantity"]').value) || 1;

            addBtn.classList.add('loading');
            addBtn.innerHTML = '<i class="ph ph-circle-notch" style="font-size:18px;animation:spin 1s linear infinite;"></i>';

            var url = '{{ app()->getLocale() === "en" ? "/en/cart/add" : "/cart/add" }}';
            fetch(url, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
                body: JSON.stringify({ product_id: productId, size: size, color: color, quantity: qty })
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                addBtn.classList.remove('loading');
                addBtn.innerHTML = '<i class="ph ph-shopping-bag-open" style="font-size:18px;"></i><span>{{ app()->getLocale()==="ar"?"أضف للسلة":"Add to Cart" }}</span>';
                if(data.success){
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el){ el.textContent = data.count; });
                    showToast(data.message || '{{ app()->getLocale()==="ar"?"تمت الإضافة!":"Added to cart!" }}', 'success');
                } else {
                    showToast(data.message || '{{ app()->getLocale()==="ar"?"خطأ":"Error" }}', 'error');
                }
            })
            .catch(function(){
                addBtn.classList.remove('loading');
                addBtn.innerHTML = '<i class="ph ph-shopping-bag-open" style="font-size:18px;"></i><span>{{ app()->getLocale()==="ar"?"أضف للسلة":"Add to Cart" }}</span>';
                showToast('{{ app()->getLocale()==="ar"?"خطأ":"Error" }}', 'error');
            });
        });
    }
});
</script>
<style>
@keyframes spin { from{transform:rotate(0)} to{transform:rotate(360deg)} }
</style>
@endpush
