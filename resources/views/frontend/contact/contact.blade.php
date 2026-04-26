@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">
                        {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                    </div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route('home') }}">
                            {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                        </a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2 capitalize">
                            {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Contact --}}
<div class="contact-us md:py-20 py-10">
    <div class="container">

        {{-- Success --}}
        @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex justify-between max-lg:flex-col gap-y-10">

            {{-- Form --}}
            <div class="left lg:w-2/3 lg:pr-4">
                <div class="heading3">
                    {{ app()->getLocale() === 'ar' ? 'راسلنا' : 'Drop Us A Line' }}
                </div>
                <div class="body1 text-secondary2 mt-3">
                    {{ app()->getLocale() === 'ar'
                        ? 'استخدم النموذج أدناه للتواصل مع فريق المبيعات'
                        : 'Use the form below to get in touch with the sales team' }}
                </div>

                <form action="{{ route('contact.send') }}" method="POST" class="md:mt-6 mt-4">
                    @csrf
                    <div class="grid sm:grid-cols-2 grid-cols-1 gap-4 gap-y-5">
                        <div class="name">
                            <input class="border-line px-4 py-3 w-full rounded-lg @error('name') border-red-400 @enderror"
                                   name="name" type="text"
                                   value="{{ old('name') }}"
                                   placeholder="{{ app()->getLocale() === 'ar' ? 'اسمك *' : 'Your Name *' }}"
                                   required />
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="email">
                            <input class="border-line px-4 py-3 w-full rounded-lg @error('email') border-red-400 @enderror"
                                   name="email" type="email"
                                   value="{{ old('email') }}"
                                   placeholder="{{ app()->getLocale() === 'ar' ? 'بريدك الإلكتروني *' : 'Your Email *' }}"
                                   required />
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="message sm:col-span-2">
                            <textarea class="border-line px-4 py-3 w-full rounded-lg @error('message') border-red-400 @enderror"
                                      name="message" rows="3"
                                      placeholder="{{ app()->getLocale() === 'ar' ? 'رسالتك *' : 'Your Message *' }}"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="block-button md:mt-6 mt-4">
                        <button type="submit"
                                class="button-main"
                                style="background-color: #000; color: #fff;">
                            {{ app()->getLocale() === 'ar' ? 'إرسال الرسالة' : 'Send Message' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info --}}
            @if($contact)
            <div class="right lg:w-1/4 lg:pl-4">
                <div class="item">
                    <div class="heading4">
                        {{ app()->getLocale() === 'ar' ? 'متجرنا' : 'Our Store' }}
                    </div>
                    {{-- <p class="mt-3">{{ $contact->address }}</p> --}}
                    <p class="mt-3">
                        {{ app()->getLocale() === 'ar' ? 'هاتف:' : 'Phone:' }}
                        <span class="whitespace-nowrap">{{ $contact->phone }}</span>
                    </p>
                    <p class="mt-1">
                        {{ app()->getLocale() === 'ar' ? 'البريد:' : 'Email:' }}
                        <span class="whitespace-nowrap">{{ $contact->email }}</span>
                    </p>
                </div>
                <div class="item mt-10">
                    <div class="heading4">
                        {{ app()->getLocale() === 'ar' ? 'ساعات العمل' : 'Open Hours' }}
                    </div>
                    <p class="mt-3">
                        {{ app()->getLocale() === 'ar' ? 'الإثنين - الجمعة:' : 'Mon - Fri:' }}
                        <span class="whitespace-nowrap">{{ $contact->mon_fri }}</span>
                    </p>
                    <p class="mt-3">
                        {{ app()->getLocale() === 'ar' ? 'السبت:' : 'Saturday:' }}
                        <span class="whitespace-nowrap">{{ $contact->saturday }}</span>
                    </p>
                    <p class="mt-3">
                        {{ app()->getLocale() === 'ar' ? 'الأحد:' : 'Sunday:' }}
                        <span class="whitespace-nowrap">{{ $contact->sunday }}</span>
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Map --}}
@if($contact && $contact->map_url)
<div class="map xl:h-[600px] sm:h-[500px] h-[450px] overflow-hidden">
    <iframe class="w-full h-full"
            src="{{ $contact->map_url }}"
            allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>
@endif

@endsection