@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="text-content">
                    <div class="heading2 text-center">
                        {{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                    </div>
                    <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                            {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                        </a>
                        <i class="ph ph-caret-right text-sm text-secondary2"></i>
                        <div class="text-secondary2">
                            {{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="legal-page-block md:py-20 py-10">
    <div class="container">
        @if($page && $page->content)
            <div class="prose max-w-none {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}"
                 dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                {!! $page->content !!}
            </div>
        @else
            <div class="text-center py-20 text-secondary">
                <i class="ph ph-shield-slash text-7xl"></i>
                <p class="mt-4">
                    {{ app()->getLocale() === 'ar' ? 'لم يتم إضافة سياسة الخصوصية بعد' : 'Privacy policy not available yet.' }}
                </p>
            </div>
        @endif
    </div>
</div>

@endsection
