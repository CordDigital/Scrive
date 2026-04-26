@extends('frontend.layouts.app')
@section('content')

<div class="slider-block style-one bg-linear xl:h-[860px] lg:h-[800px] md:h-[580px] sm:h-[500px] h-[350px] max-[420px]:h-[320px] w-full">
    <div class="slider-main h-full w-full">
        <div class="swiper swiper-slider h-full relative">
            <div class="swiper-wrapper">
                @foreach ($sliders as $slider)
                    <div class="swiper-slide">
                        <div class="slider-item h-full w-full relative">
                            <div class="container w-full h-full flex items-center relative">
                                <div class="text-content basis-1/2">
                                    <div class="text-sub-display text-lg text-black">
                                        {{ app()->getLocale() === 'ar' ? $slider->subtitle_ar : $slider->subtitle_en }}
                                    </div>
                                    <div class="text-display md:mt-5 mt-2 text-4xl md:text-6xl font-bold text-black">
                                        {{ app()->getLocale() === 'ar' ? $slider->title_ar : $slider->title_en }}
                                    </div>
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
                                       class="button-main md:mt-8 mt-3 px-6 py-3 bg-white text-black rounded-full hover:bg-black hover:text-white transition" style="border: 1px solid;">
                                        {{ __('Shop Now') }}
                                    </a>
                                </div>
                                <div class="sub-img absolute sm:w-1/2 w-3/5 2xl:-right-[60px] -right-[16px] bottom-0">
                                    @if($loop->first)
                                        <img src="{{ Storage::url($slider->image) }}"
                                             alt="{{ app()->getLocale() === 'ar' ? $slider->title_ar : $slider->title_en }}"
                                             class="w-full h-auto object-contain"
                                             fetchpriority="high"
                                             loading="eager"
                                             decoding="sync" />
                                    @else
                                        <img src="{{ Storage::url($slider->image) }}"
                                             alt="{{ app()->getLocale() === 'ar' ? $slider->title_ar : $slider->title_en }}"
                                             class="w-full h-auto object-contain"
                                             loading="lazy"
                                             decoding="async" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>

<div class="what-new-block filter-product-block md:pt-20 pt-10">
    <div class="container">
        <div class="heading flex flex-col items-center text-center">
            <div class="heading3">{{ __("What's new") }}</div>
            <div class="menu-tab mt-6 flex justify-center">
                <div class="menu inline-flex items-center gap-2 p-1 bg-surface rounded-full relative overflow-x-auto whitespace-nowrap max-w-full" style="-webkit-overflow-scrolling:touch; -ms-overflow-style:none; scrollbar-width:none;">
                    <div class="indicator absolute top-1 bottom-1 bg-white rounded-full shadow-md duration-300"></div>
                    @foreach ($categories as $category)
                        <div class="tab-item relative text-secondary text-button-uppercase py-2 px-5 cursor-pointer duration-300 hover:text-black {{ $loop->first ? 'active' : '' }}"
                            data-item="{{ $category->id }}">
                            {{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="list-product hide-product-sold relative md:mt-10 mt-6">
            <div class="flex items-center gap-3">
                <button class="whats-new-prev shrink-0 w-10 h-10 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white transition-all">
                    <i class="ph ph-caret-left"></i>
                </button>
                <div class="swiper swiper-whats-new overflow-hidden flex-1">
                    <div class="swiper-wrapper">
                        @foreach ($categories as $category)
                            @foreach ($productsByCategory[$category->id] ?? [] as $product)
                                <div class="swiper-slide product-slide" data-category="{{ $category->id }}">
                                    @include('frontend.partials.product-card', [
                                        'product'    => $product,
                                        'categoryId' => $category->id,
                                        'first'      => $loop->parent->first,
                                    ])
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <button class="whats-new-next shrink-0 w-10 h-10 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white transition-all">
                    <i class="ph ph-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="collection-block md:pt-20 pt-10">
    <div class="container">
        <div class="heading3 text-center">{{ __('Explore Collections') }}</div>
    </div>
    <div class="list-collection relative section-swiper-navigation md:mt-10 mt-6 sm:px-5 px-4">
        <div class="swiper-button-prev lg:left-10 left-6" tabindex="0" role="button"></div>
        <div class="swiper swiper-collection h-full relative">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                    @foreach ($category->children as $child)
                        <div class="swiper-slide" role="group">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($child->name_en) }}"
                                class="collection-item block relative rounded-2xl overflow-hidden cursor-pointer">
                                <div class="bg-img">
                                    @if($child->image)
                                        <img src="{{ asset('storage/' . $child->image) }}"
                                            alt="{{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                            decoding="async">
                                    @else
                                        <div class="w-full h-full" style="background:linear-gradient(135deg,#e5e7eb,#f9fafb);"></div>
                                    @endif
                                </div>
                                <div class="collection-name heading5 text-center sm:bottom-8 bottom-4 lg:w-[200px] md:w-[160px] w-[100px] md:py-3 py-1.5 bg-white rounded-xl duration-500">
                                    {{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
        <div class="swiper-button-next lg:right-10 right-6" tabindex="0" role="button"></div>
    </div>
</div>

@php
    $bsPrices = $bestSellers->pluck('price');
    $bsMin    = $bsPrices->min() ?? 0;
    $bsMax    = $bsPrices->max() ?? 0;
    $bsRange  = ($bsMax - $bsMin) > 0 ? ($bsMax - $bsMin) / 3 : 1;
    $bsLowThr = $bsMin + $bsRange;
    $bsMidThr = $bsMin + 2 * $bsRange;
@endphp
<div class="best-sellers-block filter-product-block md:pt-20 pt-10">
    <div class="container">
        <div class="heading flex flex-col items-center text-center">
            <div class="heading3">{{ __('Best Sellers') }}</div>
        </div>

        <div class="filter-tabs flex items-center justify-center gap-3 flex-wrap md:mt-8 mt-5">
            <button class="bs-filter-btn active text-button-uppercase px-6 py-2 rounded-full border border-black bg-black text-white duration-300" data-filter="all">
                {{ __('All') }}
            </button>
            <button class="bs-filter-btn text-button-uppercase px-6 py-2 rounded-full border border-line duration-300 hover:bg-black hover:text-white hover:border-black" data-filter="low">
                {{ __('Low Price') }}
            </button>
            <button class="bs-filter-btn text-button-uppercase px-6 py-2 rounded-full border border-line duration-300 hover:bg-black hover:text-white hover:border-black" data-filter="medium">
                {{ __('Medium Price') }}
            </button>

        </div>

        <div class="list-product relative md:mt-10 mt-6">
            <div class="flex items-center gap-3">
                <button class="bs-prev shrink-0 w-10 h-10 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white transition-all">
                    <i class="ph ph-caret-left"></i>
                </button>
                <div class="swiper swiper-best-sellers overflow-hidden flex-1">
                    <div class="swiper-wrapper">
                        @foreach ($bestSellers as $product)
                            @php
                                $priceRange = $product->price <= $bsLowThr ? 'low'
                                    : ($product->price <= $bsMidThr ? 'medium' : 'high');
                            @endphp
                            <div class="swiper-slide bs-item" data-price-range="{{ $priceRange }}">
                                @include('frontend.partials.product-card', [
                                    'product'    => $product,
                                    'categoryId' => null,
                                    'first'      => true,
                                    'priceRange' => $priceRange,
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="bs-next shrink-0 w-10 h-10 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white transition-all">
                    <i class="ph ph-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@if($flashSale)
<div class="container md:pt-20 pt-10">
    <div class="flash-sale-block bg-surface flex items-center justify-end max-sm:justify-center relative overflow-hidden rounded-3xl w-full">
        <div class="bg-img w-1/2 absolute left-0 lg:-top-28 sm:-top-14 max-sm:hidden" style="top: -5rem; transform: scale(0.7);">
            <img src="{{ $flashSale->image ? asset('storage/' . $flashSale->image) : asset('assets/images/banneer - Copy.png') }}"
                 alt="flash-sale"
                 class="w-full h-full object-cover"
                 loading="lazy"
                 decoding="async">
        </div>
        <div class="text-content basis-1/2 flex flex-col items-center text-center px-8 lg:py-24 md:py-16 py-10" style="padding-bottom: 4rem;">
            <div class="heading2">{{ app()->getLocale() === 'ar' ? $flashSale->title_ar : $flashSale->title_en }}</div>
            <div class="body1 mt-3">{{ app()->getLocale() === 'ar' ? $flashSale->subtitle_ar : $flashSale->subtitle_en }}</div>
            <div class="countdown-time flex items-center gap-5 max-sm:gap-[14px] lg:mt-9 md:mt-6 mt-4"
                 data-ends="{{ $flashSale->ends_at->toIso8601String() }}">
                <div class="item flex flex-col items-center">
                    <div class="countdown-day time heading1">0</div>
                    <div class="text-button-uppercase font-medium">{{ __('Days') }}</div>
                </div>
                <span class="heading4">:</span>
                <div class="item flex flex-col items-center">
                    <div class="countdown-hour time heading1">0</div>
                    <div class="text-button-uppercase font-medium">{{ __('Hours') }}</div>
                </div>
                <span class="heading4">:</span>
                <div class="item flex flex-col items-center">
                    <div class="countdown-minute time heading1">0</div>
                    <div class="text-button-uppercase font-medium">{{ __('Minutes') }}</div>
                </div>
                <span class="heading4">:</span>
                <div class="item flex flex-col items-center">
                    <div class="countdown-second time heading1">0</div>
                    <div class="text-button-uppercase font-medium">{{ __('Seconds') }}</div>
                </div>
            </div>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="button-main lg:mt-9 md:mt-6 mt-4">{{ __('Get it now') }}</a>
        </div>
    </div>
</div>
@endif
  <!--<div class="container mt-5">-->
  <!--      <div class="row">-->
  <!--          <div class="col-md-12">-->
  <!--          @php $indexRoute = app()->getLocale() == 'en' ? 'en.zizi-world.index' : 'zizi-world.index'; @endphp-->

  <!--              <a href="{{ route($indexRoute) }}">-->
  <!--                  <img src="assets/images/banner-zizi.webp"-->
  <!--                       alt="banner-sale"-->
  <!--                       class="w-full h-full object-cover">-->
                         
  <!--              </a>-->
                

  <!--          </div>-->
  <!--      </div>-->
  <!--      </div>-->
        
        
        
        <div class="banner-block style-one grid gap-5 md:pt-20 pt-10">
             @php $indexRoute = app()->getLocale() == 'en' ? 'en.zizi-world.index' : 'zizi-world.index'; @endphp
    <a href="{{ route($indexRoute) }}"" class="banner-item relative block overflow-hidden duration-500">
        <div class="banner-img">
            <img src="assets/images/banner-zizi.webp"
                 class="duration-1000 w-full h-full object-cover" alt="Best Sellers"
                 loading="lazy" decoding="async" />
        </div>
        <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
            <div class="heading2 text-white">{{ __('Zizi World') }}</div>
          
        </div>
    </a>
   
</div>

<div class="banner-block style-one grid sm:grid-cols-2 gap-5 md:pt-20 pt-10">
    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?sort=bestSelling" class="banner-item relative block overflow-hidden duration-500">
        <div class="banner-img">
            <img src="{{ asset('assets/images/best-sellers,.jpg') }}"
                 class="duration-1000" alt="Best Sellers"
                 loading="lazy" decoding="async" />
        </div>
        <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
            <div class="heading2 text-white">{{ __('Best Sellers') }}</div>
            <div class="text-button text-white relative inline-block pb-1 border-b-2 border-white duration-500 mt-2">{{ __('Shop Now') }}</div>
        </div>
    </a>
    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?sort=newArrivals" class="banner-item relative block overflow-hidden duration-500">
        <div class="banner-img">
            <img src="{{ asset('assets/images/new-arrivals.jpg') }}"
                 class="duration-1000" alt="New Arrivals"
                 loading="lazy" decoding="async" />
        </div>
        <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
            <div class="heading2 text-white">{{ __('New Arrivals') }}</div>
            <div class="text-button text-white relative inline-block pb-1 border-b-2 border-white duration-500 mt-2">{{ __('Shop Now') }}</div>
        </div>
    </a>
</div>

<div class="benefit-block md:pt-20 pt-10">
    <div class="container">
        <div class="list-benefit grid items-start lg:grid-cols-4 grid-cols-2 gap-[30px]">
            @foreach ($benefits as $benefit)
                <div class="benefit-item flex flex-col items-center justify-center">
                    <i class="{{ $benefit->icon }} text-5xl"></i>
                    <div class="heading6 text-center mt-5">{{ app()->getLocale() === 'ar' ? $benefit->title_ar : $benefit->title_en }}</div>
                    <div class="caption1 text-secondary text-center mt-3">{{ app()->getLocale() === 'ar' ? $benefit->description_ar : $benefit->description_en }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

      









<div class="testimonial-block md:pt-20 md:pb-16 pt-10 pb-8 md:mt-20 mt-10 bg-surface">
    <div class="container">
        <div class="heading3 text-center">{{ __('What People Are Saying') }}</div>
        <div class="list-testimonial pagination-mt40 md:mt-10 mt-6">
            <div class="swiper swiper-list-testimonial h-full relative">
                <div class="swiper-wrapper">
                    @forelse($testimonials as $t)
                        <div class="swiper-slide">
                            <div class="testimonial-item style-one h-full">
                                <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="ph{{ $i <= $t->rating ? '-fill' : '' }} ph-star text-yellow"></i>
                                        @endfor
                                    </div>
                                    <div class="heading6 title mt-4">{{ app()->getLocale() === 'ar' ? $t->title_ar : $t->title_en }}</div>
                                    <div class="desc mt-2">{!! app()->getLocale() === 'ar' ? $t->content_ar : $t->content_en !!}</div>
                                    <div class="text-button name mt-4">{{ $t->name }}</div>
                                    <div class="caption2 date text-secondary2 mt-1">{{ \Carbon\Carbon::parse($t->review_date)->format('F d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="testimonial-item style-one h-full">
                                <div class="testimonial-main bg-white p-8 rounded-2xl h-full">
                                    <div class="flex items-center gap-1">
                                        @for($i = 0; $i < 5; $i++)<i class="ph-fill ph-star text-yellow"></i>@endfor
                                    </div>
                                    <div class="heading6 title mt-4">{{ __('Excellent Service!') }}</div>
                                    <div class="desc mt-2">"{{ __('Great products and amazing quality.') }}"</div>
                                    <div class="text-button name mt-4">Customer</div>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="flex items-center justify-center gap-4 mt-6 new-55">
                <button class="testimonial-prev w-10 h-10 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white transition-all">
                    <i class="ph ph-caret-left"></i>
                </button>
                <button class="testimonial-next w-10 h-10 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white transition-all">
                    <i class="ph ph-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="instagram-block md:pt-20 pt-10">
    <div class="container">
        <div class="heading">
            <div class="heading3 text-center">{{ __('ZIZI ABUSALLA On Instagram') }}</div>
            <div class="text-center mt-3">{{ __('ZiziAbusalla') }}</div>
        </div>
        <div class="list-instagram md:mt-10 mt-6">
            <div class="swiper swiper-list-instagram">
                <div class="swiper-wrapper" style="    justify-content: space-between;">
                    @foreach ($InstagramImages as $image)
                        <div class="swiper-slide">
                            <a href="{{ $image->url ?? 'https://www.instagram.com/' }}" target="_blank"
                                class="item relative block sm:rounded-[32px] rounded-2xl overflow-hidden aspect-square">
                                <img src="{{ asset('storage/' . $image->image) }}"
                                     alt="Instagram"
                                     class="h-full w-full object-cover duration-500 relative"
                                     loading="lazy"
                                     decoding="async" />
                                <div class="icon sm:w-12 sm:h-12 w-8 h-8 bg-white hover:bg-black duration-500 flex items-center justify-center sm:rounded-2xl rounded-xl absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[1]">
                                    <i class="fa-brands fa-instagram"></i>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // Swiper Initializers
    // ============================================================
    function initWhatsNewSwiper() {
        return new Swiper('.swiper-whats-new', {
            slidesPerView: 2,
            spaceBetween: 16,
            navigation: {
                nextEl: '.whats-new-next',
                prevEl: '.whats-new-prev',
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: { slidesPerView: 4, spaceBetween: 30 },
            },
        });
    }

    function initBestSellersSwiper() {
        return new Swiper('.swiper-best-sellers', {
            slidesPerView: 2,
            spaceBetween: 16,
            navigation: {
                nextEl: '.bs-next',
                prevEl: '.bs-prev',
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: { slidesPerView: 4, spaceBetween: 30 },
            },
        });
    }

    // ============================================================
    // What's New Tab Filter Logic
    // ============================================================
    document.querySelectorAll('.what-new-block').forEach(function (block) {
        const tabs = block.querySelectorAll('.tab-item');
        const indicator = block.querySelector('.indicator');
        const swiperWrapper = block.querySelector('.swiper-whats-new .swiper-wrapper');


        block.originalSlides = Array.from(swiperWrapper.querySelectorAll('.product-slide'));

        function moveIndicator(activeTab) {
            if (!indicator || !activeTab) return;
            const menu = activeTab.closest('.menu');
            const menuRect = menu.getBoundingClientRect();
            const tabRect = activeTab.getBoundingClientRect();
            indicator.style.width = tabRect.width + 'px';
            indicator.style.left = (tabRect.left - menuRect.left) + 'px';
        }

        function filterSlides(categoryId) {
            if (window.whatsNewSwiperInstance) {
                window.whatsNewSwiperInstance.destroy(true, true);
                swiperWrapper.innerHTML = '';
            }

            const filteredSlides = block.originalSlides.filter(slide => {
                return slide.getAttribute('data-category') === categoryId;
            });

            filteredSlides.forEach(slide => {
                swiperWrapper.appendChild(slide);
            });

            setTimeout(function () {
                window.whatsNewSwiperInstance = initWhatsNewSwiper();
            }, 50);
        }

        const firstTab = block.querySelector('.tab-item.active');
        if (firstTab) {
            setTimeout(() => moveIndicator(firstTab), 50);
            filterSlides(firstTab.getAttribute('data-item'));
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                moveIndicator(this);
                filterSlides(this.getAttribute('data-item'));
            });
        });
    });

    // ============================================================
    // Best Sellers Filter Logic
    // ============================================================
    const filterBtns = document.querySelectorAll('.bs-filter-btn');
    window.bestSellersSwiperInstance = initBestSellersSwiper();

    filterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => {
                b.classList.remove('active', 'bg-black', 'text-white', 'border-black');
                b.classList.add('border-line');
            });
            this.classList.add('active', 'bg-black', 'text-white', 'border-black');
            this.classList.remove('border-line');

            const filter = this.dataset.filter;

            document.querySelectorAll('.swiper-best-sellers .bs-item').forEach(function (item) {
                const show = (filter === 'all' || item.dataset.priceRange === filter);
                if (show) {
                    item.style.display = '';
                    item.classList.remove('swiper-slide-hidden');
                } else {
                    item.style.display = 'none';
                    item.classList.add('swiper-slide-hidden');
                }
            });

            if (window.bestSellersSwiperInstance) window.bestSellersSwiperInstance.destroy(true, true);
            setTimeout(() => { window.bestSellersSwiperInstance = initBestSellersSwiper(); }, 50);
        });
    });

    // ============================================================
    // Flash Sale Countdown
    // ============================================================
    const countdown = document.querySelector('.countdown-time[data-ends]');
    if (countdown) {
        const ends = new Date(countdown.getAttribute('data-ends')).getTime();
        function updateCountdown() {
            const diff = ends - Date.now();
            if (diff <= 0) {
                countdown.querySelectorAll('.time').forEach(el => el.textContent = '0');
                return;
            }
            countdown.querySelector('.countdown-day').textContent = Math.floor(diff / 86400000);
            countdown.querySelector('.countdown-hour').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            countdown.querySelector('.countdown-minute').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            countdown.querySelector('.countdown-second').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // ============================================================
    // Global Click Handler (Add to Cart, etc.)
    // ============================================================
    document.addEventListener('click', function (e) {
        // Quick Shop Toggle
        const quickShopBtn = e.target.closest('.quick-shop-btn');
        if (quickShopBtn) {
            const block = quickShopBtn.closest('.product-thumb').querySelector('.quick-shop-block');
            if (block) block.style.display = block.style.display === 'block' ? 'none' : 'block';
            return;
        }

        // Add to Cart Logic
        const addCartBtn = e.target.closest('.add-cart-btn');
        if (addCartBtn) {
            const card = addCartBtn.closest('.product-main');
            if (!card) return;
            const productId = card.getAttribute('data-product-id');
            const sizeEl = addCartBtn.closest('.quick-shop-block')?.querySelector('.size-item.active');
            addToCart(productId, sizeEl ? sizeEl.textContent.trim() : '', '', 1, addCartBtn);
            return;
        }

        // Size Selection Logic
        const sizeItem = e.target.closest('.quick-shop-block .size-item');
        if (sizeItem) {
            sizeItem.closest('.list-size').querySelectorAll('.size-item')
                .forEach(s => s.classList.remove('active', 'bg-black', 'text-white'));
            sizeItem.classList.add('active', 'bg-black', 'text-white');
            return;
        }
    });

    // ============================================================
    // Others
    // ============================================================
    new Swiper('.swiper-list-testimonial', {
        slidesPerView: 1, spaceBetween: 24, loop: true,
        pagination: { el: '.swiper-list-testimonial .swiper-pagination', clickable: true },
        navigation: { nextEl: '.testimonial-next', prevEl: '.testimonial-prev' },
        breakpoints: { 640: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
    });

    new Swiper('.swiper-list-instagram', {
        slidesPerView: 2, spaceBetween: 12, loop: true,
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        breakpoints: { 768: { slidesPerView: 4 }, 1024: { slidesPerView: 6 } },
    });
});

function showToast(msg, type) {
    type = type || 'success';
    let t = document.getElementById('cart-toast');
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
    const locale = document.documentElement.lang || 'ar';
    const url = locale === 'en' ? '/en/cart/add' : '/cart/add';
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ product_id: productId, size, color, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(el => el.textContent = data.count);
            showToast(data.message || (locale === 'en' ? 'Added to cart!' : 'تمت الإضافة!'), 'success');
        }
    });
}
</script>
@endpush
