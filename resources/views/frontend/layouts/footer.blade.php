  <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}" class="text-decoration-none">
                    @if($siteSettings->logo ?? false)
                        <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->seo_title ?? 'Store' }}" style="max-height: 50px;" class="mb-4">
                    @else
                        <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border border-white px-3 mr-1">E</span>Shopper</h1>
                    @endif
                </a>
                <p>{{ $siteSettings->seo_description ?? 'Your one-stop shop for quality products at great prices.' }}</p>
                @if(isset($footerContact) && $footerContact)
                    @if($footerContact->address)
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{ $footerContact->address }}</p>
                    @endif
                    @if($footerContact->email)
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ $footerContact->email }}</p>
                    @endif
                    @if($footerContact->phone)
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ $footerContact->phone }}</p>
                    @endif
                @else
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
                @endif
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Quick Links</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'shop' : 'en.shop') }}"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'blog' : 'en.blog') }}"><i class="fa fa-angle-right mr-2"></i>Blog</a>
                            <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'about' : 'en.about') }}"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                            <a class="text-dark" href="{{ route(app()->getLocale() === 'ar' ? 'contact' : 'en.contact') }}"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">My Account</h5>
                        <div class="d-flex flex-column justify-content-start">
                            @auth
                                <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'profile' : 'en.profile') }}"><i class="fa fa-angle-right mr-2"></i>My Profile</a>
                                <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'my.orders' : 'en.my.orders') }}"><i class="fa fa-angle-right mr-2"></i>My Orders</a>
                                <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'wishlist' : 'en.wishlist') }}"><i class="fa fa-angle-right mr-2"></i>Wishlist</a>
                            @else
                                <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'login' : 'en.login') }}"><i class="fa fa-angle-right mr-2"></i>Login</a>
                                <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'register' : 'en.register') }}"><i class="fa fa-angle-right mr-2"></i>Register</a>
                            @endauth
                            <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'cart' : 'en.cart') }}"><i class="fa fa-angle-right mr-2"></i>Shopping Cart</a>
                            <a class="text-dark mb-2" href="{{ route(app()->getLocale() === 'ar' ? 'faqs' : 'en.faqs') }}"><i class="fa fa-angle-right mr-2"></i>FAQs</a>
                            <a class="text-dark" href="{{ route(app()->getLocale() === 'ar' ? 'privacy-policy' : 'en.privacy-policy') }}"><i class="fa fa-angle-right mr-2"></i>Privacy Policy</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Newsletter</h5>
                        @if(session('newsletter_success'))
                            <div class="alert alert-success">{{ session('newsletter_success') }}</div>
                        @endif
                        <form action="{{ route(app()->getLocale() === 'ar' ? 'newsletter.subscribe' : 'en.newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="name" class="form-control border-0 py-4" placeholder="Your Name" />
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control border-0 py-4" placeholder="Your Email" required />
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block border-0 py-3" type="submit">Subscribe Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">{{ $siteSettings->seo_title ?? 'Store' }}</a>. All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                @if(isset($footerSocials) && $footerSocials->count())
                    @foreach($footerSocials as $social)
                        <a class="text-dark px-2" href="{{ $social->url }}" target="_blank" rel="noopener noreferrer">
                            <i class="{{ $social->icon }}"></i>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <!-- Footer End -->
