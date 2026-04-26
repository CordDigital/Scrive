@extends('frontend.layouts.app')

@section('content')

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">
                    {{ app()->getLocale() === 'ar' ? 'حسابي' : 'My Account' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="profile-block md:py-20 py-10">
    <div class="container max-w-2xl">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-surface rounded-2xl p-8">
            <div class="heading4 mb-6">
                {{ app()->getLocale() === 'ar' ? 'تعديل البيانات' : 'Edit Profile' }}
            </div>

            <form action="{{ route(app()->getLocale() === 'ar' ? 'profile.update' : 'en.profile.update') }}"
                  method="POST">
                @csrf @method('PUT')

                <div class="flex flex-col gap-4">
                    <div>
                        <label class="text-button block mb-2">
                            {{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}
                        </label>
                        <input type="text" name="name"
                               class="border-line px-4 py-3 w-full rounded-xl @error('name') border-red-400 @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-button block mb-2">
                            {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}
                        </label>
                        <input type="email" name="email"
                               class="border-line px-4 py-3 w-full rounded-xl @error('email') border-red-400 @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-line pt-4 mt-2">
                        <div class="text-button mb-4 text-secondary">
                            {{ app()->getLocale() === 'ar' ? 'تغيير كلمة المرور (اختياري)' : 'Change Password (optional)' }}
                        </div>

                        <div class="flex flex-col gap-4">
                            <div>
                                <label class="text-button block mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'كلمة المرور الحالية' : 'Current Password' }}
                                </label>
                                <input type="password" name="current_password"
                                       class="border-line px-4 py-3 w-full rounded-xl @error('current_password') border-red-400 @enderror">
                                @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="text-button block mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'كلمة المرور الجديدة' : 'New Password' }}
                                </label>
                                <input type="password" name="password"
                                       class="border-line px-4 py-3 w-full rounded-xl">
                            </div>

                            <div>
                                <label class="text-button block mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}
                                </label>
                                <input type="password" name="password_confirmation"
                                       class="border-line px-4 py-3 w-full rounded-xl">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="button-main mt-2">
                        {{ app()->getLocale() === 'ar' ? 'حفظ التغييرات' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'my.orders' : 'en.my.orders') }}"
               class="text-button hover:underline">
                {{ app()->getLocale() === 'ar' ? 'عرض طلباتي' : 'View My Orders' }} →
            </a>
        </div>
    </div>
</div>

@endsection
