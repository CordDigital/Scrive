@extends('frontend.layouts.app')

@section('content')
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">{{ __('Zizi World') }}</div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="/">{{ __('Homepage') }}</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">{{ __('Zizi World') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="store-list md:py-20 py-10">
    <div class="container">
        @forelse($stores as $index => $store)
            <div class="item bg-surface overflow-hidden rounded-[20px] {{ $index > 0 ? 'md:mt-20 mt-10' : '' }}">
                <div class="flex items-center {{ $index % 2 == 0 ? 'lg:justify-end' : 'lg:justify-start' }} relative max-lg:flex-col {{ $index % 2 != 0 ? 'max-lg:flex-col-reverse' : '' }}">

                    <img src="{{ asset('storage/' . ($store->thumbnail ?? $store->cover_image)) }}"
                         alt="{{ $store->name }}"
                         class="lg:absolute relative top-0 bottom-0 lg:w-1/2 w-full h-full object-cover"
                         style="{{ $index % 2 == 0 ? 'inset-inline-start: 0;' : 'inset-inline-end: 0;' }}" />

                    <div class="lg:w-1/2 {{ $index % 2 == 0 ? 'lg:ps-[100px] lg:pe-20' : 'lg:pe-[100px] lg:ps-20' }} lg:py-14 sm:py-10 py-6 max-lg:px-6 new-size">
                        <div class="heading3">{{ $store->name }}</div>

                        <div class="mt-3 body1 text-secondary">
                            {!! Str::limit(strip_tags($store->description), 250) !!}
                        </div>

                        <div class="mt-6">
                            @php $showRoute = app()->getLocale() == 'en' ? 'en.zizi-world.show' : 'zizi-world.show'; @endphp
                            <a href="{{ route($showRoute, $store->slug) }}" class="button-main bg-black text-white px-8 py-3 rounded-full inline-block">
                                {{ __('Explore Store') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <div class="heading4 text-secondary">{{ __('No stores found in Zizi World yet.') }}</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
