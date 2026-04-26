@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Register' }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                    </a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <div class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Register' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="register-block md:py-20 py-10">
    <div class="container">
        <div class="content-main flex gap-y-8 max-md:flex-col">

            {{-- Register Form --}}
            <div class="left md:w-1/2 w-full lg:pr-[60px] md:pr-[40px] md:border-r border-line">
                <div class="heading4">{{ app()->getLocale() === 'ar' ? 'إنشاء حساب جديد' : 'Create Account' }}</div>

                <form class="md:mt-7 mt-4" method="POST"
                      action="{{ app()->getLocale() === 'ar' ? route('register') : '/en/register' }}">
                    @csrf

                    <div>
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('name') border-red-500 @enderror"
                               name="name" type="text"
                               value="{{ old('name') }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}"
                               required autofocus>
                        @error('name')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-5">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                               name="email" type="email"
                               value="{{ old('email') }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email address *' }}"
                               required>
                        @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-5" style="position:relative;">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('password') border-red-500 @enderror"
                               id="password" name="password" type="password"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'كلمة المرور *' : 'Password *' }}"
                               required autocomplete="new-password"
                               style="padding-inline-end: 44px;">
                        <button type="button" onclick="togglePass('password', this)"
                                style="position:absolute; top:50%; inset-inline-end:14px; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#999; padding:0;">
                            <i class="ph ph-eye" style="font-size:18px;"></i>
                        </button>
                        @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-5" style="position:relative;">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg"
                               id="password_confirmation" name="password_confirmation" type="password"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور *' : 'Confirm Password *' }}"
                               required style="padding-inline-end: 44px;">
                        <button type="button" onclick="togglePass('password_confirmation', this)"
                                style="position:absolute; top:50%; inset-inline-end:14px; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#999; padding:0;">
                            <i class="ph ph-eye" style="font-size:18px;"></i>
                        </button>
                    </div>

                    <div class="block-button md:mt-7 mt-4">
                        <button type="submit" class="button-main w-full">
                            {{ app()->getLocale() === 'ar' ? 'إنشاء الحساب' : 'Create Account' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Login CTA --}}
            <div class="right md:w-1/2 w-full lg:pl-[60px] md:pl-[40px] flex items-center">
                <div class="text-content">
                    <div class="heading4">
                        {{ app()->getLocale() === 'ar' ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}
                    </div>
                    <div class="mt-2 text-secondary">
                        {{ app()->getLocale() === 'ar'
                            ? 'مرحباً بعودتك! سجّل دخولك للوصول إلى تجربتك المخصصة.'
                            : 'Welcome back! Sign in to access your personalized experience.' }}
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

@push('scripts')
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('ph-eye', 'ph-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('ph-eye-slash', 'ph-eye');
    }
}
</script>
@endpush

@endsection
