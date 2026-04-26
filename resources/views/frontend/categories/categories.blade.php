@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-img">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">{{ __('Categories') }}</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">{{ __('Homepage') }}</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">{{ __('Categories') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Categories with Tabs --}}
<div class="collection-block md:pt-20 pt-10 md:pb-20 pb-10">
    <div class="container">
        {{-- Parent Tabs --}}
        <div class="menu-tab bg-surface rounded-2xl mt-6 mx-auto" style="max-width: fit-content;">
            <div class="menu flex items-center justify-center gap-2 p-1 relative overflow-x-auto whitespace-nowrap">
                <div class="indicator absolute top-1 bottom-1 bg-white rounded-full shadow-md duration-300"></div>
                <div class="tab-item relative text-secondary text-button-uppercase py-2 px-5 cursor-pointer duration-300 hover:text-black active"
                     data-item="all">
                    {{ __('All') }}
                </div>
                @foreach($categories as $cat)
                <div class="tab-item relative text-secondary text-button-uppercase py-2 px-5 cursor-pointer duration-300 hover:text-black"
                     data-item="{{ $cat->id }}">
                    {{ app()->getLocale() === 'ar' ? $cat->name_ar : $cat->name_en }}
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tab Content: All (children only) --}}
        <div class="tab-content mt-10" id="tab-all">
            <div class="list-collection relative section-swiper-navigation style-outline style-small-border">
                <div class="all-swiper-prev swiper-button-prev" tabindex="0" role="button"></div>
                <div class="swiper all-children-swiper h-full relative">
                    <div class="swiper-wrapper new-494">
                        @foreach($categories as $cat)
                            @foreach($cat->children as $child)
                            <div class="swiper-slide" role="group">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($child->name_en) }}"
                                   class="flex flex-col items-center group">
                                    <div class="rounded-full overflow-hidden aspect-square mx-auto" style="max-width:150px;">
                                        @if($child->image)
                                        <img src="{{ asset('storage/' . $child->image) }}"
                                             alt="{{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}"
                                             class="w-full h-full object-cover duration-500 group-hover:scale-105">
                                        @else
                                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#f3f4f6,#e5e7eb);">
                                            <i class="ph ph-image text-3xl text-secondary2"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <span class="body1 font-semibold mt-3 text-center group-hover:underline">{{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}</span>
                                    <span class="caption1 text-secondary">{{ $child->products_count ?? 0 }} {{ __('Products') }}</span>
                                </a>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                <div class="all-swiper-next swiper-button-next" tabindex="0" role="button"></div>
            </div>
        </div>

        {{-- Tab Content: Per Parent --}}
        @foreach($categories as $cat)
        <div class="tab-content mt-10 hidden" id="tab-{{ $cat->id }}">
            @if($cat->children->count() > 0)
            <div class="list-collection relative section-swiper-navigation style-outline style-small-border">
                <div class="parent-{{ $cat->id }}-prev swiper-button-prev" tabindex="0" role="button"></div>
                <div class="swiper parent-swiper-{{ $cat->id }} h-full relative">
                    <div class="swiper-wrapper">
                        @foreach($cat->children as $child)
                        <div class="swiper-slide" role="group">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($child->name_en) }}"
                               class="flex flex-col items-center group">
                                <div class="rounded-full overflow-hidden aspect-square mx-auto" style="max-width:150px;">
                                    @if($child->image)
                                    <img src="{{ asset('storage/' . $child->image) }}"
                                         alt="{{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}"
                                         class="w-full h-full object-cover duration-500 group-hover:scale-105">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#f3f4f6,#e5e7eb);">
                                        <i class="ph ph-image text-3xl text-secondary2"></i>
                                    </div>
                                    @endif
                                </div>
                                <span class="body1 font-semibold mt-3 text-center group-hover:underline">{{ app()->getLocale() === 'ar' ? $child->name_ar : $child->name_en }}</span>
                                <span class="caption1 text-secondary">{{ $child->products_count ?? 0 }} {{ __('Products') }}</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="parent-{{ $cat->id }}-next swiper-button-next" tabindex="0" role="button"></div>
            </div>
            @else
            <div class="text-center py-16">
                <i class="ph ph-folder-open text-6xl text-secondary2"></i>
                <p class="body1 text-secondary mt-4">{{ __('No subcategories') }}</p>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}?category={{ urlencode($cat->name_en) }}"
                   class="text-button inline-block mt-3 border-b border-black pb-0.5">
                    {{ __('Browse Products') }} →
                </a>
            </div>
            @endif
        </div>
        @endforeach

    </div>
</div>

{{-- Banners --}}

<div class="banner-block style-one md:pt-10 pt-6 md:pb-20 pb-10">
    <div class="container grid sm:grid-cols-2 gap-5">
        {{-- Banner 1 --}}
        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
           class="banner-item relative block overflow-hidden rounded-2xl duration-500">
            <div class="banner-img">
                <img src="{{ asset('assets/images/banner/Season.jpg
') }}" class="duration-1000" alt="Banner 1">
            </div>
            <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
                <div class=" text-white text-center">
                    <h3>

                  {{ __('Trendy girls’ clothing made for comfort and confidence.') }}

                    </h3>
</div>
                <div class="text-button text-white relative inline-block pb-1 border-b-2 border-white duration-500 mt-2">
                    {{ __('Shop Now') }}
                </div>
            </div>
        </a>

        <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
           class="banner-item relative block overflow-hidden rounded-2xl duration-500">
            <div class="banner-img">
                <img src="{{ asset('assets/images/banner/Dresses banner.webp
') }}" class="duration-1000" alt="Banner 2">
            </div>
            <div class="banner-content absolute top-0 left-0 w-full h-full flex flex-col items-center justify-center">
                <div class=" text-white text-center">
                    <h3>

                  {{ __('Perfect outfits for every day and every occasion.') }}
                    </h3>
                    </div>
                <div class="text-button text-white relative inline-block pb-1 border-b-2 border-white duration-500 mt-2">
                    {{ __('Shop Now') }}
                </div>
            </div>
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const swiperConfig = {
        slidesPerView: 3,
        spaceBetween: 12,
        breakpoints: {
            640: { slidesPerView: 4, spaceBetween: 16 },
            768: { slidesPerView: 5, spaceBetween: 20 },
            1024: { slidesPerView: 6, spaceBetween: 24 },
            1280: { slidesPerView: 7, spaceBetween: 30 },
        },
    };

    // All children swiper
    let allSwiper = null;
    if (document.querySelector('.all-children-swiper')) {
        allSwiper = new Swiper('.all-children-swiper', {
            ...swiperConfig,
            navigation: {
                prevEl: '.all-swiper-prev',
                nextEl: '.all-swiper-next',
            },
        });
    }

    // Per-parent swipers (init on first show)
    const parentSwipers = {};

    function initParentSwiper(id) {
        if (parentSwipers[id]) return;
        const el = document.querySelector('.parent-swiper-' + id);
        if (!el) return;
        parentSwipers[id] = new Swiper(el, {
            ...swiperConfig,
            navigation: {
                prevEl: '.parent-' + id + '-prev',
                nextEl: '.parent-' + id + '-next',
            },
        });
    }

    // Tabs
    const tabs = document.querySelectorAll('.menu-tab .tab-item');
    const contents = document.querySelectorAll('.tab-content');
    const indicator = document.querySelector('.menu-tab .indicator');

    function moveIndicator(tab) {
        if (indicator) {
            indicator.style.left = tab.offsetLeft + 'px';
            indicator.style.width = tab.offsetWidth + 'px';
        }
    }

    const activeTab = document.querySelector('.menu-tab .tab-item.active');
    if (activeTab) moveIndicator(activeTab);

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const id = this.dataset.item;

            tabs.forEach(t => t.classList.remove('active', 'text-black'));
            this.classList.add('active', 'text-black');
            moveIndicator(this);

            contents.forEach(c => {
                if (c.id === 'tab-' + id) {
                    c.classList.remove('hidden');
                } else {
                    c.classList.add('hidden');
                }
            });

            // Init swiper for this parent on first click
            if (id !== 'all') {
                setTimeout(() => initParentSwiper(id), 50);
            }
        });
    });
});
</script>
@endpush

@endsection
