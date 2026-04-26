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
    <meta property="product:price:currency" content="EGP">
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
</style>
@endpush

@section('content')

   <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">{{ $product->name }}</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0"><a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}">Shop</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">{{ $product->name }}</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        {{-- Main product image --}}
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="object-fit: cover;">
                        </div>
                        {{-- Additional images --}}
                        @if($product->images->count())
                            @foreach($product->images as $img)
                            <div class="carousel-item">
                                <img class="w-100 h-100" src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}" style="object-fit: cover;">
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count())
                <div class="pd-thumbs mt-3">
                    <div class="pd-thumb active" onclick="$('#product-carousel').carousel(0)" style="cursor:pointer;">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    </div>
                    @foreach($product->images as $index => $img)
                    <div class="pd-thumb" onclick="$('#product-carousel').carousel({{ $index + 1 }})" style="cursor:pointer;">
                        <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="col-lg-7 pb-5">
                {{-- Category --}}
                @if($product->category)
                    <small class="text-muted text-uppercase">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', ['category' => $product->category->name_en]) }}" class="text-muted">
                            {{ $product->category->name }}
                        </a>
                    </small>
                @endif

                <h3 class="font-weight-semi-bold">{{ $product->name }}</h3>

                {{-- Brand --}}
                @if($product->brand)
                    <p class="text-muted mb-2">Brand: <strong>{{ $product->brand }}</strong></p>
                @endif

                {{-- Price --}}
                <div class="d-flex align-items-center mb-3">
                    <h3 class="font-weight-semi-bold mb-0">{{ number_format($product->price, 2) }} EGP</h3>
                    @if($product->old_price)
                        <h5 class="text-muted ml-3 mb-0"><del>{{ number_format($product->old_price, 2) }} EGP</del></h5>
                        <span class="badge badge-danger ml-2">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>

                {{-- Stock --}}
                <div class="mb-3">
                    @if($product->stock > 0)
                        <span class="text-success"><i class="fa fa-check-circle mr-1"></i> In Stock ({{ $product->stock }} available)</span>
                    @else
                        <span class="text-danger"><i class="fa fa-times-circle mr-1"></i> Out of Stock</span>
                    @endif
                </div>

                {{-- Description --}}
                @if($product->description)
                    <p class="mb-4">{!! nl2br(e(Str::limit($product->description, 300))) !!}</p>
                @endif

                {{-- Sizes --}}
                @if($product->sizes && count($product->sizes))
                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                    <div>
                        @foreach($product->sizes as $size)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="size-{{ $loop->index }}" name="size" value="{{ $size }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="custom-control-label" for="size-{{ $loop->index }}">{{ $size }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Colors --}}
                @if($product->colors && count($product->colors))
                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                    <div>
                        @foreach($product->colors as $color)
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="color-{{ $loop->index }}" name="color" value="{{ $color }}" {{ $loop->first ? 'checked' : '' }}>
                                <label class="custom-control-label" for="color-{{ $loop->index }}">{{ $color }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Add to Cart --}}
                <div class="d-flex align-items-center mb-4 pt-2" data-product-id="{{ $product->id }}">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus" type="button" onclick="changeQty(-1)">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control bg-secondary text-center" id="qty-input" value="1" min="1" max="{{ $product->stock ?? 99 }}">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus" type="button" onclick="changeQty(1)">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary px-3" id="add-to-cart-btn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <i class="fa fa-shopping-cart mr-1"></i> Add To Cart
                    </button>
                </div>

                {{-- Share --}}
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-dark px-2" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-dark px-2" href="https://wa.me/?text={{ urlencode($product->name . ' - ' . url()->current()) }}" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a class="text-dark px-2" href="https://www.pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ urlencode(asset('storage/' . $product->image)) }}&description={{ urlencode($product->name) }}" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs: Description / Info --}}
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    @if($product->video_url ?? false)
                        <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-video">Video</a>
                    @endif
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Product Description</h4>
                        <div class="mb-4">
                            {!! $product->description !!}
                        </div>
                    </div>
                    @if($product->video_url ?? false)
                    <div class="tab-pane fade" id="tab-pane-video">
                        <h4 class="mb-3">Product Video</h4>
                        <div class="embed-responsive embed-responsive-16by9 mb-4" style="max-width:720px;">
                            <iframe class="embed-responsive-item" src="{{ $product->video_url }}" allowfullscreen></iframe>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    {{-- ══════════════════════════════════════════════════════════════
         RELATED PRODUCTS — Dynamic from $related
    ══════════════════════════════════════════════════════════════════ --}}
    @if(isset($related) && $related->count())
    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    @foreach($related as $relProduct)
                    <div class="card product-item border-0">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $relProduct) }}">
                                <img class="img-fluid w-100" src="{{ asset('storage/' . $relProduct->image) }}" alt="{{ $relProduct->name }}">
                            </a>
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <h6 class="text-truncate mb-3">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $relProduct) }}" class="text-dark">
                                    {{ $relProduct->name }}
                                </a>
                            </h6>
                            <div class="d-flex justify-content-center">
                                <h6>{{ number_format($relProduct->price, 2) }} EGP</h6>
                                @if($relProduct->old_price)
                                    <h6 class="text-muted ml-2"><del>{{ number_format($relProduct->old_price, 2) }} EGP</del></h6>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $relProduct) }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                            <button class="btn btn-sm text-dark p-0 add-cart-btn" data-product-id="{{ $relProduct->id }}"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
    @endif

@endsection

@push('scripts')
<script>
var CSRF   = '{{ csrf_token() }}';
var LOCALE = '{{ app()->getLocale() }}';

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
   Add to Cart
══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function(){
    var addBtn = document.getElementById('add-to-cart-btn');
    if(addBtn){
        addBtn.addEventListener('click', function(){
            var productId = '{{ $product->id }}';
            var qty       = parseInt(document.getElementById('qty-input').value) || 1;
            var sizeEl    = document.querySelector('input[name="size"]:checked');
            var colorEl   = document.querySelector('input[name="color"]:checked');
            var size      = sizeEl ? sizeEl.value : '';
            var color     = colorEl ? colorEl.value : '';
            var url       = LOCALE === 'en' ? '/en/cart/add' : '/cart/add';

            addBtn.disabled = true;
            addBtn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Adding...';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, size: size, color: color, quantity: qty })
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fa fa-shopping-cart mr-1"></i> Add To Cart';
                if(data.success){
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el){ el.textContent = data.count; });
                    if(typeof showCartPopup === 'function') showCartPopup();
                }
            })
            .catch(function(){
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fa fa-shopping-cart mr-1"></i> Add To Cart';
            });
        });
    }

    // Global add-to-cart for related products
    document.addEventListener('click', function(e){
        var cartBtn = e.target.closest('.add-cart-btn');
        if(cartBtn){
            e.preventDefault();
            var card = cartBtn.closest('[data-product-id]');
            var pid  = card ? card.dataset.productId : cartBtn.dataset.productId;
            if(!pid) return;
            var url = LOCALE === 'en' ? '/en/cart/add' : '/cart/add';
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ product_id: pid, size: '', quantity: 1 })
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if(data.success){
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el){ el.textContent = data.count; });
                    if(typeof showCartPopup === 'function') showCartPopup();
                }
            });
        }
    });
});
</script>
@endpush
