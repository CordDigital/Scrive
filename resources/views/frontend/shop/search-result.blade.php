@extends('frontend.layouts.app')

@section('seo_title', (app()->getLocale() === 'ar' ? 'نتائج البحث' : 'Search Results') . ($q ? " - $q" : ''))

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">{{ app()->getLocale() === 'ar' ? 'نتائج البحث' : 'Search Results' }}</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                            {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                        </a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'نتائج البحث' : 'Search Results' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shop-product search-result-block lg:py-20 md:py-14 py-10">
    <div class="container">
        <div class="heading flex flex-col items-center">
            @if($q)
                <div class="heading4 text-center">
                    {{ app()->getLocale() === 'ar' ? 'تم العثور على' : 'Found' }}
                    <span class="text-red">{{ $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->total() : $products->count() }}</span>
                    {{ app()->getLocale() === 'ar' ? 'نتيجة لـ' : 'results for' }}
                    "<span>{{ $q }}</span>"
                </div>
            @endif

            <div class="input-block lg:w-1/2 sm:w-3/5 w-full md:h-[52px] h-[44px] sm:mt-8 mt-5">
                <form action="{{ route(app()->getLocale() === 'ar' ? 'search-result' : 'en.search-result') }}" method="GET" class="w-full h-full relative">
                    <input type="text" name="q" value="{{ $q }}"
                           placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث...' : 'Search...' }}"
                           class="caption1 w-full h-full pl-4 md:pr-[150px] pr-32 rounded-xl border border-line" />
                    <button type="submit" class="button-main absolute top-1 bottom-1 right-1 flex items-center justify-center">
                        {{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}
                    </button>
                </form>
            </div>
        </div>

        @if($products->count() > 0)
        <div class="list-product-block relative md:pt-10 pt-6">
            <div class="list-product hide-product-sold grid lg:grid-cols-4 sm:grid-cols-3 grid-cols-2 sm:gap-[30px] gap-[20px] mt-5">
                @foreach($products as $product)
                    @include('frontend.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
            <div class="flex justify-center mt-10">
                {{ $products->links() }}
            </div>
            @endif
        </div>
        @elseif($q)
        <div class="text-center md:pt-10 pt-6">
            <i class="ph ph-magnifying-glass text-5xl text-secondary2"></i>
            <p class="heading6 text-secondary mt-4">
                {{ app()->getLocale() === 'ar' ? 'لا توجد نتائج. جرب كلمات بحث مختلفة.' : 'No results found. Try different search terms.' }}
            </p>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="button-main mt-6">
                {{ app()->getLocale() === 'ar' ? 'تصفح المتجر' : 'Browse Shop' }}
            </a>
        </div>
        @else
        <div class="text-center md:pt-10 pt-6">
            <i class="ph ph-magnifying-glass text-5xl text-secondary2"></i>
            <p class="heading6 text-secondary mt-4">
                {{ app()->getLocale() === 'ar' ? 'اكتب كلمة للبحث' : 'Type something to search' }}
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
