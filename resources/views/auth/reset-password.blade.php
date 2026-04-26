@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">
                    {{ app()->getLocale() === 'ar' ? 'كلمة مرور جديدة' : 'New Password' }}
                </div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Homepage' }}
                    </a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <div class="text-secondary2">
                        {{ app()->getLocale() === 'ar' ? 'إعادة التعيين' : 'Reset Password' }}
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
                    {{ app()->getLocale() === 'ar' ? 'تعيين كلمة مرور جديدة' : 'Set New Password' }}
                </div>

                <form class="md:mt-7 mt-4" method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div>
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                               name="email" type="email"
                               value="{{ old('email', $request->email) }}"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email address *' }}"
                               required autofocus>
                        @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="mt-5" style="position:relative;">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg @error('password') border-red-500 @enderror"
                               id="password" name="password" type="password"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'كلمة المرور الجديدة *' : 'New Password *' }}"
                               required autocomplete="new-password"
                               style="padding-inline-end:44px;">
                        <button type="button" onclick="togglePass('password', this)"
                                style="position:absolute;top:50%;inset-inline-end:14px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#999;padding:0;">
                            <i class="ph ph-eye" style="font-size:18px;"></i>
                        </button>
                        @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mt-5" style="position:relative;">
                        <input class="border-line px-4 pt-3 pb-3 w-full rounded-lg"
                               id="password_confirmation" name="password_confirmation" type="password"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور *' : 'Confirm Password *' }}"
                               required style="padding-inline-end:44px;">
                        <button type="button" onclick="togglePass('password_confirmation', this)"
                                style="position:absolute;top:50%;inset-inline-end:14px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#999;padding:0;">
                            <i class="ph ph-eye" style="font-size:18px;"></i>
                        </button>
                    </div>

                    <div class="block-button md:mt-7 mt-4">
                        <button type="submit" class="button-main w-full">
                            {{ app()->getLocale() === 'ar' ? 'تعيين كلمة المرور' : 'Reset Password' }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Side text --}}
            <div class="right md:w-1/2 w-full lg:pl-[60px] md:pl-[40px] flex items-center">
                <div class="text-content">
                    <div class="heading4">
                        {{ app()->getLocale() === 'ar' ? 'اختر كلمة مرور قوية' : 'Choose a strong password' }}
                    </div>
                    <div class="mt-2 text-secondary">
                        {{ app()->getLocale() === 'ar'
                            ? 'استخدم مزيجاً من الأحرف والأرقام والرموز لحماية حسابك.'
                            : 'Use a mix of letters, numbers and symbols to keep your account secure.' }}
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
