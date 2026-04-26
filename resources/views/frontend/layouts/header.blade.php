
    <!-- Navbar Start -->
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0" id="navbar-vertical">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                        @php
                            $navCategories = \App\Models\Category::where('is_active', true)
                                ->whereNull('parent_id')
                                ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
                                ->orderBy('sort_order')
                                ->take(10)
                                ->get();
                        @endphp
                        @forelse($navCategories as $cat)
                            @if($cat->children->count())
                                <div class="nav-item dropdown">
                                    <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', ['category' => $cat->name_en]) }}" class="nav-link" data-toggle="dropdown">
                                        {{ $cat->name }} <i class="fa fa-angle-down float-right mt-1"></i>
                                    </a>
                                    <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                                        @foreach($cat->children as $child)
                                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', ['category' => $child->name_en]) }}" class="dropdown-item">{{ $child->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop', ['category' => $cat->name_en]) }}" class="nav-item nav-link">{{ $cat->name }}</a>
                            @endif
                        @empty
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="nav-item nav-link">Shop All</a>
                        @endforelse
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="text-decoration-none d-block d-lg-none">
                        @if($siteSettings->logo ?? false)
                            <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->seo_title ?? 'Store' }}" style="max-height: 40px;">
                        @else
                            <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                        @endif
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="nav-item nav-link {{ request()->routeIs('home') || request()->routeIs('en.home') ? 'active' : '' }}">Home</a>
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}" class="nav-item nav-link {{ request()->routeIs('shop') || request()->routeIs('en.shop') ? 'active' : '' }}">Shop</a>
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}" class="nav-item nav-link {{ request()->routeIs('blog') || request()->routeIs('en.blog') ? 'active' : '' }}">Blog</a>
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'about' : 'en.about') }}" class="nav-item nav-link {{ request()->routeIs('about') || request()->routeIs('en.about') ? 'active' : '' }}">About</a>
                            <a href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') || request()->routeIs('en.contact') ? 'active' : '' }}">Contact</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            @auth
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'profile' : 'en.profile') }}" class="nav-item nav-link">{{ auth()->user()->name }}</a>
                                <form method="POST" action="{{ route(app()->getLocale() === 'ar' ? 'logout' : 'en.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-item nav-link btn btn-link p-0" style="border:none;">Logout</button>
                                </form>
                            @else
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'login' : 'en.login') }}" class="nav-item nav-link">Login</a>
                                <a href="{{ route(app()->getLocale() === 'ar' ? 'register' : 'en.register') }}" class="nav-item nav-link">Register</a>
                            @endauth
                        </div>
                    </div>
                </nav>
                
            </div>
        </div>
    </div>
    <!-- Navbar End -->
