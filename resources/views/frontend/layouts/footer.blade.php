<div id="footer" class="footer">
    <div class="footer-main bg-surface">
        <div class="container">
            <div class="content-footer md:py-[60px] py-10 flex justify-between flex-wrap gap-y-8">

                {{-- Company Info --}}
                <div class="company-infor basis-1/4 max-lg:basis-full pr-7">
                    <a href="{{ route('home') }}" class="logo inline-block">
                        <div class="heading3 w-fit">
                            <img src="{{ $siteSettings->footer_logo ? asset('storage/' . $siteSettings->footer_logo) : asset('assets/logo.png') }}" alt="ZIZI ABUSALLA">
                        </div>
                    </a>
                    <div class="flex gap-3 mt-3">
                        <div class="flex flex-col">
                            <span class="text-button">{{ app()->getLocale() === 'ar' ? 'البريد:' : 'Mail:' }}</span>
                            <span class="text-button mt-3">{{ app()->getLocale() === 'ar' ? 'هاتف:' : 'Phone:' }}</span>
                            {{-- <span class="text-button mt-3">{{ app()->getLocale() === 'ar' ? 'العنوان:' : 'Address:' }}</span> --}}
                        </div>
                        <div class="flex flex-col new-phone">
                            <a href="mailto:info@ziziabusalla.com">{{ $footerContact->email ?? 'hi.avitex@gmail.com' }}</a>

                                <a class="mt-[14px]" href="tel:+201121557956">{{ $footerContact->phone ?? '1-333-345-6868' }}</a>

                            
                            {{-- <span class="mt-3 pt-1">
                                {{ app()->getLocale() === 'ar'
                                    ? ($footerContact->address_ar ?? '549 Oak St. Crystal Lake, IL 60014')
                                    : ($footerContact->address_en ?? '549 Oak St. Crystal Lake, IL 60014') }}
                            </span> --}}
                        </div>
                    </div>
                </div>

                {{-- Right Content --}}
                <div class="right-content flex flex-wrap gap-y-8 basis-3/4 max-lg:basis-full">

                    {{-- Nav Links --}}
                    <div class="list-nav flex justify-between basis-2/3 max-md:basis-full gap-4">

                        {{-- Information --}}
                        <div class="item flex flex-col basis-1/3">
                            <div class="text-button-uppercase pb-3">{{ app()->getLocale() === 'ar' ? 'معلومات' : 'Information' }}</div>
                            <a class="caption1 has-line-before duration-300 w-fit"
                               href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}">
                                {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                            </a>
                            @auth
                                <a class="caption1 has-line-before duration-300 w-fit pt-2"
                                   href="{{ route(app()->getLocale() === 'ar' ? 'profile' : 'en.profile') }}">
                                    {{ app()->getLocale() === 'ar' ? 'حسابي' : 'My Account' }}
                                </a>
                            @else
                                <a class="caption1 has-line-before duration-300 w-fit pt-2"
                                   href="{{ route('login') }}">
                                    {{ app()->getLocale() === 'ar' ? 'حسابي' : 'My Account' }}
                                </a>
                            @endauth
                            {{-- <a class="caption1 has-line-before duration-300 w-fit pt-2"
                               href="{{ route(app()->getLocale() === 'ar' ? 'faqs' : 'en.faqs') }}">
                                {{ app()->getLocale() === 'ar' ? 'الأسئلة الشائعة' : 'FAQs' }}
                            </a> --}}
                            <a class="caption1 has-line-before duration-300 w-fit pt-2"
                               href="{{ route(app()->getLocale() === 'ar' ? 'about' : 'en.about') }}">
                                {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}
                            </a>
                        </div>

                        {{-- Quick Shop --}}
                        <div class="item flex flex-col basis-1/3">
                            <div class="text-button-uppercase pb-3">{{ app()->getLocale() === 'ar' ? 'تسوق سريع' : 'Quick Shop' }}</div>
                            <a class="caption1 has-line-before duration-300 w-fit"
                               href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}">
                                {{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}
                            </a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2"
                               href="{{ route(app()->getLocale() === 'ar' ? 'categories' : 'en.categories') }}">
                                {{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}
                            </a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2"
                               href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}">
                                {{ app()->getLocale() === 'ar' ? 'المدونة' : 'Blog' }}
                            </a>
                            @auth
                                <a class="caption1 has-line-before duration-300 w-fit pt-2"
                                   href="{{ route(app()->getLocale() === 'ar' ? 'wishlist' : 'en.wishlist') }}">
                                    {{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Wishlist' }}
                                </a>
                            @endauth
                        </div>

                        {{-- Customer Services --}}
                        <div class="item flex flex-col basis-1/3">
                            <div class="text-button-uppercase pb-3">{{ app()->getLocale() === 'ar' ? 'خدمة العملاء' : 'Customer Services' }}</div>
                            <!--<a class="caption1 has-line-before duration-300 w-fit pt-2" href="#">{{ app()->getLocale() === 'ar' ? 'الشحن' : 'Shipping' }}</a>-->
                            <a class="caption1 has-line-before duration-300 w-fit "
                               href="{{ route(app()->getLocale() === 'ar' ? 'privacy-policy' : 'en.privacy-policy') }}">
                                {{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                            </a>
                            <a class="caption1 has-line-before duration-300 w-fit pt-2"
                               href="{{ route(app()->getLocale() === 'ar' ? 'return-policy' : 'en.return-policy') }}">
                                {{ app()->getLocale() === 'ar' ? 'سياسة الإرجاع' : 'Return Policy' }}
                            </a>
                        </div>
                    </div>

                    {{-- Newsletter --}}
                    <div class="newsletter basis-1/3 pl-7 max-md:basis-full max-md:pl-0">
                        <div class="text-button-uppercase">{{ app()->getLocale() === 'ar' ? 'النشرة البريدية' : 'Newsletter' }}</div>
                        <div class="caption1 mt-3">
                            {{ app()->getLocale() === 'ar'
                                ? 'اشترك في نشرتنا البريدية واحصل على خصم 10% على أول طلب'
                                : 'Sign up for our newsletter and get 10% off your first purchase' }}
                        </div>

                        {{-- Alerts --}}
                        @if(session('newsletter_success'))
                            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-xl mb-3 text-sm mt-3">
                                {{ session('newsletter_success') }}
                            </div>
                        @endif
                        @if(session('newsletter_info'))
                            <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-xl mb-3 text-sm mt-3">
                                {{ session('newsletter_info') }}
                            </div>
                        @endif

                        {{-- Form --}}
                        <div class="input-block w-full h-[52px] mt-4">
                            <form class="w-full h-full relative"
                                  action="{{ route(app()->getLocale() === 'ar' ? 'newsletter.subscribe' : 'en.newsletter.subscribe') }}"
                                  method="POST">
                                @csrf
                                <input type="email" name="email"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your e-mail' }}"
                                       class="caption1 w-full h-full pl-4 pr-14 rounded-xl border border-line" required />
                                @error('email')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                                <button type="submit"
                                        class="w-[44px] h-[44px] bg-black flex items-center justify-center rounded-xl absolute top-1 right-1 css-ar-3">
                                    <i class="ph ph-arrow-right text-xl text-white"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Social Links --}}
                        <div class="list-social flex items-center gap-6 mt-4">
                            @foreach($footerSocials as $social)
                                <a href="{{ $social->url }}" target="_blank">
                                    <div class="text-2xl text-black hover:text-secondary duration-300">
                                        <i class="{{ $social->icon }}"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- Footer Bottom --}}
            <div class="footer-bottom py-3 flex items-center justify-between gap-5 max-lg:justify-center max-lg:flex-col border-t border-line">
                <div class="left flex items-center gap-8">
                    <div class="copyright caption1 text-secondary">
                        ©{{ date('Y') }} ZIZI ABUSALLA. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All Rights Reserved' }}.
                    </div>

                    {{-- Language Switcher --}}
                    <div class="select-block flex items-center gap-5 max-md:hidden">
                        <div class="choose-language flex items-center gap-1.5">
                            <select id="chooseLanguageFooter" class="caption2 bg-transparent cursor-pointer"
                                    onchange="window.location='{{ route('switch.lang', 'LOCALE') }}'.replace('LOCALE', this.value)">
                                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            <i class="ph ph-caret-down text-xs text-[#1F1F1F]"></i>
                        </div>
                    </div>
                </div>

                {{-- Payment Icons --}}
                <div class="right flex items-center gap-2">
                    <div class="caption1 text-secondary">{{ app()->getLocale() === 'ar' ? 'الدفع:' : 'Payment:' }}</div>
                    @foreach(range(0, 5) as $i)
                        <div class="payment-img">
                            <img src="{{ asset("assets/images/payment/Frame-$i.png") }}" alt="payment" class="w-9" />
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
