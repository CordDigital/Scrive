<div id="header" class="relative w-full">
    <div class="header-menu style-one absolute top-0 left-0 right-0 w-full md:h-[74px] h-[56px] bg-transparent">
        <div class="container mx-auto h-full">
            <div class="header-main flex justify-between h-full">

                {{-- Mobile Menu Icon --}}
                <div class="menu-mobile-icon lg:hidden flex items-center">
                    <i class="fa-solid fa-list"></i>
                </div>

                <div class="left flex items-center gap-16">

                    {{-- Logo --}}
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="flex items-center max-lg:absolute max-lg:left-1/2 max-lg:-translate-x-1/2">
                        <div class="heading4">
                            <img src="{{ $siteSettings->header_logo ? asset('storage/' . $siteSettings->header_logo) : asset('assets/logo.png') }}" alt="ZIZI ABUSALLA">
                        </div>
                    </a>

                    {{-- Desktop Nav --}}
                    <div class="menu-main h-full max-lg:hidden">
                        <ul class="flex items-center gap-8 h-full">
                            <li class="h-full relative">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center gap-1 {{ request()->routeIs('home') || request()->routeIs('en.home') ? 'active' : '' }}">
                                    {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                                </a>
                            </li>
                            <li class="h-full">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'about' : 'en.about') }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center {{ request()->routeIs('about') || request()->routeIs('en.about') ? 'active' : '' }}">
                                    {{ __('About Us') }}
                                </a>
                            </li>
                            <li class="h-full">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'categories' : 'en.categories') }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center {{ request()->routeIs('categories') || request()->routeIs('en.categories') ? 'active' : '' }}">
                                    {{ __('Categories') }}
                                </a>
                            </li>
                            <li class="h-full">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center {{ request()->routeIs('shop') || request()->routeIs('en.shop') ? 'active' : '' }}">
                                    {{ __('Shop') }}
                                </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center {{ request()->routeIs('blog') || request()->routeIs('en.blog') ? 'active' : '' }}">
                                    {{ __('Blog') }}
                                </a>
                            </li>
                            <li class="h-full relative">
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}"
                                   class="text-button-uppercase duration-300 h-full flex items-center justify-center {{ request()->routeIs('contact') || request()->routeIs('en.contact') ? 'active' : '' }}">
                                    {{ __('Contact') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Right Icons --}}
                <div class="right flex gap-12">

                    {{-- Search --}}
                    <div class="max-md:hidden search-icon flex items-center cursor-pointer relative">
                        <i class="ph-bold ph-magnifying-glass text-2xl"></i>
                        <div class="line absolute bg-line w-px h-6 -right-6"></div>
                    </div>

                    <div class="list-action flex items-center gap-4">

                        {{-- User --}}
                        <div class="user-icon flex items-center justify-center cursor-pointer relative">
                            <i class="ph-bold ph-user text-2xl"></i>
                            <div class="login-popup absolute top-[74px] w-[320px] p-7 rounded-xl bg-white box-shadow-sm z-50">
                                @auth
                                    <div class="text-button mb-3">
                                        {{ app()->getLocale() === 'ar' ? 'مرحباً،' : 'Hello,' }}
                                        {{ auth()->user()->name }}
                                    </div>
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'profile' : 'en.profile') }}"
                                       class="button-main w-full text-center block mb-3">
                                        {{ __('My Account') }}
                                    </a>
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'my.orders' : 'en.my.orders') }}"
                                       class="button-main bg-surface text-black border border-line w-full text-center block mb-3">
                                        {{ __('My Orders') }}
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                       class="button-main bg-white text-black border border-black w-full text-center block mb-3">
                                        {{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                                    </a>
                                    @endif
                                    <div class="bottom mt-4 pt-4 border-t border-line">
                                        <form action="{{ route(app()->getLocale() === 'ar' ? 'logout' : 'en.logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full text-center text-secondary hover:text-black duration-200 caption1">
                                                {{ app()->getLocale() === 'ar' ? 'تسجيل الخروج' : 'Logout' }}
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'login' : 'en.login') }}"
                                       class="button-main w-full text-center block">
                                        {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                                    </a>
                                    <div class="text-secondary text-center mt-3 pb-4 caption1">
                                        {{ app()->getLocale() === 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }}
                                        <a href="{{ route(app()->getLocale() === 'ar' ? 'register' : 'en.register') }}"
                                           class="text-black pl-1 hover:underline">
                                            {{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Register' }}
                                        </a>
                                    </div>
                                    <div class="bottom mt-4 pt-4 border-t border-line"></div>
                                @endauth
                            </div>
                        </div>

                        {{-- Wishlist --}}
                        <div class="max-md:hidden wishlist-icon flex items-center relative cursor-pointer">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'wishlist' : 'en.wishlist') }}">
                                <i class="ph-bold ph-heart text-2xl"></i>
                                <span id="wishlist-count"
                                      class="quantity absolute -right-1.5 -top-1.5 text-xs text-white bg-black w-4 h-4 flex items-center justify-center rounded-full">
                                    @auth {{ auth()->user()->wishlist()->count() }} @else 0 @endauth
                                </span>
                            </a>
                        </div>

                        {{-- Cart --}}
                        <div class="max-md:hidden cart-icon flex items-center relative cursor-pointer">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'cart' : 'en.cart') }}">
                                <i class="ph-bold ph-handbag text-2xl"></i>
                                <span id="cart-count"
                                      class="quantity absolute -right-1.5 -top-1.5 text-xs text-white bg-black w-4 h-4 flex items-center justify-center rounded-full">
                                    {{ app(\App\Services\CartService::class)->count() }}
                                </span>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="menu-mobile" class="">
        <div class="menu-container bg-white h-full">
            <div class="container h-full">
                <div class="menu-main h-full overflow-hidden">
                    <div class="heading py-2 relative flex items-center justify-center">
                        <div class="close-menu-mobile-btn absolute left-0 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-surface flex items-center justify-center">
                            <i class="ph ph-x text-sm"></i>
                        </div>
                        <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="logo text-3xl font-semibold text-center">ZIZI ABUSALLA</a>
                    </div>
                    <form action="{{ route(app()->getLocale() === 'ar' ? 'search-result' : 'en.search-result') }}" method="GET" class="form-search relative mt-2">
                        <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i class="ph ph-magnifying-glass text-xl cursor-pointer"></i>
                        </button>
                        <input type="text" name="q"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث عن...' : 'What are you looking for?' }}"
                               class="h-12 rounded-lg border border-line text-sm w-full pl-10 pr-4" />
                    </form>
                    <div class="list-nav mt-6">
                        <ul>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}"
                                   class="text-xl font-semibold flex items-center justify-between {{ request()->routeIs('home') || request()->routeIs('en.home') ? 'active' : '' }}">
                                    {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'about' : 'en.about') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('about') || request()->routeIs('en.about') ? 'active' : '' }}">
                                    {{ __('About Us') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'categories' : 'en.categories') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('categories') || request()->routeIs('en.categories') ? 'active' : '' }}">
                                    {{ __('Categories') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('shop') || request()->routeIs('en.shop') ? 'active' : '' }}">
                                    {{ __('Shop') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('blog') || request()->routeIs('en.blog') ? 'active' : '' }}">
                                    {{ __('Blog') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5 {{ request()->routeIs('contact') || request()->routeIs('en.contact') ? 'active' : '' }}">
                                    {{ __('Contact') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'faqs' : 'en.faqs') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5">
                                    {{ __('FAQs') }}
                                </a>
                            </li>
                            @auth
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'profile' : 'en.profile') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5">
                                    {{ __('My Account') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'wishlist' : 'en.wishlist') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5">
                                    {{ __('Wishlist') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'my.orders' : 'en.my.orders') }}"
                                   class="text-xl font-semibold flex items-center justify-between mt-5">
                                    {{ __('My Orders') }}
                                </a>
                            </li>
                            @endauth
                             <div class="choose-type choose-language flex items-center gap-1.5">
                    <div class="select relative">
                        <p class="selected caption2 text-white">
                            {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </p>
                        <ul class="list-option bg-white">
                            <li class="caption2 {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                                onclick="window.location='{{ route('switch.lang', 'ar') }}'">
                                العربية
                            </li>
                            <li class="caption2 {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                                onclick="window.location='{{ route('switch.lang', 'en') }}'">
                                English
                            </li>
                        </ul>
                    </div>
                    <i class="ph ph-caret-down text-xs text-white"></i>
                </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Bottom Bar --}}
    <div class="menu_bar fixed bg-white bottom-0 left-0 w-full h-[70px] sm:hidden z-[101]">
        <div class="menu_bar-inner grid grid-cols-5 items-center h-full">
            <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="menu_bar-link flex flex-col items-center gap-1">
                <span class="ph-bold ph-house text-2xl block"></span>
                <span class="menu_bar-title caption2 font-semibold">
                    {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                </span>
            </a>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'categories' : 'en.categories') }}"
               class="menu_bar-link flex flex-col items-center gap-1">
                <span class="ph-bold ph-list text-2xl block"></span>
                <span class="menu_bar-title caption2 font-semibold">
                    {{ app()->getLocale() === 'ar' ? 'الأقسام' : 'Category' }}
                </span>
            </a>
            <a href="#" class="menu_bar-link flex flex-col items-center gap-1">
                <span class="ph-bold ph-magnifying-glass text-2xl block"></span>
                <span class="menu_bar-title caption2 font-semibold">
                    {{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}
                </span>
            </a>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'wishlist' : 'en.wishlist') }}"
               class="menu_bar-link flex flex-col items-center gap-1">
                <div class="wishlist-icon relative">
                    <span class="ph-bold ph-heart text-2xl block"></span>
                    <span id="wishlist-count-mobile"
                          class="quantity absolute -right-1.5 -top-1.5 text-xs text-white bg-black w-4 h-4 flex items-center justify-center rounded-full">
                        @auth {{ auth()->user()->wishlist()->count() }} @else 0 @endauth
                    </span>
                </div>
                <span class="menu_bar-title caption2 font-semibold">
                    {{ app()->getLocale() === 'ar' ? 'المفضلة' : 'Wishlist' }}
                </span>
            </a>
            <a href="{{ route(app()->getLocale() === 'ar' ? 'cart' : 'en.cart') }}"
               class="menu_bar-link flex flex-col items-center gap-1">
                <div class="cart-icon relative">
                    <span class="ph-bold ph-handbag text-2xl block"></span>
                    <span id="cart-count-mobile"
                          class="quantity absolute -right-1.5 -top-1.5 text-xs text-white bg-black w-4 h-4 flex items-center justify-center rounded-full">
                        {{ app(\App\Services\CartService::class)->count() }}
                    </span>
                </div>
                <span class="menu_bar-title caption2 font-semibold">
                    {{ app()->getLocale() === 'ar' ? 'السلة' : 'Cart' }}
                </span>
            </a>
        </div>
    </div>

</div>
