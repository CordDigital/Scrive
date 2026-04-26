{{-- Partial: resources/views/frontend/partials/product-card.blade.php --}}
<div class="product-item grid-type"
    @if (isset($categoryId)) data-item="{{ $categoryId }}" @endif
    @if (isset($priceRange)) data-price-range="{{ $priceRange }}" @endif>

    <div class="product-main cursor-pointer block" data-product-id="{{ $product->id }}"
        data-url="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}">

        <div class="product-thumb bg-white relative overflow-hidden rounded-2xl">

            {{-- Badge --}}
            @if ($product->is_featured)
                <div class="product-tag text-button-uppercase bg-green px-3 py-0.5 inline-block rounded-full absolute top-3 left-3 z-[1]">
                    {{ __('New') }}
                </div>
            @elseif($product->old_price)
                <div class="product-tag text-button-uppercase bg-red px-3 py-0.5 inline-block absolute top-3 left-3 z-[1] text-white">
                    -{{ $product->discount_percent }}%
                </div>
            @endif

            {{-- Wishlist Button --}}
            <div class="list-action-right absolute top-3 right-3">
                @auth
                    @php $wishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                    <div class="wishlist-btn w-[32px] h-[32px] flex items-center justify-center rounded-full bg-white cursor-pointer relative"
                        data-url="{{ route(app()->getLocale() === 'ar' ? 'wishlist.toggle' : 'en.wishlist.toggle', $product) }}">
                        <i class="{{ $wishlisted ? 'ph-fill text-red-500' : 'ph text-secondary' }} ph-heart text-lg"></i>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="w-[32px] h-[32px] flex items-center justify-center rounded-full bg-white">
                        <i class="ph ph-heart text-lg text-secondary"></i>
                    </a>
                @endauth
            </div>

            {{-- Product Images --}}
            @php
                $mainImage   = $product->image;
                $secondImage = $product->images->first()->image ?? $mainImage;
            @endphp
            <div class="product-img w-full h-full aspect-[3/4]">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}">
                    <img src="{{ asset('storage/' . $mainImage) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover duration-700"
                         loading="lazy"
                         decoding="async">
                    <img src="{{ asset('storage/' . $secondImage) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover duration-700"
                         loading="lazy"
                         decoding="async">
                </a>
            </div>

            {{-- Hover Actions --}}
            <div class="list-action grid grid-cols-2 gap-3 px-5 absolute w-full bottom-5">
                <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}"
                    class="quick-view-btn w-full text-button-uppercase py-2 text-center rounded-full duration-300 bg-white hover:bg-black hover:text-white">
                    <span class="max-sm:hidden">{{ __('Quick View') }}</span>
                    <i class="ph ph-eye sm:hidden text-xl"></i>
                </a>
                <button type="button"
                    class="add-cart-btn w-full text-button-uppercase py-2 text-center rounded-full duration-300 bg-white hover:bg-black hover:text-white cursor-pointer"
                    data-product-id="{{ $product->id }}">
                    <span class="max-sm:hidden">{{ __('Add to Cart') }}</span>
                    <i class="ph ph-shopping-bag-open sm:hidden text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Product Info --}}
        <div class="product-infor mt-4 lg:mb-7">
            @php
                $stock      = $product->stock ?? 0;
                $available  = max($stock, 0);
                $soldPercent = 0;
            @endphp
            <div class="product-sold sm:pb-4 pb-2">
                <div class="progress bg-line h-1.5 w-full rounded-full overflow-hidden relative">
                    <div class="progress-sold bg-red absolute left-0 top-0 h-full" style="width: {{ $soldPercent }}%"></div>
                </div>
                <div class="flex items-center justify-between gap-3 gap-y-1 flex-wrap mt-2">
                    <div class="text-button-uppercase">
                        <span class="text-secondary2 max-sm:text-xs">{{ __('Available') }}: </span>
                        <span class="max-sm:text-xs">{{ $available }}</span>
                    </div>
                    @if ($stock > 0 && $stock <= 5)
                        <div class="text-button-uppercase text-red-500 max-sm:text-xs">
                            {{ __('Only') }} {{ $stock }} {{ __('left') }}!
                        </div>
                    @endif
                </div>
            </div>

            <a href="{{ route(app()->getLocale() === 'ar' ? 'shop.show' : 'en.shop.show', $product) }}"
                class="product-name text-title duration-300 hover:underline block">
                {{ $product->name }}
            </a>

            @if ($product->images->count())
                <div class="list-color list-color-image max-md:hidden flex items-center gap-3 flex-wrap duration-500 mt-1">
                    @foreach ($product->images as $img)
                        @php $colorName = app()->getLocale() === 'ar' ? ($img->color_ar ?? null) : ($img->color_en ?? null); @endphp
                        @if ($colorName)
                            <div class="color-item w-12 h-12 rounded-xl duration-300 relative">
                                <img src="{{ asset('storage/' . $img->image) }}"
                                     alt="{{ $colorName }}"
                                     class="rounded-xl w-full h-full object-cover"
                                     loading="lazy"
                                     decoding="async">
                                <div class="tag-action bg-black text-white caption2 capitalize px-1.5 py-0.5 rounded-sm">
                                    {{ $colorName }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

           <div class="product-price-block flex items-center gap-2 flex-wrap mt-1 duration-300 relative z-[1]">
    @if ($product->price)
        <div class="product-price text-title">{{ number_format($product->price, 2) }} EGP</div>
        @if ($product->old_price)
            <div class="product-origin-price caption1 text-secondary2">
                <del>{{ number_format($product->old_price, 2) }} EGP</del>
            </div>
            <div class="product-sale caption1 font-medium bg-green px-3 py-0.5 inline-block rounded-full">
                -{{ $product->discount_percent }}%
            </div>
        @endif
    @else
        <div class="product-price text-title">
            {{ app()->getLocale() === 'ar' ? 'السعر حسب الطلب' : 'Price on Request' }}
        </div>
    @endif
</div>
        </div>
    </div>
</div>
