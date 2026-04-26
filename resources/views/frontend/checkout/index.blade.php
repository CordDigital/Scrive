@extends('frontend.layouts.app')

@push('styles')
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-6px); }
    40%, 80% { transform: translateX(6px); }
}
input.checkout-invalid,
textarea.checkout-invalid,
select.checkout-invalid {
    border: 2px solid #ef4444 !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3) !important;
}
.checkout-err-msg {
    color: #ef4444;
    font-size: 12px;
    font-weight: 600;
    margin-top: 6px;
}
</style>
@endpush

@section('content')

<div class="breadcrumb-block style-shared">
    <div class="breadcrumb-main bg-linear overflow-hidden">
        <div class="container lg:pt-[134px] pt-24 pb-10 relative">
            <div class="main-content w-full h-full flex flex-col items-center justify-center relative z-[1]">
                <div class="heading2 text-center">{{ __('Checkout') }}</div>
                <div class="link flex items-center justify-center gap-1 caption1 mt-3">
                    <a href="{{ route('home') }}">{{ __('Homepage') }}</a>
                    <i class="ph ph-caret-right text-sm text-secondary2"></i>
                    <span class="text-secondary2">{{ __('Checkout') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="checkout-block md:py-20 py-10">
    <div class="container">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route(app()->getLocale() === 'ar' ? 'checkout.store' : 'en.checkout.store') }}"
              method="POST" id="checkoutForm">
            @csrf

            <div class="content-main flex max-lg:flex-col-reverse gap-y-10 justify-between">

                {{-- ── Left: Delivery + Payment ── --}}
                <div class="left lg:w-1/2">

                    {{-- Delivery Info --}}
                    <div class="information">
                        <div class="heading5">{{ __('Delivery Information') }}</div>
                        <div class="form-checkout mt-5">
                            <div class="grid sm:grid-cols-2 gap-4 gap-y-5">

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('first_name') border-red-400 @enderror"
                                           name="first_name" type="text"
                                           placeholder="{{ __('First Name') }} *"
                                           value="{{ old('first_name', auth()->user()->name) }}" required />
                                    @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('last_name') border-red-400 @enderror"
                                           name="last_name" type="text"
                                           placeholder="{{ __('Last Name') }} *"
                                           value="{{ old('last_name') }}" required />
                                    @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('email') border-red-400 @enderror"
                                           name="email" type="email"
                                           placeholder="{{ __('Email Address') }} *"
                                           value="{{ old('email', auth()->user()->email) }}" required />
                                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('phone') border-red-400 @enderror"
                                           name="phone" type="tel"
                                           placeholder="{{ __('Phone Number') }} *"
                                           value="{{ old('phone') }}" required />
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('country') border-red-400 @enderror"
                                           name="country" type="text"
                                           placeholder="{{ __('Country') }} *"
                                           value="{{ old('country') }}" required />
                                    @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('city') border-red-400 @enderror"
                                           name="city" type="text"
                                           placeholder="{{ __('City') }} *"
                                           value="{{ old('city') }}" required />
                                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="col-span-full">
                                    <input class="border-line px-4 py-3 w-full rounded-lg @error('address') border-red-400 @enderror"
                                           name="address" type="text"
                                           placeholder="{{ __('Street Address') }} *"
                                           value="{{ old('address') }}" required />
                                    @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <input class="border-line px-4 py-3 w-full rounded-lg"
                                           name="postal_code" type="text"
                                           placeholder="{{ __('Postal Code') }}"
                                           value="{{ old('postal_code') }}" />
                                </div>

                                <div class="col-span-full">
                                    <textarea class="border border-line px-4 py-3 w-full rounded-lg"
                                              name="note" rows="3"
                                              placeholder="{{ __('Order note (optional)') }}">{{ old('note') }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="payment-block md:mt-10 mt-6">
                        <div class="heading5">{{ __('Payment Method') }}</div>

                        <div class="list-payment mt-5 flex flex-col gap-4">

                            {{-- Cash on Delivery --}}
                            <label class="type bg-surface p-5 border border-line rounded-lg cursor-pointer duration-200"
                                   id="label-cash">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="cash" id="pay-cash"
                                           {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }} />
                                    <div class="flex items-center gap-2">
                                        <i class="ph ph-money text-xl"></i>
                                        <span class="text-button">{{ __('Cash on Delivery') }}</span>
                                    </div>
                                </div>
                                <div class="pay-detail text-secondary text-sm mt-3 {{ old('payment_method', 'cash') !== 'cash' ? 'hidden' : '' }}">
                                    {{ __('Pay with cash upon delivery of your order.') }}
                                </div>
                            </label>

                            {{-- Credit Card / Stripe --}}
                            <label class="type bg-surface p-5 border border-line rounded-lg cursor-pointer duration-200"
                                   id="label-credit">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="credit_card" id="pay-credit"
                                           {{ old('payment_method') === 'credit_card' ? 'checked' : '' }} />
                                    <div class="flex items-center gap-2">
                                        <i class="ph ph-credit-card text-xl"></i>
                                        <span class="text-button">{{ __('Credit Card') }}</span>
                                        <img src="{{ asset('assets/images/payment/visa.png') }}" alt="visa" class="h-5" onerror="this.remove()">
                                        <img src="{{ asset('assets/images/payment/master.png') }}" alt="master" class="h-5" onerror="this.remove()">
                                    </div>
                                </div>
                                <div class="pay-detail mt-3 {{ old('payment_method') !== 'credit_card' ? 'hidden' : '' }}">
                                    {{-- Stripe card element --}}
                                    <div id="stripe-card-element" class="border-line px-4 py-3 rounded-lg bg-white"></div>
                                    <div id="stripe-card-errors" class="text-red-500 text-xs mt-2"></div>
                                </div>
                            </label>

                            {{-- PayPal --}}
                            <label class="type bg-surface p-5 border border-line rounded-lg cursor-pointer duration-200"
                                   id="label-paypal">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="paypal" id="pay-paypal"
                                           {{ old('payment_method') === 'paypal' ? 'checked' : '' }} />
                                    <div class="flex items-center gap-2">
                                        <i class="ph ph-currency-circle-dollar text-xl"></i>
                                        <span class="text-button">PayPal</span>
                                    </div>
                                </div>
                                <div class="pay-detail text-secondary text-sm mt-3 {{ old('payment_method') !== 'paypal' ? 'hidden' : '' }}">
                                    {{ __('You will be redirected to PayPal to complete payment.') }}
                                </div>
                            </label>

                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="block-button md:mt-10 mt-6">
                        <button type="button" id="placeOrderBtn" class="button-main w-full relative">
                            <span id="placeOrderText">{{ __('Place Order') }}</span>
                            <span id="placeOrderSpinner" class="hidden ml-2">
                                <i class="ph ph-spinner animate-spin"></i>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- ── Right: Order Summary ── --}}
                <div class="right lg:w-5/12">
                    <div class="checkout-block bg-surface p-6 rounded-2xl sticky top-28">
                        <div class="heading5 pb-4 border-b border-line">{{ __('Your Order') }}</div>

                        <div class="list-product-checkout mt-4 flex flex-col gap-4">
                            @foreach($items as $item)
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ Storage::url($item['image']) }}"
                                         alt="{{ $item['name'] }}"
                                         class="w-16 h-16 object-cover rounded-xl">
                                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-black text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $item['quantity'] }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <div class="text-title text-sm font-semibold">{{ $item['name'] }}</div>
                                    @if($item['size'] || $item['color'])
                                    <div class="caption2 text-secondary">
                                        {{ $item['size'] }}{{ $item['color'] ? ' / '.$item['color'] : '' }}
                                    </div>
                                    @endif
                                </div>
                                <div class="text-title font-semibold text-sm">
                                   EGP{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-line mt-5 pt-4 flex flex-col gap-3">
                            <div class="flex justify-between">
                                <span class="text-title">{{ __('Subtotal') }}</span>
                                <span class="text-title font-semibold">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="flex justify-between">
                                <span class="text-title">{{ __('Discount') }}
                                    @if($coupon) <span class="caption2 text-green-600">({{ $coupon['code'] }})</span> @endif
                                </span>
                                <span class="text-title font-semibold text-red-500">-${{ number_format($discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-title">{{ __('Shipping') }}</span>
                                <span class="text-green-600 font-semibold">{{ __('Free') }}</span>
                            </div>
                            <div class="flex justify-between pt-3 border-t border-line">
                                <span class="heading5">{{ __('Total') }}</span>
                                <span class="heading5">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
{{-- Stripe.js --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locale = document.documentElement.lang || 'ar';

    // ── Payment method toggle ─────────────────────────────────────
    document.querySelectorAll('input[name="payment_method"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.pay-detail').forEach(d => d.classList.add('hidden'));
            this.closest('.type').querySelector('.pay-detail').classList.remove('hidden');

            // Update border
            document.querySelectorAll('.type').forEach(t => t.classList.remove('border-black'));
            this.closest('.type').classList.add('border-black');
        });
        // Init active border
        if (radio.checked) {
            radio.closest('.type').classList.add('border-black');
        }
    });

    // ── Stripe setup ──────────────────────────────────────────────
    const stripeKey = '{{ env("STRIPE_KEY") }}';
    let stripe, cardElement, stripeReady = false;

    if (stripeKey) {
        stripe = Stripe(stripeKey);
        const elements = stripe.elements();
        cardElement = elements.create('card', {
            style: { base: { fontSize: '16px', color: '#111' } }
        });
        cardElement.mount('#stripe-card-element');
        stripeReady = true;
    }

    // ── Validate required fields ───────────────────────────────────
    function validateForm() {
        let valid = true;
        const form = document.getElementById('checkoutForm');
        form.querySelectorAll('input[required], textarea[required], select[required]').forEach(function(field) {
            // Remove old message
            const oldMsg = field.parentElement.querySelector('.checkout-err-msg');
            if (oldMsg) oldMsg.remove();

            if (!field.value.trim()) {
                field.classList.add('checkout-invalid');
                field.style.animation = 'none';
                field.offsetHeight;
                field.style.animation = 'shake 0.4s ease';
                // Add error message
                const msg = document.createElement('p');
                msg.className = 'checkout-err-msg';
                msg.textContent = field.placeholder ? field.placeholder.replace(' *', '') + (locale === 'en' ? ' is required' : ' مطلوب') : (locale === 'en' ? 'This field is required' : 'هذا الحقل مطلوب');
                field.parentElement.appendChild(msg);
                valid = false;
            } else {
                field.classList.remove('checkout-invalid');
            }
        });
        if (!valid) {
            const firstInvalid = form.querySelector('.checkout-invalid');
            if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return valid;
    }

    // Remove red highlight + message on input
    document.getElementById('checkoutForm').addEventListener('input', function(e) {
        if (e.target.matches('input, textarea, select')) {
            e.target.classList.remove('checkout-invalid');
            const msg = e.target.parentElement.querySelector('.checkout-err-msg');
            if (msg) msg.remove();
        }
    });

    // ── Place Order ───────────────────────────────────────────────
    document.getElementById('placeOrderBtn').addEventListener('click', async function () {
        const method = document.querySelector('input[name="payment_method"]:checked')?.value;
        const btn    = this;

        if (!validateForm()) return;

        btn.disabled = true;
        document.getElementById('placeOrderSpinner').classList.remove('hidden');

        if (method === 'paypal') {
            // Submit form → server will redirect to PayPal
            document.getElementById('checkoutForm').submit();
            return;
        }

        if (method === 'credit_card' && stripeReady) {
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement
            });
            if (error) {
                document.getElementById('stripe-card-errors').textContent = error.message;
                btn.disabled = false;
                document.getElementById('placeOrderSpinner').classList.add('hidden');
                return;
            }
            // Send to Stripe charge route
            const formData = new FormData(document.getElementById('checkoutForm'));
            formData.append('payment_method_id', paymentMethod.id);
            const resp = await fetch('{{ route(app()->getLocale() === "ar" ? "payment.stripe" : "en.payment.stripe") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await resp.json();
            if (data.success && data.redirect) {
                window.location.href = data.redirect;
            } else {
                document.getElementById('stripe-card-errors').textContent = data.message || (locale === 'en' ? 'Payment failed' : 'فشل الدفع');
                btn.disabled = false;
                document.getElementById('placeOrderSpinner').classList.add('hidden');
            }
            return;
        }

        // Cash → normal form submit
        document.getElementById('checkoutForm').submit();
    });
});
</script>
@endpush
