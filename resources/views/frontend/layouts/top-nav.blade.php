    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-2 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="{{ route(app()->getLocale() === 'ar' ? 'faqs' : 'en.faqs') }}">FAQs</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}">Help</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}">Support</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    @if(isset($topNavSocials) && $topNavSocials->count())
                        @foreach($topNavSocials as $social)
                            <a class="text-dark px-2" href="{{ $social->url }}" target="_blank" rel="noopener noreferrer">
                                <i class="{{ $social->icon }}"></i>
                            </a>
                        @endforeach
                    @else
                        <a class="text-dark px-2" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="text-dark px-2" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="text-dark px-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a class="text-dark px-2" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="text-dark pl-2" href="#"><i class="fab fa-youtube"></i></a>
                    @endif

                    {{-- Language Switcher --}}
                    <span class="text-muted px-2">|</span>
                    <div class="btn-group dropup" style="position:relative;">
                        <button type="button" class="btn btn-sm text-dark dropdown-toggle p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:13px; font-weight:600; background:none; border:none; line-height:1;">
                            <i class="fa fa-globe mr-1"></i>{{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" style="min-width:120px; font-size:13px;">
                            <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active font-weight-bold' : '' }}" href="{{ route('switch.lang', 'ar') }}">
                                العربية
                            </a>
                            <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active font-weight-bold' : '' }}" href="{{ route('switch.lang', 'en') }}">
                                English
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="text-decoration-none">
                    @if($siteSettings->logo ?? false)
                        <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->seo_title ?? 'Store' }}" style="max-height: 50px;">
                    @else
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                    @endif
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="{{ route(app()->getLocale() === 'ar' ? 'search-result' : 'en.search-result') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Search for products" value="{{ request('q') }}">
                        <div class="input-group-append">
                            <button class="input-group-text bg-transparent text-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'wishlist' : 'en.wishlist') }}" class="btn border">
                    <i class="fas fa-heart text-primary"></i>
                    <span class="badge" id="wishlist-count">{{ auth()->check() ? auth()->user()->wishlist()->count() : 0 }}</span>
                </a>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'cart' : 'en.cart') }}" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge" id="cart-count">{{ count(session('cart', [])) }}</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
