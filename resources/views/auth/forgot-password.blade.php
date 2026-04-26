@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">
                    {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور' : 'Forgot Password' }}
                </div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                    </a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <div class="text-secondary2">
                        {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور' : 'Forgot Password' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-block md:py-20 py-10">
    <div class="container">
        <div class="content-main flex gap-y-8 max-md:flex-col">

            {{-- Form --}}
            <div class="left md:w-1/2 w-full lg:pr-[60px] md:pr-[40px] md:border-r border-line">
                <div class="heading4">
                    {{ app()->getLocale() === 'ar' ? 'إعادة تعيين كلمة المرور' : 'Reset Password' }}
                </div>
                <p class="body1 text-secondary mt-3">
                    {{ app()->getLocale() === 'ar'
                        ? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.'
                        : 'Enter your email and we\'ll send you a reset link.' }}
                </p>

                @if(session('status'))
                <div class="mt-4 p-3 rounded-lg text-sm" style="background:#dcfce7; color:#166534;">
                    {{ session('status') }}
                </div>
                @endif

                <form class="md:mt-7 mt-4" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                               name="email" type="email"
                               value="{{ old('email') }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email address *' }}"
                               required autofocus>
                        @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="block-button md:mt-7 mt-4">
                        <button type="submit" class="button-main w-full">
                            {{ app()->getLocale() === 'ar' ? 'إرسال رابط الإعادة' : 'Send Reset Link' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Back to login --}}
            <div class="right md:w-1/2 w-full lg:pl-[60px] md:pl-[40px] flex items-center">
                <div class="text-content">
                    <div class="heading4">
                        {{ app()->getLocale() === 'ar' ? 'تذكرت كلمة المرور؟' : 'Remember your password?' }}
                    </div>
                    <div class="mt-2 text-secondary">
                        {{ app()->getLocale() === 'ar'
                            ? 'عد لتسجيل الدخول والوصول إلى حسابك.'
                            : 'Go back and sign in to access your account.' }}
                    </div>
                    <div class="block-button md:mt-7 mt-4">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'login' : 'en.login') }}"
                           class="button-main">
                            {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
