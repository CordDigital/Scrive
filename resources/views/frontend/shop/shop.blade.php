@extends('frontend.layouts.app')

@push('styles')
<style>
/* ── Breadcrumb tabs ── */
.filter-type .tab-item { font-size: 12px; font-weight: 700; letter-spacing: .8px; padding-bottom: 6px; color: #888; transition: color .2s; }
.filter-type .tab-item.active,
.filter-type .tab-item:hover { color: #000000; }
.filter-type .tab-item.active { border-bottom: 2px solid #fff; }

/* ── Product Card ── */
.shop-card { border-radius: 18px; overflow: hidden; background: #fff; border: 1px solid #f0f0f0; transition: box-shadow .25s, transform .25s; }
.shop-card:hover { box-shadow: 0 10px 36px rgba(0,0,0,.08); transform: translateY(-3px); }

/* ── Pagination ── */
.shop-pagination { display: flex; justify-content: center; margin-top: 40px; }

/* ── Empty ── */
.shop-empty { text-align: center; padding: 80px 0; }
.shop-empty i { font-size: 52px; color: #ddd; display: block; margin-bottom: 14px; }
.shop-empty p { font-size: 15px; color: #aaa; margin-bottom: 20px; }
</style>
@endpush

@section('content')
 <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Our Shop</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


<!-- Shop Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-12">
                <!-- Categories -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Filter by Category</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="text-dark {{ !request('category') ? 'font-weight-bold' : '' }}">
                                All Categories
                            </a>
                        </div>
                        @if(isset($categories))
                            @foreach($categories as $cat)
                                <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('category', 'page'), ['category' => $cat->name_en])) }}"
                                       class="text-dark {{ request('category') === $cat->name_en ? 'font-weight-bold' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                    <span class="badge border font-weight-normal">{{ $cat->products_count }}</span>
                                </div>
                                @if($cat->children->count())
                                    @foreach($cat->children as $child)
                                    <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3 ml-3">
                                        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('category', 'page'), ['category' => $child->name_en])) }}"
                                           class="text-dark {{ request('category') === $child->name_en ? 'font-weight-bold' : '' }}">
                                            {{ $child->name }}
                                        </a>
                                        <span class="badge border font-weight-normal">{{ $child->products_count }}</span>
                                    </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </form>
                </div>
                <!-- Categories End -->

                <!-- Price Start -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Filter by Price</h5>
                    <form action="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div class="form-group">
                            <label>Min Price</label>
                            <input type="number" name="min_price" class="form-control" placeholder="0" value="{{ request('min_price') }}">
                        </div>
                        <div class="form-group">
                            <label>Max Price</label>
                            <input type="number" name="max_price" class="form-control" placeholder="10000" value="{{ request('max_price') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Apply</button>
                    </form>
                </div>
                <!-- Price End -->

                <!-- Size Start -->
                <div class="mb-5">
                    <h5 class="font-weight-semi-bold mb-4">Filter by Size</h5>
                    @php $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free Size']; @endphp
                    <div class="d-flex flex-wrap">
                        @foreach($sizes as $size)
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('size', 'page'), ['size' => $size])) }}"
                               class="btn {{ request('size') === $size ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm m-1">
                                {{ $size }}
                            </a>
                        @endforeach
                        @if(request('size'))
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', request()->except('size', 'page')) }}"
                               class="btn btn-outline-danger btn-sm m-1">
                                <i class="fa fa-times"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
                <!-- Size End -->
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <span class="text-muted">{{ $products->total() }} products found</span>
                                @if(request('category'))
                                    <span class="badge badge-dark ml-2">{{ request('category') }}</span>
                                @endif
                                @if(request('size'))
                                    <span class="badge badge-dark ml-1">Size: {{ request('size') }}</span>
                                @endif
                            </div>
                            <div class="dropdown ml-4">
                                <select class="form-control" onchange="window.location.href=this.value">
                                    <option value="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('sort'), ['sort' => ''])) }}"
                                        {{ !request('sort') ? 'selected' : '' }}>Sort by Default</option>
                                    <option value="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('sort'), ['sort' => 'newArrivals'])) }}"
                                        {{ request('sort') === 'newArrivals' ? 'selected' : '' }}>New Arrivals</option>
                                    <option value="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('sort'), ['sort' => 'priceLowToHigh'])) }}"
                                        {{ request('sort') === 'priceLowToHigh' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('sort'), ['sort' => 'priceHighToLow'])) }}"
                                        {{ request('sort') === 'priceHighToLow' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', array_merge(request()->except('sort'), ['sort' => 'bestSelling'])) }}"
                                        {{ request('sort') === 'bestSelling' ? 'selected' : '' }}>Best Selling</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4" data-product-id="{{ $product->id }}">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}">
                                    <img class="img-fluid w-100" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                                </a>
                                @if($product->old_price)
                                    <span class="badge badge-danger position-absolute" style="top:10px;left:10px;">-{{ $product->discount_percent }}%</span>
                                @elseif($product->is_featured)
                                    <span class="badge badge-dark position-absolute" style="top:10px;left:10px;">New</span>
                                @endif
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}" class="text-dark">
                                        {{ $product->name }}
                                    </a>
                                </h6>
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
                    <div class="col-12">
                        <div class="shop-empty">
                            <i class="fas fa-shopping-bag"></i>
                            <p>No products found matching your criteria.</p>
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="btn btn-primary">View All Products</a>
                        </div>
                    </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($products->hasPages())
                    <div class="col-12 pb-1">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-3">
                                {{-- Previous --}}
                                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                {{-- Page numbers --}}
                                @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    <li class="page-item {{ $products->currentPage() === $page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                {{-- Next --}}
                                <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var locale = document.documentElement.lang || 'ar';

    // ============================================================
    // Toast
    // ============================================================
    function showToast(msg, type) {
        var t = document.getElementById('cart-toast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'cart-toast';
            t.style.cssText = 'position:fixed;right:24px;z-index:9999;padding:14px 20px;border-radius:14px;font-size:14px;font-weight:700;transition:opacity .3s;opacity:0;display:flex;align-items:center;gap:8px;color:#fff;';
            document.body.appendChild(t);
        }
        t.style.bottom = window.innerWidth < 640 ? '90px' : '28px';
        t.style.background = type === 'success' ? '#111' : '#ef4444';
        t.innerHTML = '<i class="fas fa-check-circle" style="font-size:18px;"></i> ' + msg;
        t.style.opacity = '1';
        clearTimeout(t._t);
        t._t = setTimeout(function() { t.style.opacity = '0'; }, 3000);
    }

    // ============================================================
    // Add to Cart
    // ============================================================
    function addToCart(productId, size, btn) {
        var url = locale === 'en' ? '/en/cart/add' : '/cart/add';
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
        .then(function(r) { return r.json(); })
        .then(function (data) {
            if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = ''; }
            if (data.success) {
                document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el) { el.textContent = data.count; });
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
    // Click handler
    // ============================================================
    document.addEventListener('click', function (e) {
        var cartBtn = e.target.closest('.add-cart-btn');
        if (cartBtn) {
            e.preventDefault();
            e.stopPropagation();
            var card = cartBtn.closest('[data-product-id]');
            if (!card) return;
            var productId = card.dataset.productId;
            addToCart(productId, '', cartBtn);
            return;
        }
    });
});
</script>
@endpush
