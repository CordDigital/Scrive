@extends('frontend.layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                    </a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <div class="text-secondary2">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="login-block md:py-20 py-10">
    <div class="container">
        <div class="content-main flex gap-y-8 max-md:flex-col">

            {{-- Login Form --}}
            <div class="left md:w-1/2 w-full lg:pr-[60px] md:pr-[40px] md:border-r border-line">
                <div class="heading4">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}</div>

                @if(session('status'))
                <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
                @endif

                <form class="md:mt-7 mt-4" method="POST"
                      action="{{ app()->getLocale() === 'ar' ? route('login') : '/en/login' }}">
                    @csrf
                    @if(request('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                    @endif

                    <div class="email">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                               id="email" name="email" type="email"
                               value="{{ old('email') }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email address *' }}"
                               required autofocus>
                        @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pass mt-5" style="position:relative;">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('password') border-red-500 @enderror"
                               id="password" name="password" type="password"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'كلمة المرور *' : 'Password *' }}"
                               required autocomplete="current-password"
                               style="padding-inline-end: 44px;">
                        <button type="button" onclick="togglePass('password', this)"
                                style="position:absolute; top:50%; inset-inline-end:14px; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#999; padding:0;">
                            <i class="ph ph-eye" style="font-size:18px;"></i>
                        </button>
                        @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mt-5">
                        <div class="flex items-center">
                            <div class="block-input">
                                <input type="checkbox" name="remember" id="remember">
                                <i class="ph-fill ph-check-square icon-checkbox text-2xl"></i>
                            </div>
                            <label for="remember" class="pl-2 cursor-pointer">
                                {{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember me' }}
                            </label>
                        </div>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-semibold hover:underline text-sm">
                            {{ app()->getLocale() === 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?' }}
                        </a>
                        @endif
                    </div>

                    <div class="block-button md:mt-7 mt-4">
                        <button type="submit" class="button-main w-full">
                            {{ app()->getLocale() === 'ar' ? 'دخول' : 'Login' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Register CTA --}}
            <div class="right md:w-1/2 w-full lg:pl-[60px] md:pl-[40px] flex items-center" style="    padding-right: 30px;">
                <div class="text-content">
                    <div class="heading4">
                        {{ app()->getLocale() === 'ar' ? 'عميل جديد؟' : 'New Customer?' }}
                    </div>
                    <div class="mt-2 text-secondary">
                        {{ app()->getLocale() === 'ar'
                            ? 'انضم إلينا اليوم واستمتع بعروض حصرية وتجربة تسوق مخصصة.'
                            : 'Join us today and unlock exclusive offers and a personalized shopping experience.' }}
                    </div>
                    <div class="block-button md:mt-7 mt-4">
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'register' : 'en.register') }}"
                           class="button-main">
                            {{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Register' }}
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
