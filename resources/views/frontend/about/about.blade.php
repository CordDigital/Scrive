@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">
                        {{ __('About Us') }}
                    </div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="/">{{ __('Homepage') }}</a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">{{ __('About Us') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- About Section --}}
<div class="about md:pt-20 pt-10">
    <div class="about-us-block">
        <div class="container">
            <div class="text flex items-center justify-center">
                <div class="content md:w-5/6 w-full">
                    <div class="heading3 text-center">
                        {!! $AboutPage->title ?? __('About Us') !!}
                    </div>
                    <div class="body1 text-center md:mt-7 mt-5">
                        {!! $AboutPage->description ?? __('About page content goes here...') !!}
                    </div>
                </div>
            </div>

            {{-- Images --}}
            @if($AboutPage->image_1 || $AboutPage->image_2 || $AboutPage->image_3)
            <div class="list-img grid sm:grid-cols-3 gap-[30px] md:pt-20 pt-10">
                @foreach([1,2,3] as $i)
                    @if($AboutPage->{"image_$i"})
                        <div class="bg-img">
                            <img src="{{ Storage::url($AboutPage->{"image_$i"}) }}"
                                 alt="{{ __('About Image') }} {{ $i }}"
                                 class="w-full rounded-[30px]" />
                        </div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Benefits --}}
<div class="benefit-block md:pt-20 pt-10">
    <div class="container">
       <div class="list-benefit grid items-start lg:grid-cols-4 grid-cols-2 gap-[30px]">
            @foreach($benefits as $benefit)
            <div class="benefit-item flex flex-col items-center justify-center">
                <i class="{{ $benefit->icon }} text-5xl"></i>
                <div class="heading6 text-center mt-5">{{ app()->getLocale() === 'ar' ? $benefit->title_ar : $benefit->title_en }}</div>
                <div class="caption1 text-secondary text-center mt-3">{{ app()->getLocale() === 'ar' ? $benefit->description_ar : $benefit->description_en }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Newsletter --}}

{{-- <div class="container">
    <div class="newsletter-block md:py-20 sm:py-14 py-10 sm:px-8 px-6 sm:rounded-[32px] rounded-3xl flex flex-col items-center bg-green md:mt-20 mt-10">

        <div class="heading3 text-white text-center">
            {{ __('Sign up and get 10% off') }}
        </div>

        <div class="text-white text-center mt-3">
            {{ __('Sign up for early sale access, new in, promotions and more') }}
        </div>

        {{-- Alerts --}}
        {{-- @if(session('newsletter_success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-xl mt-4 text-sm">
                {{ session('newsletter_success') }}
            </div>
        @endif --}}

        {{-- @if(session('newsletter_info'))
            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-xl mt-4 text-sm">
                {{ session('newsletter_info') }}
            </div>
        @endif --}}

        {{-- <div class="input-block lg:w-1/2 sm:w-3/5 w-full h-[52px] sm:mt-10 mt-7">

            <form class="w-full h-full relative"
                  action="{{ route(app()->getLocale() === 'ar' ? 'newsletter.subscribe' : 'en.newsletter.subscribe') }}"
                  method="POST">

                @csrf

                <input type="email"
                       name="email"
                       placeholder="{{ __('Enter your e-mail') }}"
                       class="caption1 w-full h-full pl-4 pr-14 rounded-xl border border-line"
                       required />

                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror

                <button type="submit"
                        class="button-main bg-green text-black absolute top-1 bottom-1 right-1 flex items-center justify-center px-4 rounded-xl">
                    {{ __('Subscribe') }}
                </button>

            </form>

        </div> --}}
    {{-- </div>
</div> --}}


<!-- Instagram -->

<div class="instagram-block md:pt-20 pt-10">
    <div class="container">
        <div class="heading">
            <div class="heading3 text-center">{{ __('ZIZI ABUSALLA On Instagram') }}</div>
            <div class="text-center mt-3">{{ __('ZiziAbusalla') }}</div>
        </div>
        <div class="list-instagram md:mt-10 mt-6">
            <div class="swiper swiper-list-instagram">
                <div class="swiper-wrapper">
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
