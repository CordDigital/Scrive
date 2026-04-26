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

  <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Checkout</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Checkout Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h4 class="font-weight-semi-bold mb-4">Billing Address</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input class="form-control" type="text" placeholder="John">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input class="form-control" type="text" placeholder="Doe">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" type="text" placeholder="example@email.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" type="text" placeholder="+123 456 789">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 1</label>
                            <input class="form-control" type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 2</label>
                            <input class="form-control" type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Country</label>
                            <select class="custom-select">
                                <option selected>United States</option>
                                <option>Afghanistan</option>
                                <option>Albania</option>
                                <option>Algeria</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>City</label>
                            <input class="form-control" type="text" placeholder="New York">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>State</label>
                            <input class="form-control" type="text" placeholder="New York">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ZIP Code</label>
                            <input class="form-control" type="text" placeholder="123">
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="newaccount">
                                <label class="custom-control-label" for="newaccount">Create an account</label>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="shipto">
                                <label class="custom-control-label" for="shipto"  data-toggle="collapse" data-target="#shipping-address">Ship to different address</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse mb-4" id="shipping-address">
                    <h4 class="font-weight-semi-bold mb-4">Shipping Address</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input class="form-control" type="text" placeholder="John">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input class="form-control" type="text" placeholder="Doe">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>E-mail</label>
                            <input class="form-control" type="text" placeholder="example@email.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Mobile No</label>
                            <input class="form-control" type="text" placeholder="+123 456 789">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 1</label>
                            <input class="form-control" type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Address Line 2</label>
                            <input class="form-control" type="text" placeholder="123 Street">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Country</label>
                            <select class="custom-select">
                                <option selected>United States</option>
                                <option>Afghanistan</option>
                                <option>Albania</option>
                                <option>Algeria</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>City</label>
                            <input class="form-control" type="text" placeholder="New York">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>State</label>
                            <input class="form-control" type="text" placeholder="New York">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>ZIP Code</label>
                            <input class="form-control" type="text" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Order Total</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-medium mb-3">Products</h5>
                        <div class="d-flex justify-content-between">
                            <p>Colorful Stylish Shirt 1</p>
                            <p>$150</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Colorful Stylish Shirt 2</p>
                            <p>$150</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Colorful Stylish Shirt 3</p>
                            <p>$150</p>
                        </div>
                        <hr class="mt-0">
                        <div class="d-flex justify-content-between mb-3 pt-1">
                            <h6 class="font-weight-medium">Subtotal</h6>
                            <h6 class="font-weight-medium">$150</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Shipping</h6>
                            <h6 class="font-weight-medium">$10</h6>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <div class="d-flex justify-content-between mt-2">
                            <h5 class="font-weight-bold">Total</h5>
                            <h5 class="font-weight-bold">$160</h5>
                        </div>
                    </div>
                </div>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Payment</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="paypal">
                                <label class="custom-control-label" for="paypal">Paypal</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="directcheck">
                                <label class="custom-control-label" for="directcheck">Direct Check</label>
                            </div>
                        </div>
                        <div class="">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment" id="banktransfer">
                                <label class="custom-control-label" for="banktransfer">Bank Transfer</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-secondary bg-transparent">
                        <button class="btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Checkout End -->

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
