<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    @if(isset($sliders) && $sliders->isNotEmpty())
        <link rel="preload" as="image" href="{{ Storage::url($sliders->first()->image) }}" fetchpriority="high">
    @endif

    {{-- DNS Prefetch للـ third-party --}}
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//connect.facebook.net">
    <link rel="dns-prefetch" href="//www.googletagmanager.com">

    {{-- Google Analytics: async بدون blocking --}}
    @if($siteSettings->google_analytics)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $siteSettings->google_analytics }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $siteSettings->google_analytics }}');</script>
    @endif

    {{-- Facebook Pixel --}}
    @if($siteSettings->facebook_pixel)
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','{{ $siteSettings->facebook_pixel }}');fbq('track','PageView');</script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $siteSettings->facebook_pixel }}&ev=PageView&noscript=1"/></noscript>
    @endif



    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('seo_title', $siteSettings->seo_title ?? 'ZIZI ABUSALLA')</title>
    <meta name="description" content="@yield('seo_description', $siteSettings->seo_description ?? '')">
    <meta name="keywords" content="@yield('seo_keywords', $siteSettings->seo_keywords ?? '')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="@yield('og_type', $siteSettings->og_type ?? 'website')">
    <meta property="og:site_name"   content="{{ $siteSettings->og_site_name ?? $siteSettings->seo_title ?? 'ZIZI ABUSALLA' }}">
    <meta property="og:title"       content="@yield('seo_title', $siteSettings->seo_title ?? 'ZIZI ABUSALLA')">
    <meta property="og:description" content="@yield('seo_description', $siteSettings->seo_description ?? '')">
    <meta property="og:image"       content="@yield('og_image', $siteSettings->og_image ? asset('storage/' . $siteSettings->og_image) : asset('assets/images/fav.png'))">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:locale"      content="{{ app()->getLocale() === 'ar' ? 'ar_AR' : 'en_US' }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="{{ $siteSettings->twitter_card ?? 'summary_large_image' }}">
    @if($siteSettings->twitter_handle)
    <meta name="twitter:site"        content="{{ $siteSettings->twitter_handle }}">
    @endif
    <meta name="twitter:title"       content="@yield('seo_title', $siteSettings->seo_title ?? 'ZIZI ABUSALLA')">
    <meta name="twitter:description" content="@yield('seo_description', $siteSettings->seo_description ?? '')">
    <meta name="twitter:image"       content="@yield('og_image', $siteSettings->og_image ? asset('storage/' . $siteSettings->og_image) : asset('assets/images/fav.png'))">

    {{-- Google Verification --}}
    @if($siteSettings->google_verification)
    <meta name="google-site-verification" content="{{ $siteSettings->google_verification }}">
    @endif

    @stack('seo')

    <link rel="shortcut icon" href="{{ $siteSettings->favicon ? asset('storage/' . $siteSettings->favicon) : asset('assets/images/fav.png') }}" type="image/x-icon" />

    {{-- Critical CSS: يتحمل أول --}}
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    @if(app()->getLocale() === 'ar')
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/style-ar.css') }}" />
    @else
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <style>
            .blog-card-thumb { height: auto !important; }
            .menu { overflow-x: auto; white-space: nowrap; scrollbar-width: none; }
            .menu::-webkit-scrollbar { display: none; }
            .tab-item { flex: 0 0 auto; }
            .list-instagram .item:hover .icon { background-color: white; }
            .swiper-button-next, .swiper-button-prev { color: white; }
            .badge-new {

    display: none !important;
}
.new-size{
        padding-left: 22px;
}
.new-photo{
        width: 1410px;
    height: 482px;
    padding: 30px;
}
        </style>
    @endif
    <link rel="stylesheet" href="{{ asset('dist/output-scss.css') }}" />
    <link rel="stylesheet" href="{{ asset('dist/output-tailwind.css') }}" />

    {{-- Font Awesome: non-blocking async load لتحسين Render Blocking --}}
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
          as="style" crossorigin="anonymous"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
              crossorigin="anonymous" referrerpolicy="no-referrer" />
    </noscript>

    {{-- Font display swap لتحسين Font Display --}}
    <style>
        @font-face { font-display: swap; }
    </style>

    @stack('styles')
</head>

<body>
    @include('frontend.layouts.top-nav')
    @include('frontend.layouts.header')
    @yield('content')
    @include('frontend.layouts.footer')
<a href="https://wa.me/+393927162220" style="text-decoration: none;" target="_blank" class="btn-share social-item-2 whatsapp-22" rel="noopener noreferrer nofollow">
                <i class="fab fa-whatsapp new-icon-6"></i>
            </a>
    <a class="scroll-to-top-btn" href="#top-nav"><i class="ph-bold ph-caret-up"></i></a>

    {{-- Modal Search --}}
    <div class="modal-search-block">
        <div class="modal-search-main md:p-10 p-6 rounded-[32px]">
            <div class="form-search relative w-full">
                <i class="ph ph-magnifying-glass absolute heading5 right-6 top-1/2 -translate-y-1/2 cursor-pointer new-css-55" id="search-submit-icon"></i>
                <i class="ph ph-x absolute heading6 right-16 top-1/2 -translate-y-1/2 cursor-pointer text-secondary2 hover:text-black duration-200" id="search-clear-btn" style="display:none;"></i>
                <input type="text" id="live-search-input"
                       placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث عن منتج...' : 'Search for a product...' }}"
                       class="text-button-lg h-14 rounded-2xl border border-line w-full pl-6 pr-6 pr-20"
                       autocomplete="off" />
            </div>
            <div id="search-results" class="mt-6" style="display:none;">
                <div class="heading6 mb-4">{{ app()->getLocale() === 'ar' ? 'نتائج البحث' : 'Search Results' }}</div>
                <div id="search-results-list" class="grid grid-cols-2 gap-3 max-h-[400px] overflow-y-auto pr-2"></div>
            </div>
            <div id="search-no-results" class="mt-8 text-center" style="display:none;">
                <i class="ph ph-magnifying-glass text-4xl text-secondary2"></i>
                <p class="text-secondary mt-3">{{ app()->getLocale() === 'ar' ? 'لا توجد نتائج' : 'No results found' }}</p>
            </div>
            <div id="search-loading" class="mt-8 text-center" style="display:none;">
                <div class="inline-block w-8 h-8 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/phosphor-icons.js') }}" defer></script>









    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="{{ asset('mail/jqBootstrapValidation.min.js') }}"></script>
    <script src="{{ asset('mail/contact.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>



    <script>
    (function(){
        var input      = document.getElementById('live-search-input');
        var resultsWrap = document.getElementById('search-results');
        var resultsList = document.getElementById('search-results-list');
        var noResults  = document.getElementById('search-no-results');
        var loading    = document.getElementById('search-loading');
        var submitIcon = document.getElementById('search-submit-icon');
        var clearBtn   = document.getElementById('search-clear-btn');
        var searchUrl  = '{{ route(app()->getLocale() === "ar" ? "search-result" : "en.search-result") }}';
        var timer      = null;

        function hideAll() {
            resultsWrap.style.display = 'none';
            noResults.style.display   = 'none';
            loading.style.display     = 'none';
        }
        function toggleClearBtn() {
            if (clearBtn) clearBtn.style.display = input.value.trim() ? 'block' : 'none';
        }

        function doSearch(q) {
            if (q.length < 2) { hideAll(); return; }
            hideAll();
            loading.style.display = 'block';
            fetch(searchUrl + '?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                loading.style.display = 'none';
                if (!data.length) { noResults.style.display = 'block'; return; }
                resultsWrap.style.display = 'block';
                resultsList.innerHTML = data.map(function(p){
                    var priceHtml = '<span class="text-title text-sm font-bold">EGP ' + p.price + '</span>';
                    if (p.old_price) priceHtml += ' <del class="text-secondary2 text-xs">EGP ' + p.old_price + '</del>';
                    return '<a href="' + p.url + '" class="block rounded-xl overflow-hidden border border-line hover:shadow-md duration-200">' +
                        '<div class="aspect-[3/4] overflow-hidden bg-surface">' +
                            '<img src="' + p.image + '" class="w-full h-full object-cover" loading="lazy" decoding="async" />' +
                        '</div>' +
                        '<div class="p-2.5">' +
                            '<div class="text-xs font-semibold line-clamp-2 leading-tight">' + p.name + '</div>' +
                            '<div class="mt-1.5 flex items-center gap-1 flex-wrap">' + priceHtml + '</div>' +
                        '</div>' +
                    '</a>';
                }).join('');
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function(){
                input.value = '';
                hideAll();
                toggleClearBtn();
                input.focus();
            });
        }

        if (input) {
            input.addEventListener('input', function(){
                clearTimeout(timer);
                toggleClearBtn();
                var q = this.value.trim();
                timer = setTimeout(function(){ doSearch(q); }, 300);
            });
            input.addEventListener('keydown', function(e){
                if (e.key === 'Enter') {
                    e.preventDefault();
                    var q = this.value.trim();
                    if (q.length >= 2) window.location.href = searchUrl + '?q=' + encodeURIComponent(q);
                }
            });
        }

        if (submitIcon) {
            submitIcon.addEventListener('click', function(){
                var q = input.value.trim();
                if (q.length >= 2) window.location.href = searchUrl + '?q=' + encodeURIComponent(q);
            });
        }
    })();
    </script>

    @stack('scripts')

    {{-- Cart Success Popup --}}
    <div id="cart-popup-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:10000;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:20px;padding:40px 32px;text-align:center;max-width:340px;width:90%;position:relative;animation:cartPopupIn .3s ease;">
            <button onclick="closeCartPopup()" style="position:absolute;top:14px;right:14px;background:none;border:none;cursor:pointer;padding:4px;">
                <i class="ph ph-x" style="font-size:20px;color:#999;"></i>
            </button>
            <div style="width:72px;height:72px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="ph-fill ph-check-circle" style="font-size:40px;color:#22c55e;"></i>
            </div>
            <div id="cart-popup-title" style="font-size:18px;font-weight:700;color:#111;margin-bottom:8px;"></div>
            <div id="cart-popup-subtitle" style="font-size:14px;color:#666;margin-bottom:24px;"></div>
            <div style="display:flex;gap:10px;">
                <button onclick="closeCartPopup()" style="flex:1;padding:12px;border-radius:12px;border:1px solid #e5e5e5;background:#fff;font-size:14px;font-weight:600;cursor:pointer;">
                    {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                </button>
                <a href="{{ route(app()->getLocale() === 'ar' ? 'cart' : 'en.cart') }}" style="flex:1;padding:12px;border-radius:12px;background:#111;color:#fff;font-size:14px;font-weight:600;text-align:center;text-decoration:none;">
                    {{ app()->getLocale() === 'ar' ? 'عرض السلة' : 'View Cart' }}
                </a>
            </div>
        </div>
    </div>

    <style>
    @keyframes cartPopupIn {
        from { opacity:0; transform:scale(.85); }
        to   { opacity:1; transform:scale(1); }
    }
    </style>

    <script>
        var _cartPopupTimer = null;
        function showCartPopup() {
            var locale = document.documentElement.lang || 'ar';
            document.getElementById('cart-popup-title').textContent    = locale === 'ar' ? 'تمت الإضافة للسلة!' : 'Added to Cart!';
            document.getElementById('cart-popup-subtitle').textContent = locale === 'ar' ? 'المنتج أُضيف لسلة التسوق بنجاح' : 'Product has been added to your cart';
            var overlay = document.getElementById('cart-popup-overlay');
            overlay.style.display = 'flex';
            clearTimeout(_cartPopupTimer);
            _cartPopupTimer = setTimeout(closeCartPopup, 3000);
        }
        function closeCartPopup() {
            clearTimeout(_cartPopupTimer);
            document.getElementById('cart-popup-overlay').style.display = 'none';
        }
        document.getElementById('cart-popup-overlay').addEventListener('click', function(e) {
            if (e.target === this) closeCartPopup();
        });
    </script>

    <script>
        var footerLangSelect = document.getElementById('chooseLanguageFooter');
        if (footerLangSelect) {
            footerLangSelect.addEventListener('change', function() {
                window.location = '{{ route("switch.lang", "LOCALE") }}'.replace('LOCALE', this.value);
            });
        }

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.wishlist-btn');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();

            var locale    = document.documentElement.lang || 'ar';
            var url       = btn.dataset.url;

            if (!url) {
                var card      = btn.closest('.product-main[data-product-id]');
                var productId = card ? card.dataset.productId : btn.dataset.productId;
                if (!productId) return;
                url = locale === 'en'
                    ? '/en/wishlist/' + productId + '/toggle'
                    : '/wishlist/' + productId + '/toggle';
            }

            var icon = btn.querySelector('i');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.redirect) { window.location.href = data.redirect; return; }
                if (icon) {
                    icon.className = 'ph ph-check text-lg';
                    setTimeout(function() {
                        icon.className = data.wishlisted
                            ? 'ph-fill ph-heart text-lg text-red-500'
                            : 'ph ph-heart text-lg text-secondary';
                    }, 1500);
                }
                document.querySelectorAll('#wishlist-count, #wishlist-count-mobile').forEach(function(el) {
                    if (data.count !== undefined) el.textContent = data.count;
                });
                var msg = data.message || (data.wishlisted
                    ? (locale === 'ar' ? 'تمت الإضافة للمفضلة' : 'Added to wishlist')
                    : (locale === 'ar' ? 'تم الحذف من المفضلة' : 'Removed from wishlist'));
                var t = document.getElementById('wishlist-toast');
                if (!t) {
                    t = document.createElement('div');
                    t.id = 'wishlist-toast';
                    t.style.cssText = 'position:fixed;right:24px;z-index:9999;padding:12px 20px;border-radius:12px;font-size:14px;font-weight:600;background:#111;color:#fff;opacity:0;transition:opacity .3s;pointer-events:none;';
                    document.body.appendChild(t);
                }
                t.style.bottom  = window.innerWidth < 640 ? '90px' : '24px';
                t.textContent   = msg;
                t.style.opacity = '1';
                clearTimeout(t._timeout);
                t._timeout = setTimeout(function() { t.style.opacity = '0'; }, 2500);
            });
        });
    </script>

    <script>
    (function(){
        var btn = document.querySelector('.scroll-to-top-btn');
        if (!btn) return;
        window.addEventListener('scroll', function() {
            btn.classList.toggle('active', window.scrollY > 600);
        });
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        if (window.scrollY > 600) btn.classList.add('active');
    })();
    </script>

</body>
</html>
