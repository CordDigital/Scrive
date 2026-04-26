<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="text-center sidebar-brand-wrapper d-flex align-items-center">
        <a class="sidebar-brand brand-logo" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/logo.png') }}" alt="logo" />
        </a>
        <a class="sidebar-brand brand-logo-mini ps-4 pt-3" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
        </a>
    </div>

    <ul class="nav">
        {{-- Profile Section --}}
        <li class="nav-item nav-profile">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column pe-3">
                    <span class="font-weight-medium mb-2">{{ Auth::user()->name }}</span>
                    <span class="font-weight-normal text-muted small">{{ Auth::user()->email }}</span>
                </div>
            </a>
        </li>

        {{-- Group 1: Main --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Main</span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}" href="{{ route('admin.site-settings.edit') }}">
                <i class="mdi mdi-cog menu-icon"></i>
                <span class="menu-title">Site Settings</span>
            </a>
        </li>

        {{-- Group 2: E-Commerce --}}
        <li class="nav-item nav-category">
            <span class="nav-link">E-Commerce</span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.products.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#shop-menu">
                <i class="mdi mdi-shopping menu-icon"></i>
                <span class="menu-title">Shop Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.products.*') ? 'show' : '' }}" id="shop-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}"><i class="mdi mdi-shape me-2"></i>Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.products.index') }}"><i class="mdi mdi-package-variant me-2"></i>Products</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="mdi mdi-cart menu-icon"></i>
                <span class="menu-title">Orders</span>
                @php $pendingOrders = \App\Models\Order::where('status','pending')->count(); @endphp
                @if ($pendingOrders > 0)
                    <span class="badge badge-pill badge-danger ms-auto">{{ $pendingOrders }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                <i class="mdi mdi-ticket-percent menu-icon"></i>
                <span class="menu-title">Coupons</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.flash-sale.*') ? 'active' : '' }}" href="{{ route('admin.flash-sale.edit') }}">
                <i class="mdi mdi-lightning-bolt menu-icon"></i>
                <span class="menu-title">Flash Sale</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.wishlists.index') }}">
                <i class="mdi mdi-heart-multiple menu-icon"></i>
                <span class="menu-title">Wishlists</span>
                <span class="badge badge-pill badge-info ms-auto">{{ \App\Models\Wishlist::count() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="mdi mdi-account-group menu-icon"></i>
                <span class="menu-title">Users</span>
                <span class="badge badge-pill badge-secondary ms-auto">{{ \App\Models\User::where('role','user')->count() }}</span>
            </a>
        </li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}" href="{{ route('admin.stores.index') }}">
        <i class="mdi mdi-store menu-icon"></i>
        <span class="menu-title">Zizi world</span>
        @php $storesCount = \App\Models\Store::count(); @endphp
        @if ($storesCount > 0)
            <span class="badge badge-pill badge-primary ms-auto">{{ $storesCount }}</span>
        @endif
    </a>
</li>
        {{-- Group 3: Content --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Content & SEO</span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.sliders.*') || request()->routeIs('admin.benefits.*') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#home-page">
                <i class="mdi mdi-monitor-dashboard menu-icon"></i>
                <span class="menu-title">Home Page</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.sliders.*') || request()->routeIs('admin.benefits.*') ? 'show' : '' }}" id="home-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.sliders.index') }}"><i class="mdi mdi-image-multiple me-2"></i>Sliders</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.benefits.index') }}"><i class="mdi mdi-star-circle me-2"></i>Benefits</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                <i class="mdi mdi-post menu-icon"></i>
                <span class="menu-title">Blog Posts</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
                <i class="mdi mdi-comment-multiple menu-icon"></i>
                <span class="menu-title">Comments</span>
                @php $pending = \App\Models\BlogComment::where('is_approved', false)->count(); @endphp
                @if ($pending > 0)
                    <span class="badge badge-danger ms-auto">{{ $pending }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                <i class="mdi mdi-comment-quote menu-icon"></i>
                <span class="menu-title">Testimonials</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.instagram.*') ? 'active' : '' }}" href="{{ route('admin.instagram.index') }}">
                <i class="mdi mdi-instagram menu-icon"></i>
                <span class="menu-title">Instagram Feed</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                <i class="mdi mdi-help-circle menu-icon"></i>
                <span class="menu-title">FAQs</span>
            </a>
        </li>

        {{-- Group 4: Pages & Communication --}}
        <li class="nav-item nav-category">
            <span class="nav-link">Communication</span>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}" href="{{ route('admin.about.edit') }}">
                <i class="mdi mdi-information menu-icon"></i>
                <span class="menu-title">About Page</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.contact.edit') || request()->routeIs('admin.contact.messages') || request()->routeIs('admin.contact.show') ? '' : 'collapsed' }}"
                data-bs-toggle="collapse" href="#contact-page">
                <i class="mdi mdi-email menu-icon"></i>
                <span class="menu-title">Contact</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.contact.edit') || request()->routeIs('admin.contact.messages') || request()->routeIs('admin.contact.show') ? 'show' : '' }}" id="contact-page">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contact.edit') ? 'active' : '' }}" href="{{ route('admin.contact.edit') }}"><i class="mdi mdi-cog-outline me-2"></i>Page Settings</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.contact.messages') || request()->routeIs('admin.contact.show') ? 'active' : '' }}" href="{{ route('admin.contact.messages') }}"><i class="mdi mdi-message-text me-2"></i>Messages</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.privacy-policy.*') ? 'active' : '' }}" href="{{ route('admin.privacy-policy.edit') }}">
                <i class="mdi mdi-shield-lock menu-icon"></i>
                <span class="menu-title">Privacy Policy</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.return-policy.*') ? 'active' : '' }}" href="{{ route('admin.return-policy.edit') }}">
                <i class="mdi mdi-refresh menu-icon"></i>
                <span class="menu-title">Return Policy</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.announcement.*') ? 'active' : '' }}" href="{{ route('admin.announcement.edit') }}">
                <i class="mdi mdi-bullhorn menu-icon"></i>
                <span class="menu-title">Announcement Bar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}" href="{{ route('admin.newsletter.index') }}">
                <i class="mdi mdi-email-newsletter menu-icon"></i>
                <span class="menu-title">Newsletter</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.social.*') ? 'active' : '' }}" href="{{ route('admin.social.index') }}">
                <i class="mdi mdi-share-variant menu-icon"></i>
                <span class="menu-title">Social Links</span>
            </a>
        </li>

        {{-- Logout Section --}}
        <li class="nav-item sidebar-actions mt-3">
            <div class="nav-link">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-gradient-danger btn-block btn-lg">
                        <i class="mdi mdi-logout me-2"></i> Sign Out
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>
