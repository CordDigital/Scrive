@extends('frontend.layouts.app')

@push('styles')
<style>
.cart-wrap { padding: 60px 0 80px; background: #f9f9f9; min-height: 60vh; }
.cart-grid { display: grid; grid-template-columns: 1fr 380px; gap: 28px; align-items: start; }
@media(max-width:1100px){ .cart-grid { grid-template-columns: 1fr; } }

/* ── Items Card ── */
.cart-card { background: #fff; border-radius: 20px; border: 1px solid #efefef; overflow: hidden; }
.cart-card-head {
    display: grid; grid-template-columns: 2.5fr 1fr 1fr 1fr 40px;
    padding: 14px 24px; background: #fafafa; border-bottom: 1px solid #f0f0f0;
    font-size: 11px; font-weight: 700; color: #aaa; text-transform: uppercase; letter-spacing: .6px;
}
@media(max-width:700px){ .cart-card-head { display: none; } }

.cart-item {
    display: grid; grid-template-columns: 2.5fr 1fr 1fr 1fr 40px;
    padding: 20px 24px; border-bottom: 1px solid #f5f5f5; align-items: center;
    transition: background .15s;
}
.cart-item:last-child { border-bottom: none; }
.cart-item:hover { background: #fdfcfc; }
@media(max-width:700px){
    .cart-item { grid-template-columns: 1fr 1fr; grid-template-rows: auto auto; gap: 12px; }
}

.cart-product { display: flex; align-items: center; gap: 14px; }
.cart-img { width: 72px; height: 72px; border-radius: 14px; object-fit: cover; border: 1px solid #f0f0f0; flex-shrink: 0; }
.cart-name { font-size: 14px; font-weight: 700; color: #111; line-height: 1.4; }
.cart-variant { font-size: 12px; color: #aaa; margin-top: 3px; }
.cart-price { font-size: 14px; font-weight: 600; color: #555; text-align: center; }
.cart-total-cell { font-size: 15px; font-weight: 800; color: #111; text-align: center; }

/* Qty */
.cart-qty { display: flex; align-items: center; justify-content: center; }
.qty-wrap { display: flex; align-items: center; border: 1.5px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
.qty-btn {
    width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
    background: none; border: none; cursor: pointer; font-size: 18px; font-weight: 300;
    color: #555; transition: background .15s;
}
.qty-btn:hover { background: #f5f5f5; }
.qty-val { width: 36px; text-align: center; font-size: 14px; font-weight: 700; color: #111; border-left: 1.5px solid #e5e7eb; border-right: 1.5px solid #e5e7eb; height: 36px; line-height: 36px; }

/* Remove */
.remove-cart-btn {
    width: 32px; height: 32px; border-radius: 8px; border: none; background: #fef2f2;
    color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: 16px; transition: background .2s; margin: auto;
}
.remove-cart-btn:hover { background: #fee2e2; }

/* ── Coupon ── */
.coupon-wrap { padding: 20px 24px; border-top: 1px solid #f0f0f0; background: #fff; }
.coupon-form { display: flex; gap: 10px; max-width: 420px; }
.coupon-input {
    flex: 1; padding: 11px 18px; border: 1.5px solid #e5e7eb; border-radius: 12px;
    font-size: 14px; outline: none; transition: border-color .2s; background: #fafafa;
}
.coupon-input:focus { border-color: #111; background: #fff; }
.coupon-btn {
    padding: 11px 22px; background: #111; color: #fff; border: none;
    border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; transition: background .2s; white-space: nowrap;
}
.coupon-btn:hover { background: #333; }

/* ── Summary ── */
.summary-card { background: #fff; border-radius: 20px; border: 1px solid #efefef; padding: 28px; position: sticky; top: 100px; }
.summary-title { font-size: 17px; font-weight: 800; color: #111; margin-bottom: 22px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0; }
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; font-size: 14px; }
.summary-label { color: #888; font-weight: 500; }
.summary-val { font-weight: 700; color: #111; }
.summary-discount { color: #16a34a; }
.summary-divider { height: 1px; background: #f0f0f0; margin: 16px 0; }
.summary-total-row { display: flex; justify-content: space-between; align-items: center; margin-top: 6px; }
.summary-total-label { font-size: 16px; font-weight: 800; color: #111; }
.summary-total-val { font-size: 24px; font-weight: 900; color: #111; }

.checkout-btn {
    display: block; width: 100%; text-align: center; background: #111; color: #fff;
    padding: 16px; border-radius: 14px; font-size: 15px; font-weight: 800;
    margin-top: 22px; text-decoration: none; transition: all .25s; letter-spacing: .3px;
}
.checkout-btn:hover { background: #333; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,.15); color: #fff; }
.back-shop {
    display: flex; align-items: center; justify-content: center; gap: 6px;
    margin-top: 14px; font-size: 13px; font-weight: 600; color: #888;
    text-decoration: none; transition: color .2s;
}
.back-shop:hover { color: #111; }

.trust-row { display: flex; justify-content: center; gap: 20px; margin-top: 22px; padding-top: 18px; border-top: 1px solid #f0f0f0; }
.trust-item { display: flex; flex-direction: column; align-items: center; gap: 4px; font-size: 11px; color: #bbb; font-weight: 600; }
.trust-item i { font-size: 20px; color: #ccc; }

/* ── Empty ── */
.cart-empty { text-align: center; padding: 80px 24px; }
.cart-empty i { font-size: 56px; color: #ddd; display: block; margin-bottom: 16px; }
.cart-empty p { font-size: 16px; color: #aaa; font-weight: 500; margin-bottom: 24px; }
</style>
@endpush

@section('content')


    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shopping Cart</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shopping Cart</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive mb-5">
                <table class="table table-bordered text-center mb-0">
                    <thead class="bg-secondary text-dark">
                        <tr>
                            <th>Products</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        <tr>
                            <td class="align-middle"><img src="img/product-1.jpg" alt="" style="width: 50px;"> Colorful Stylish Shirt</td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <tr>
                            <td class="align-middle"><img src="img/product-2.jpg" alt="" style="width: 50px;"> Colorful Stylish Shirt</td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <tr>
                            <td class="align-middle"><img src="img/product-3.jpg" alt="" style="width: 50px;"> Colorful Stylish Shirt</td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <tr>
                            <td class="align-middle"><img src="img/product-4.jpg" alt="" style="width: 50px;"> Colorful Stylish Shirt</td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button></td>
                        </tr>
                        <tr>
                            <td class="align-middle"><img src="img/product-5.jpg" alt="" style="width: 50px;"> Colorful Stylish Shirt</td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-minus" >
                                        <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm bg-secondary text-center" value="1">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-primary btn-plus">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle"><button class="btn btn-sm btn-primary"><i class="fa fa-times"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <form class="mb-5" action="">
                    <div class="input-group">
                        <input type="text" class="form-control p-4" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form>
                <div class="card border-secondary mb-5">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0">Cart Summary</h4>
                    </div>
                    <div class="card-body">
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
                        <button class="btn btn-block btn-primary my-3 py-3">Proceed To Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function showToast(msg, type = 'success'){
        let t = document.getElementById('cart-toast');
        if(!t){
            t = document.createElement('div');
            t.id = 'cart-toast';
            t.style.cssText = 'position:fixed;bottom:28px;right:28px;z-index:9999;padding:14px 20px;border-radius:14px;font-size:14px;font-weight:700;transition:all .3s;opacity:0;transform:translateY(10px);display:flex;align-items:center;gap:8px;color:#fff;';
            document.body.appendChild(t);
        }
        t.style.background = type === 'success' ? '#111' : '#ef4444';
        t.innerHTML = `<i class="ph-bold ph-${type==='success'?'check-circle':'warning-circle'}" style="font-size:18px;"></i> ${msg}`;
        t.style.opacity = '1'; t.style.transform = 'translateY(0)';
        clearTimeout(t._t);
        t._t = setTimeout(()=>{ t.style.opacity='0'; t.style.transform='translateY(10px)'; }, 3000);
    }

    function updateCartRow(row, qty, itemTotal){
        row.querySelector('.qty-val').textContent = qty;
        row.querySelector('.qty-minus').dataset.qty = qty - 1;
        row.querySelector('.qty-plus').dataset.qty  = qty + 1;
        row.querySelector('.item-total').textContent = parseFloat(itemTotal).toLocaleString('en-US', {minimumFractionDigits:2});
    }

    function updateCartSummary(subtotal, total){
        const sub = parseFloat(subtotal), tot = parseFloat(total);
        document.querySelector('.total-product').textContent = sub.toLocaleString('en-US', {minimumFractionDigits:2});
        document.querySelector('.total-cart').textContent    = tot.toLocaleString('en-US', {minimumFractionDigits:2});
        document.querySelector('.discount').textContent      = (sub - tot).toLocaleString('en-US', {minimumFractionDigits:2});
    }

    // Qty + Remove
    document.querySelector('.cart-table-section').addEventListener('click', function(e){
        const target = e.target.closest('button');
        if(!target) return;
        const row = target.closest('.cart-row');

        if(target.classList.contains('qty-plus') || target.classList.contains('qty-minus')){
            const qty = parseInt(target.dataset.qty);
            if(qty <= 0){ removeItem(row); return; }
            const price = parseFloat(row.dataset.price);
            updateCartRow(row, qty, price * qty);
            fetch(target.dataset.url, {
                method:'PATCH',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify({quantity: qty})
            }).then(r=>r.json()).then(data=>{
                updateCartRow(row, qty, data.itemTotal);
                updateCartSummary(data.subtotal, data.total);
            });
        }

        if(target.classList.contains('remove-cart-btn')) removeItem(row);
    });

    function removeItem(row){
        const url = row.querySelector('.remove-cart-btn').dataset.url;
        row.style.opacity = '0.4'; row.style.pointerEvents = 'none';
        fetch(url,{ method:'DELETE', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'} })
        .then(r=>r.json()).then(data=>{
            if(data && data.success){
                row.style.transform = 'translateX(30px)';
                setTimeout(()=>{ row.remove(); updateCartSummary(data.subtotal, data.total); }, 280);
                showToast('{{ app()->getLocale()==="ar"?"تم الحذف":"Item removed" }}');
            }
        });
    }

    // Coupon
    const couponForm = document.getElementById('coupon-form');
    if(couponForm){
        couponForm.addEventListener('submit', function(e){
            e.preventDefault();
            const code = this.querySelector('input[name="code"]').value.trim();
            if(!code) return;
            const url = '{{ route(app()->getLocale() === "ar" ? "cart.coupon" : "en.cart.coupon") }}';
            fetch(url, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},
                body: JSON.stringify({code})
            }).then(r=>r.json()).then(data=>{
                if(data.success){
                    document.querySelector('.discount').textContent = parseFloat(data.discount).toLocaleString('en-US',{minimumFractionDigits:2});
                    document.querySelector('.total-cart').textContent = parseFloat(data.total).toLocaleString('en-US',{minimumFractionDigits:2});
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            }).catch(()=>showToast('Error','error'));
        });
    }
});
</script>
@endpush
