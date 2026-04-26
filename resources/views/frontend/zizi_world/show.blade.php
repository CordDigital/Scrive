@extends('frontend.layouts.app')

@section('seo_title',       $store->name)
@section('seo_description', Str::limit(strip_tags($store->description), 160))
@section('og_image',        asset('storage/' . $store->cover_image))
@section('og_type',         'website')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .store-description-content { line-height: 2; font-size: 1.1rem; color: #4b5563; }
    .store-description-content img { border-radius: 16px; margin: 24px 0; max-width: 100%; }

    .gallery-thumb { position: relative; overflow: hidden; border-radius: 16px; cursor: pointer; }
    .gallery-thumb::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.15), transparent);
        opacity: 0; transition: opacity 0.4s;
    }
    .gallery-thumb:hover::after { opacity: 1; }
    .gallery-thumb img { transition: transform 0.6s ease; }
    .gallery-thumb:hover img { transform: scale(1.05); }

    .gallerySwiper .swiper-wrapper { justify-content: center; }

    .other-store-card {
        border-radius: 20px; overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .other-store-card:hover { transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,0.08); }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
{{-- <div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">{{ $store->name }}</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="/">{{ __('Homepage') }}</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        @php $indexRoute = app()->getLocale() == 'en' ? 'en.zizi-world.index' : 'zizi-world.index'; @endphp
                        <a href="{{ route($indexRoute) }}">{{ __('Zizi World') }}</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2">{{ $store->name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- Hero Cover --}}
<div class="w-full h-[45vh] md:h-[65vh] relative overflow-hidden" style="margin-top:80px;">
    <img src="{{ asset('storage/' . $store->cover_image) }}" alt="{{ $store->name }}"
         class="w-full h-full object-cover new-photo">
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
    <!--<div class="absolute bottom-0 inset-x-0 p-8 md:p-14">-->
    <!--    <div class="container">-->
    <!--        <h1 class="text-white heading2 drop-shadow-lg">{{ $store->name }}</h1>-->
    <!--    </div>-->
    <!--</div>-->
</div>

{{-- Store Content --}}
<div class="md:py-20 py-10">
    <div class="container">

        {{-- الوصف --}}
        <div class="max-w-4xl mx-auto">
            <div class="store-description-content {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                {!! $store->description !!}
            </div>
        </div>

        @if($store->images->count() > 0)
        <div class="mt-20">
            {{-- Navigation buttons (hidden when not enough images to scroll) --}}
            <div class="flex items-center justify-center mb-10 gallery-nav-buttons" style="display:none;">
                <div class="flex gap-3">
                    <button class="prev-gal w-11 h-11 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition">
                        <i class="ph ph-caret-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
                    </button>
                    <button class="next-gal w-11 h-11 rounded-full border border-line flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition">
                        <i class="ph ph-caret-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"></i>
                    </button>
                </div>
            </div>

            <div class="swiper gallerySwiper mt-3">
                <div class="swiper-wrapper">
                    @foreach($store->images as $img)
                    <div class="swiper-slide">
                        <div class="gallery-thumb h-56 md:h-72">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt=""
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($store->description_second)
        <div class="max-w-4xl mx-auto mt-16">
            <div class="store-description-content {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                {!! $store->description_second !!}
            </div>
        </div>
        @endif

    </div>
</div>

@if($otherStores->count() > 0)
<div class="bg-surface md:py-20 py-10">
    <div class="container">
        <h3 class="heading3 text-center mb-12">{{ __('Discover More') }}</h3>
        <div class="swiper otherStoresSwiper">
            <div class="swiper-wrapper">
                @foreach($otherStores as $other)
                <div class="swiper-slide">
                    @php $showRoute = app()->getLocale() == 'en' ? 'en.zizi-world.show' : 'zizi-world.show'; @endphp
                    <a href="{{ route($showRoute, $other->slug) }}" class="other-store-card block bg-white">
                        <div class="h-52 overflow-hidden">
                            <img src="{{ asset('storage/' . $other->cover_image) }}" alt="{{ $other->name }}"
                                 class="w-full h-full object-cover hover:scale-105 transition duration-500">
                        </div>
                        <div class="p-6 text-center">
                            <h5 class="heading6 mb-2">{{ $other->name }}</h5>
                            <p class="text-secondary2 caption1 line-clamp-2">
                                {{ Str::limit(strip_tags($other->description), 80) }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var isRtl = document.documentElement.dir === 'rtl';

    @if($store->images->count() > 0)
    var totalImages = {{ $store->images->count() }};
    var navEl = document.querySelector('.gallery-nav-buttons');

    function getMaxVisible() {
        if (window.innerWidth >= 1024) return 4;
        if (window.innerWidth >= 640) return 3;
        return 2;
    }

    var needsScroll = totalImages > getMaxVisible();
    if (needsScroll) { navEl.style.display = ''; }

    new Swiper(".gallerySwiper", {
        slidesPerView: 2,
        spaceBetween: 16,
        loop: needsScroll,
        rtl: isRtl,
        autoplay: needsScroll ? { delay: 3000, disableOnInteraction: false } : false,
        navigation: { nextEl: ".next-gal", prevEl: ".prev-gal" },
        breakpoints: {
            640:  { slidesPerView: 3, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 24 }
        }
    });
    @endif

    @if($otherStores->count() > 0)
    new Swiper(".otherStoresSwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        rtl: isRtl,
        autoplay: { delay: 4000, disableOnInteraction: false },
        breakpoints: {
            640:  { slidesPerView: 2, spaceBetween: 20 },
            1024: { slidesPerView: 3, spaceBetween: 24 }
        }
    });
    @endif
</script>
@endpush

@endsection
