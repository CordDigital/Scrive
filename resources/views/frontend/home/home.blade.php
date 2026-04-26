@extends('frontend.layouts.app')
@section('content')

{{-- ══════════════════════════════════════════════════════════════
     CAROUSEL — Dynamic from $sliders
══════════════════════════════════════════════════════════════════ --}}
@if(isset($sliders) && $sliders->count())
<div id="header-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="height: 410px;">
            <img class="img-fluid" src="{{ Storage::url($slider->image) }}" alt="{{ $slider->title ?? 'Slide ' . ($index+1) }}">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <div class="p-3" style="max-width: 700px;">
                    @if($slider->subtitle)
                        <h4 class="text-light text-uppercase font-weight-medium mb-3">{{ $slider->subtitle }}</h4>
                    @endif
                    @if($slider->title)
                        <h3 class="display-4 text-white font-weight-semi-bold mb-4">{{ $slider->title }}</h3>
                    @endif
                    @if($slider->button_text && $slider->button_url)
                        <a href="{{ $slider->button_url }}" class="btn btn-light py-2 px-3">{{ $slider->button_text }}</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-prev-icon mb-n2"></span>
        </div>
    </a>
    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-next-icon mb-n2"></span>
        </div>
    </a>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════
     BENEFITS — Dynamic from $benefits
══════════════════════════════════════════════════════════════════ --}}
@if(isset($benefits) && $benefits->count())
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        @foreach($benefits as $benefit)
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="{{ $benefit->icon ?? 'fa fa-check' }} text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">{{ app()->getLocale() === 'ar' ? $benefit->title_ar : $benefit->title_en }}</h5>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<!-- Featured Start (fallback) -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Featured End -->


{{-- ══════════════════════════════════════════════════════════════
     CATEGORIES — Dynamic from $categories
══════════════════════════════════════════════════════════════════ --}}
@if(isset($categories) && $categories->count())
<!-- Categories Start -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        @foreach($categories as $cat)
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                @php
                    $childIds = $cat->children->pluck('id')->toArray();
                    $allIds = array_merge([$cat->id], $childIds);
                    $productCount = \App\Models\Product::whereIn('category_id', $allIds)->where('is_active', true)->count();
                @endphp
                <p class="text-right">{{ $productCount }} Products</p>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', ['category' => $cat->name_en]) }}" class="cat-img position-relative overflow-hidden mb-3">
                    @if($cat->image)
                        <img class="img-fluid" src="{{ Storage::url($cat->image) }}" alt="{{ $cat->name }}">
                    @else
                        <img class="img-fluid" src="{{ asset('img/cat-1.jpg') }}" alt="{{ $cat->name }}">
                    @endif
                </a>
                <h5 class="font-weight-semi-bold m-0">{{ $cat->name }}</h5>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- Categories End -->
@endif


{{-- ══════════════════════════════════════════════════════════════
     FLASH SALE — Dynamic from $flashSale
══════════════════════════════════════════════════════════════════ --}}
@if(isset($flashSale) && $flashSale)
<!-- Offer Start -->
<div class="container-fluid offer pt-5">
    <div class="row px-xl-5">
        <div class="col-md-6 pb-4">
            <div class="position-relative bg-secondary text-center text-md-right text-white mb-2 py-5 px-5">
                @if($flashSale->image)
                    <img src="{{ Storage::url($flashSale->image) }}" alt="{{ $flashSale->title ?? 'Flash Sale' }}">
                @endif
                <div class="position-relative" style="z-index: 1;">
                    <h5 class="text-uppercase text-primary mb-3">{{ $flashSale->subtitle ?? 'Limited Time Offer' }}</h5>
                    <h1 class="mb-4 font-weight-semi-bold">{{ $flashSale->title ?? 'Flash Sale' }}</h1>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="btn btn-outline-primary py-md-2 px-md-3">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 pb-4">
            <div class="position-relative bg-secondary text-center text-md-left text-white mb-2 py-5 px-5">
                <div class="position-relative" style="z-index: 1;">
                    <h5 class="text-uppercase text-primary mb-3">Flash Sale Ends In</h5>
                    <div class="countdown-time d-flex justify-content-center" data-ends="{{ $flashSale->ends_at->toISOString() }}">
                        <div class="text-center mx-2">
                            <h2 class="font-weight-bold countdown-day">0</h2>
                            <small>Days</small>
                        </div>
                        <div class="text-center mx-2">
                            <h2 class="font-weight-bold countdown-hour">00</h2>
                            <small>Hours</small>
                        </div>
                        <div class="text-center mx-2">
                            <h2 class="font-weight-bold countdown-minute">00</h2>
                            <small>Mins</small>
                        </div>
                        <div class="text-center mx-2">
                            <h2 class="font-weight-bold countdown-second">00</h2>
                            <small>Secs</small>
                        </div>
                    </div>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="btn btn-outline-primary py-md-2 px-md-3 mt-3">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Offer End -->
@endif


{{-- ══════════════════════════════════════════════════════════════
     WHAT'S NEW / PRODUCTS BY CATEGORY — Dynamic from $productsByCategory
══════════════════════════════════════════════════════════════════ --}}
@if(isset($categories) && $categories->count() && isset($productsByCategory))
<!-- Products Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Trendy Products</span></h2>
    </div>

    {{-- Category Tabs --}}
    <div class="text-center mb-4">
        @foreach($categories as $index => $cat)
            <button class="btn {{ $index === 0 ? 'btn-dark' : 'btn-outline-dark' }} m-1 category-filter-btn" data-category="{{ $cat->id }}">
                {{ $cat->name }}
            </button>
        @endforeach
    </div>

    {{-- Products grid for each category --}}
    @foreach($categories as $index => $cat)
    <div class="row px-xl-5 pb-3 category-products" data-category="{{ $cat->id }}" style="{{ $index !== 0 ? 'display:none;' : '' }}">
        @forelse($productsByCategory[$cat->id] ?? collect() as $product)
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="card product-item border-0 mb-4" data-product-id="{{ $product->id }}">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">{{ $product->name }}</h6>
                    <div class="d-flex justify-content-center">
                        <h6>{{ number_format($product->price, 2) }} EGP</h6>
                        @if($product->old_price)
                            <h6 class="text-muted ml-2"><del>{{ number_format($product->old_price, 2) }} EGP</del></h6>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                    <button class="btn btn-sm text-dark p-0 add-cart-btn" data-product-id="{{ $product->id }}"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No products in this category yet.</p>
        </div>
        @endforelse
    </div>
    @endforeach
</div>
<!-- Products End -->
@endif


{{-- ══════════════════════════════════════════════════════════════
     NEWSLETTER SUBSCRIBE
══════════════════════════════════════════════════════════════════ --}}
<!-- Subscribe Start -->
<div class="container-fluid bg-secondary my-5">
    <div class="row justify-content-md-center py-5 px-xl-5">
        <div class="col-md-6 col-12 py-5">
            <div class="text-center mb-2 pb-2">
                <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Stay Updated</span></h2>
                <p>Subscribe to our newsletter to get the latest updates on new products and offers.</p>
            </div>
            @if(session('newsletter_success'))
                <div class="alert alert-success text-center">{{ session('newsletter_success') }}</div>
            @endif
            <form action="{{ route(app()->getLocale() === 'ar' ? 'newsletter.subscribe' : 'en.newsletter.subscribe') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="email" name="email" class="form-control border-white p-4" placeholder="Email Goes Here" required>
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4" type="submit">Subscribe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Subscribe End -->


{{-- ══════════════════════════════════════════════════════════════
     BEST SELLERS — Dynamic from $bestSellers
══════════════════════════════════════════════════════════════════ --}}
@if(isset($bestSellers) && $bestSellers->count())
<!-- Best Sellers Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Best Sellers</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        @foreach($bestSellers as $product)
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="card product-item border-0 mb-4" data-product-id="{{ $product->id }}">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    @if($product->old_price)
                        <span class="badge badge-danger position-absolute" style="top:10px;left:10px;">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3">{{ $product->name }}</h6>
                    <div class="d-flex justify-content-center">
                        <h6>{{ number_format($product->price, 2) }} EGP</h6>
                        @if($product->old_price)
                            <h6 class="text-muted ml-2"><del>{{ number_format($product->old_price, 2) }} EGP</del></h6>
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                    <button class="btn btn-sm text-dark p-0 add-cart-btn" data-product-id="{{ $product->id }}"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- Best Sellers End -->
@endif


{{-- ══════════════════════════════════════════════════════════════
     TESTIMONIALS — Dynamic from $testimonials
══════════════════════════════════════════════════════════════════ --}}
@if(isset($testimonials) && $testimonials->count())
<!-- Testimonials Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">What Our Customers Say</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel testimonial-carousel">
                @foreach($testimonials as $testimonial)
                <div class="testimonial-item text-center border p-4">
                    @if($testimonial->image)
                        <img class="img-fluid mx-auto mb-3" src="{{ Storage::url($testimonial->image) }}" alt="{{ $testimonial->name }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
                    @endif
                    <p class="mb-3">{{ $testimonial->content }}</p>
                    <h5 class="font-weight-semi-bold">{{ $testimonial->name }}</h5>
                    @if($testimonial->title)
                        <span class="text-muted">{{ $testimonial->title }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Testimonials End -->
@endif


{{-- ══════════════════════════════════════════════════════════════
     LATEST BLOGS — Dynamic from $latestBlogs
══════════════════════════════════════════════════════════════════ --}}
@if(isset($latestBlogs) && $latestBlogs->count())
<!-- Blog Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Latest From Blog</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        @foreach($latestBlogs as $blog)
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="card border-0 mb-4">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $blog) }}">
                    <img class="card-img-top" src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}" style="height: 250px; object-fit: cover;">
                </a>
                <div class="card-body border-left border-right p-4">
                    <small class="text-muted">
                        <i class="fa fa-calendar-alt mr-1"></i>{{ $blog->published_at?->format('M d, Y') }}
                    </small>
                    <h5 class="font-weight-semi-bold mt-2 mb-2">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $blog) }}" class="text-dark">
                            {{ Str::limit($blog->title, 50) }}
                        </a>
                    </h5>
                    <p class="text-muted">{{ Str::limit(strip_tags($blog->content), 100) }}</p>
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'blog.show' : 'en.blog.show', $blog) }}" class="text-primary font-weight-semi-bold">Read More <i class="fa fa-angle-right ml-1"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- Blog End -->
@endif


{{-- ══════════════════════════════════════════════════════════════
     INSTAGRAM — Dynamic from $InstagramImages
══════════════════════════════════════════════════════════════════ --}}
@if(isset($InstagramImages) && $InstagramImages->count())
<!-- Vendor/Instagram Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Follow Us On Instagram</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel vendor-carousel">
                @foreach($InstagramImages as $image)
                <div class="vendor-item border p-4">
                    <a href="{{ $image->url ?? 'https://www.instagram.com/' }}" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="Instagram" style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Vendor/Instagram End -->
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // Category Tab Filter
    // ============================================================
    document.querySelectorAll('.category-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var catId = this.dataset.category;

            // Toggle button styles
            document.querySelectorAll('.category-filter-btn').forEach(function(b) {
                b.classList.remove('btn-dark');
                b.classList.add('btn-outline-dark');
            });
            this.classList.remove('btn-outline-dark');
            this.classList.add('btn-dark');

            // Toggle product grids
            document.querySelectorAll('.category-products').forEach(function(grid) {
                grid.style.display = grid.dataset.category === catId ? '' : 'none';
            });
        });
    });

    // ============================================================
    // Flash Sale Countdown
    // ============================================================
    var countdown = document.querySelector('.countdown-time[data-ends]');
    if (countdown) {
        var ends = new Date(countdown.getAttribute('data-ends')).getTime();
        function updateCountdown() {
            var diff = ends - Date.now();
            if (diff <= 0) {
                countdown.querySelectorAll('h2').forEach(function(el) { el.textContent = '0'; });
                return;
            }
            var day = countdown.querySelector('.countdown-day');
            var hour = countdown.querySelector('.countdown-hour');
            var minute = countdown.querySelector('.countdown-minute');
            var second = countdown.querySelector('.countdown-second');
            if (day) day.textContent = Math.floor(diff / 86400000);
            if (hour) hour.textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            if (minute) minute.textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            if (second) second.textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // ============================================================
    // Add to Cart (global handler)
    // ============================================================
    document.addEventListener('click', function (e) {
        var addCartBtn = e.target.closest('.add-cart-btn');
        if (addCartBtn) {
            e.preventDefault();
            e.stopPropagation();
            var card = addCartBtn.closest('[data-product-id]');
            var productId = card ? card.dataset.productId : addCartBtn.dataset.productId;
            if (!productId) return;
            addToCart(productId, '', '', 1, addCartBtn);
            return;
        }
    });

});

function showToast(msg, type) {
    type = type || 'success';
    var t = document.getElementById('cart-toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'cart-toast';
        t.style.cssText = 'position:fixed;right:24px;z-index:9999;padding:14px 20px;border-radius:14px;font-size:14px;font-weight:700;transition:opacity .3s;opacity:0;display:flex;align-items:center;gap:8px;color:#fff;';
        document.body.appendChild(t);
    }
    t.style.bottom = window.innerWidth < 640 ? '90px' : '28px';
    t.style.background = type === 'success' ? '#111' : '#ef4444';
    t.innerHTML = '<i class="ph-bold ph-' + (type === 'success' ? 'check-circle' : 'warning-circle') + '" style="font-size:18px;"></i> ' + msg;
    t.style.opacity = '1';
    clearTimeout(t._t);
    t._t = setTimeout(function(){ t.style.opacity = '0'; }, 3000);
}

function addToCart(productId, size, color, qty, btn) {
    var locale = document.documentElement.lang || 'ar';
    var url = locale === 'en' ? '/en/cart/add' : '/cart/add';
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ product_id: productId, size: size, color: color, quantity: qty })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el) { el.textContent = data.count; });
            showToast(data.message || (locale === 'en' ? 'Added to cart!' : 'تمت الإضافة!'), 'success');
        }
    });
}
</script>
@endpush
