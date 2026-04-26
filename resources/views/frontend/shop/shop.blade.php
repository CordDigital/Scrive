@extends('frontend.layouts.app')

@push('styles')
<style>
/* ── Breadcrumb tabs ── */
.filter-type .tab-item { font-size: 12px; font-weight: 700; letter-spacing: .8px; padding-bottom: 6px; color: #888; transition: color .2s; }
.filter-type .tab-item.active,
.filter-type .tab-item:hover { color: #000000; }
.filter-type .tab-item.active { border-bottom: 2px solid #fff; }

/* ── Layout ── */
.shop-layout { display: grid; grid-template-columns: 260px 1fr; gap: 36px; padding: 48px 0 80px; }

/* ── Sidebar ── */
.shop-sidebar { position: sticky; top: 100px; align-self: start; }

/* ── Filter Toggle Button (mobile only) ── */
.filter-toggle-btn {
    display: none; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 700; color: #111; background: #fff;
    border: 1.5px solid #e5e7eb; padding: 8px 16px; border-radius: 10px;
    cursor: pointer; transition: all .15s;
}
.filter-toggle-btn:hover, .filter-toggle-btn.active { border-color: #111; background: #111; color: #fff; }
.filter-toggle-btn .filter-arrow { transition: transform .3s; }
.filter-toggle-btn.active .filter-arrow { transform: rotate(180deg); }

/* ── Mobile: sidebar becomes collapsible dropdown ── */
@media(max-width:900px) {
    .shop-layout { grid-template-columns: 1fr; gap: 0; }
    .filter-toggle-btn { display: flex; }
    .shop-sidebar {
        display: none; position: static;
        background: #fff; border: 1px solid #f0f0f0; border-radius: 14px;
        padding: 20px 24px; margin-bottom: 22px;
    }
    .shop-sidebar.open { display: block; }
}
.sb-section { margin-bottom: 30px; padding-bottom: 28px; border-bottom: 1px solid #f0f0f0; }
.sb-section:last-child { border-bottom: none; margin-bottom: 0; }
.sb-heading { font-size: 13px; font-weight: 800; color: #111; text-transform: uppercase; letter-spacing: .8px; margin-bottom: 14px; }

/* Categories */
.sb-cat-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 10px; border-radius: 10px; font-size: 13px; color: #666;
    text-decoration: none; transition: background .15s, color .15s; cursor: pointer;
}
.sb-cat-item:hover, .sb-cat-item.active { background: #f5f5f5; color: #111; font-weight: 600; }
.sb-cat-count { font-size: 11px; color: #bbb; background: #f3f4f6; padding: 1px 7px; border-radius: 20px; }

/* Sizes */
.sb-sizes { display: flex; flex-wrap: wrap; gap: 8px; }
.sb-size {
    width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
    border: 1.5px solid #e5e7eb; border-radius: 10px; font-size: 12px; font-weight: 600;
    color: #555; text-decoration: none; transition: all .15s; cursor: pointer;
}
.sb-size.freesize { width: auto; padding: 0 12px; }
.sb-size:hover, .sb-size.active { border-color: #111; background: #111; color: #fff; }

/* Price */
.price-display { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: #555; font-weight: 600; margin-bottom: 10px; }
.price-val-badge { background: #f3f4f6; border-radius: 8px; padding: 4px 10px; font-weight: 700; color: #111; font-size: 13px; }
.price-track { position: relative; height: 4px; background: #e5e7eb; border-radius: 4px; margin: 20px 0 10px; }
.price-track-fill { position: absolute; height: 100%; background: #111; border-radius: 4px; pointer-events: none; }
.price-range-input {
    width: 100%; -webkit-appearance: none; appearance: none;
    height: 4px; background: transparent; outline: none; margin: 0;
    position: absolute; top: 0; left: 0; pointer-events: none;
}
.price-range-input::-webkit-slider-thumb {
    -webkit-appearance: none; appearance: none; pointer-events: all;
    width: 20px; height: 20px; border-radius: 50%;
    background: #fff; border: 2.5px solid #111; cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,.15); transition: border-color .15s;
}
.price-range-input::-webkit-slider-thumb:hover { border-color: #333; }
.price-range-input::-moz-range-thumb {
    pointer-events: all; width: 20px; height: 20px; border-radius: 50%;
    background: #fff; border: 2.5px solid #111; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,.15);
}
.price-apply-btn {
    margin-top: 14px; font-size: 12px; font-weight: 700; color: #111;
    background: none; border: 1.5px solid #111; padding: 7px 18px;
    border-radius: 8px; cursor: pointer; transition: all .15s; display: block; width: 100%;
}
.price-apply-btn:hover { background: #111; color: #fff; }

/* ── Filter Bar ── */
.filter-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; background: #fff; border-radius: 14px;
    border: 1px solid #f0f0f0; margin-bottom: 22px; flex-wrap: wrap; gap: 10px;
}
.filter-count { font-size: 13px; color: #888; font-weight: 500; }
.filter-count span { font-weight: 800; color: #111; }
.filter-bar-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

.sale-check-label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: #555; cursor: pointer; }
.sale-check-label input { width: 15px; height: 15px; accent-color: #111; cursor: pointer; }

.sort-select {
    padding: 8px 36px 8px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: 13px; font-weight: 600; color: #111; background: #fff;
    appearance: none; cursor: pointer; outline: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23999'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    transition: border-color .15s;
}
.sort-select:focus { border-color: #111; }

/* ── Product Card ── */
.products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media(max-width:1100px){ .products-grid { grid-template-columns: repeat(2,1fr); } }
@media(max-width:500px){  .products-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; } }

.shop-card { border-radius: 18px; overflow: hidden; background: #fff; border: 1px solid #f0f0f0; transition: box-shadow .25s, transform .25s; }
.shop-card:hover { box-shadow: 0 10px 36px rgba(0,0,0,.08); transform: translateY(-3px); }

.shop-card-thumb { position: relative; aspect-ratio: 3/4; overflow: hidden; background: #f8f8f8; }
.shop-card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s cubic-bezier(.25,.46,.45,.94); }
.shop-card-thumb .img-main  { position: absolute; inset: 0; }
.shop-card-thumb .img-hover { position: absolute; inset: 0; opacity: 0; transition: opacity .4s; }
.shop-card:hover .img-hover { opacity: 1; }
.shop-card:hover .img-main  { opacity: 0; }
.shop-card:hover .shop-card-thumb img { transform: scale(1.04); }

.shop-card-badge {
    position: absolute; top: 12px; left: 12px; z-index: 2;
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: .3px;
}
.badge-new  { background: #111; color: #fff; }
.badge-sale { background: #ef4444; color: #fff; }

.shop-card-wish {
    position: absolute; top: 12px; right: 12px; z-index: 2;
    width: 32px; height: 32px; border-radius: 50%; background: #fff;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s; text-decoration: none; cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.shop-card:hover .shop-card-wish { opacity: 1; }

.shop-card-actions {
    position: absolute; bottom: 0; left: 0; right: 0; padding: 12px;
    display: flex; gap: 8px;
    transform: translateY(100%); transition: transform .3s cubic-bezier(.25,.46,.45,.94);
    background: linear-gradient(to top, rgba(0,0,0,.04), transparent);
}
.shop-card:hover .shop-card-actions { transform: translateY(0); }

.action-btn {
    flex: 1; padding: 9px 8px; border-radius: 10px; font-size: 12px; font-weight: 700;
    text-align: center; cursor: pointer; transition: all .2s; border: none; text-decoration: none;
    display: flex; align-items: center; justify-content: center; gap: 4px;
}
.action-view, .action-cart { background: #fff; color: #111; }
.action-view:hover, .action-cart:hover { background: #111; color: #fff; }

.shop-card-body { padding: 14px 16px 16px; }
.shop-card-name { font-size: 14px; font-weight: 700; color: #111; line-height: 1.4; text-decoration: none; display: block; }
.shop-card-name:hover { color: #555; }

.shop-card-colors { display: flex; gap: 5px; margin-top: 7px; flex-wrap: wrap; }
.shop-card-color-img { width: 26px; height: 26px; border-radius: 6px; object-fit: cover; border: 1.5px solid #e5e7eb; cursor: pointer; transition: border-color .15s; }
.shop-card-color-img:hover { border-color: #111; }

.shop-card-price-row { display: flex; align-items: center; gap: 8px; margin-top: 8px; flex-wrap: wrap; }
.shop-card-price { font-size: 15px; font-weight: 800; color: #111; }
.shop-card-old   { font-size: 13px; color: #bbb; text-decoration: line-through; }
.shop-card-discount { font-size: 11px; font-weight: 700; background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 20px; }

.shop-card-stock { font-size: 11px; color: #bbb; margin-top: 6px; }
.shop-card-stock.low { color: #ef4444; font-weight: 600; }

/* Quick shop popup */
.quick-shop-popup {
    position: absolute; inset: 0; background: rgba(255,255,255,.97);
    border-radius: 18px; padding: 18px; z-index: 10;
    display: none; flex-direction: column; align-items: center; justify-content: center; gap: 12px;
}
.quick-shop-sizes { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; }
.qs-size {
    width: 38px; height: 38px; border-radius: 8px; border: 1.5px solid #e5e7eb;
    display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600;
    cursor: pointer; transition: all .15s; color: #555;
}
.qs-size:hover, .qs-size.active { border-color: #111; background: #111; color: #fff; }
.qs-close { position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 18px; cursor: pointer; color: #999; }

/* ── Pagination ── */
.shop-pagination { display: flex; justify-content: center; margin-top: 40px; }
.shop-pagination .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; }
.shop-pagination .page-item .page-link {
    width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
    border-radius: 10px; font-size: 13px; font-weight: 600; color: #555;
    border: 1.5px solid #e5e7eb; text-decoration: none; transition: all .15s;
}
.shop-pagination .page-item.active .page-link,
.shop-pagination .page-item .page-link:hover { border-color: #111; background: #111; color: #fff; }
.shop-pagination .page-item.disabled .page-link { opacity: .4; pointer-events: none; }

/* ── Empty ── */
.shop-empty { text-align: center; padding: 80px 0; }
.shop-empty i { font-size: 52px; color: #ddd; display: block; margin-bottom: 14px; }
.shop-empty p { font-size: 15px; color: #aaa; margin-bottom: 20px; }
</style>
@endpush

@section('content')
 <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Our Shop</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


<!-- Shop Start -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-12">
                <!-- Price Start -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Filter by price</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="price-all">
                            <label class="custom-control-label" for="price-all">All Price</label>
                            <span class="badge border font-weight-normal">1000</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-1">
                            <label class="custom-control-label" for="price-1">$0 - $100</label>
                            <span class="badge border font-weight-normal">150</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-2">
                            <label class="custom-control-label" for="price-2">$100 - $200</label>
                            <span class="badge border font-weight-normal">295</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-3">
                            <label class="custom-control-label" for="price-3">$200 - $300</label>
                            <span class="badge border font-weight-normal">246</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-4">
                            <label class="custom-control-label" for="price-4">$300 - $400</label>
                            <span class="badge border font-weight-normal">145</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input type="checkbox" class="custom-control-input" id="price-5">
                            <label class="custom-control-label" for="price-5">$400 - $500</label>
                            <span class="badge border font-weight-normal">168</span>
                        </div>
                    </form>
                </div>
                <!-- Price End -->

                <!-- Color Start -->
                <div class="border-bottom mb-4 pb-4">
                    <h5 class="font-weight-semi-bold mb-4">Filter by color</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="color-all">
                            <label class="custom-control-label" for="price-all">All Color</label>
                            <span class="badge border font-weight-normal">1000</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-1">
                            <label class="custom-control-label" for="color-1">Black</label>
                            <span class="badge border font-weight-normal">150</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-2">
                            <label class="custom-control-label" for="color-2">White</label>
                            <span class="badge border font-weight-normal">295</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-3">
                            <label class="custom-control-label" for="color-3">Red</label>
                            <span class="badge border font-weight-normal">246</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-4">
                            <label class="custom-control-label" for="color-4">Blue</label>
                            <span class="badge border font-weight-normal">145</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input type="checkbox" class="custom-control-input" id="color-5">
                            <label class="custom-control-label" for="color-5">Green</label>
                            <span class="badge border font-weight-normal">168</span>
                        </div>
                    </form>
                </div>
                <!-- Color End -->

                <!-- Size Start -->
                <div class="mb-5">
                    <h5 class="font-weight-semi-bold mb-4">Filter by size</h5>
                    <form>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="size-all">
                            <label class="custom-control-label" for="size-all">All Size</label>
                            <span class="badge border font-weight-normal">1000</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-1">
                            <label class="custom-control-label" for="size-1">XS</label>
                            <span class="badge border font-weight-normal">150</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-2">
                            <label class="custom-control-label" for="size-2">S</label>
                            <span class="badge border font-weight-normal">295</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-3">
                            <label class="custom-control-label" for="size-3">M</label>
                            <span class="badge border font-weight-normal">246</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="size-4">
                            <label class="custom-control-label" for="size-4">L</label>
                            <span class="badge border font-weight-normal">145</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input type="checkbox" class="custom-control-input" id="size-5">
                            <label class="custom-control-label" for="size-5">XL</label>
                            <span class="badge border font-weight-normal">168</span>
                        </div>
                    </form>
                </div>
                <!-- Size End -->
            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-9 col-md-12">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <form action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by name">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-transparent text-primary">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <div class="dropdown ml-4">
                                <button class="btn border dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                            Sort by
                                        </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="triggerId">
                                    <a class="dropdown-item" href="#">Latest</a>
                                    <a class="dropdown-item" href="#">Popularity</a>
                                    <a class="dropdown-item" href="#">Best Rating</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-1.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-2.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-3.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-4.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-5.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-6.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-7.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-8.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 pb-1">
                        <div class="card product-item border-0 mb-4">
                            <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                                <img class="img-fluid w-100" src="img/product-1.jpg" alt="">
                            </div>
                            <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                                <h6 class="text-truncate mb-3">Colorful Stylish Shirt</h6>
                                <div class="d-flex justify-content-center">
                                    <h6>$123.00</h6><h6 class="text-muted ml-2"><del>$123.00</del></h6>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-between bg-light border">
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                                <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-1">
                        <nav aria-label="Page navigation">
                          <ul class="pagination justify-content-center mb-3">
                            <li class="page-item disabled">
                              <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                              </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                              <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                              </a>
                            </li>
                          </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Shop Product End -->
        </div>
    </div>
    <!-- Shop End -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locale = document.documentElement.lang || 'ar';

    // ============================================================
    // Price Range
    // ============================================================
    const minR = document.getElementById('rangeMin');
    const maxR = document.getElementById('rangeMax');
    const minL = document.querySelector('.min-price-lbl');
    const maxL = document.querySelector('.max-price-lbl');
    const fill = document.getElementById('priceRangeFill');
    const GAP  = 200;

    function updateRange() {
        let mn = parseInt(minR.value);
        let mx = parseInt(maxR.value);
        if (mn > mx - GAP) {
            if (this === minR) { mn = mx - GAP; minR.value = mn; }
            else               { mx = mn + GAP; maxR.value = mx; }
        }
        if (minL) minL.textContent = mn;
        if (maxL) maxL.textContent = mx;
        if (fill) {
            fill.style.left  = (mn / 100) + '%';
            fill.style.right = (100 - mx / 100) + '%';
        }
    }
    if (minR && maxR) {
        minR.addEventListener('input', updateRange);
        maxR.addEventListener('input', updateRange);
        updateRange.call(minR);
    }

    // ============================================================
    // Toast
    // ============================================================
    function showToast(msg, type) {
        let t = document.getElementById('cart-toast');
        if (!t) {
            t = document.createElement('div');
            t.id = 'cart-toast';
            t.style.cssText = 'position:fixed;right:24px;z-index:9999;padding:14px 20px;border-radius:14px;font-size:14px;font-weight:700;transition:opacity .3s;opacity:0;display:flex;align-items:center;gap:8px;color:#fff;';
            document.body.appendChild(t);
        }
        t.style.bottom = window.innerWidth < 640 ? '90px' : '28px';
        t.style.background = type === 'success' ? '#111' : '#ef4444';
        t.innerHTML = `<i class="ph-bold ph-check-circle" style="font-size:18px;"></i> ${msg}`;
        t.style.opacity = '1';
        clearTimeout(t._t);
        t._t = setTimeout(() => { t.style.opacity = '0'; }, 3000);
    }

    // ============================================================
    // Add to Cart
    // ============================================================
    function addToCart(productId, size, btn) {
        const url = locale === 'en' ? '/en/cart/add' : '/cart/add';
        if (btn) { btn.style.opacity = '.6'; btn.style.pointerEvents = 'none'; }
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
                'Accept'       : 'application/json'
            },
            body: JSON.stringify({ product_id: productId, size: size || '', quantity: 1 })
        })
        .then(r => r.json())
        .then(function (data) {
            if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = ''; }
            if (data.success) {
                document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(el => el.textContent = data.count);
                if (btn) {
                    var icon = btn.querySelector('i');
                    var span = btn.querySelector('span');
                    var origClass = icon ? icon.className : '';
                    if (icon) icon.className = 'ph ph-check';
                    if (span) span.style.display = 'none';
                    setTimeout(function(){
                        if (icon) icon.className = origClass;
                        if (span) span.style.display = '';
                    }, 2000);
                }
                showToast(data.message || (locale === 'en' ? 'Added to cart!' : 'تمت الإضافة!'), 'success');
            } else {
                showToast(data.message || (locale === 'en' ? 'Error' : 'خطأ'), 'error');
            }
        })
        .catch(function () {
            if (btn) { btn.style.opacity = '1'; btn.style.pointerEvents = ''; }
            showToast(locale === 'en' ? 'Error, try again' : 'حدث خطأ', 'error');
        });
    }

    // ============================================================
    // ✅ Click handler — listener واحد على document
    // ============================================================
    document.addEventListener('click', function (e) {

        // Add to Cart
        const cartBtn = e.target.closest('.add-cart-btn');
        if (cartBtn) {
            e.preventDefault();
            e.stopPropagation();
            const card      = cartBtn.closest('[data-product-id]');
            if (!card) return;
            const productId = card.dataset.productId;
            const size      = card.querySelector('.qs-size.active')?.textContent.trim() || '';
            addToCart(productId, size, cartBtn);
            return;
        }

        // Navigate to product page (wishlist is handled by global handler in app.blade.php)
        const productCard = e.target.closest('.product-main[data-product-id]');
        if (productCard && !e.target.closest('a, button, .wishlist-btn, .add-cart-btn, .quick-shop-popup')) {
            const url = productCard.getAttribute('data-url');
            if (url) window.location.href = url;
        }
    });

    // ============================================================
    // Filter toggle
    // ============================================================
    const filterBtn = document.getElementById('filterToggleBtn');
    const sidebar   = document.getElementById('shopSidebar');
    if (filterBtn && sidebar) {
        // Auto-open if filters are active
        @if(request()->hasAny(['category', 'size', 'min_price', 'max_price']))
        sidebar.classList.add('open');
        filterBtn.classList.add('active');
        @endif

        filterBtn.addEventListener('click', function () {
            sidebar.classList.toggle('open');
            this.classList.toggle('active');
        });
    }

});

// ============================================================
// Quick Shop helpers (خارج DOMContentLoaded عشان تشتغل مع onclick)
// ============================================================
function selectQsSize(el, id) {
    document.querySelectorAll('#qsp-' + id + ' .qs-size').forEach(s => s.classList.remove('active'));
    el.classList.add('active');
}

function closeQsp(id) {
    const p = document.getElementById('qsp-' + id);
    if (p) p.style.display = 'none';
}

// ============================================================
// Category accordion
// ============================================================
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const parent = e.target.closest('.sb-cat-parent');
        if (!parent) return;
        if (e.target.closest('.sb-cat-children')) return;

        const children = parent.querySelector('.sb-cat-children');
        const icon     = parent.querySelector('.ph-caret-down');

        if (parent.classList.contains('open')) {
            children.style.maxHeight = '0';
            if (icon) icon.style.transform = 'rotate(0deg)';
            parent.classList.remove('open');
        } else {
            children.style.maxHeight = children.scrollHeight + 'px';
            if (icon) icon.style.transform = 'rotate(180deg)';
            parent.classList.add('open');
        }
    });
});
</script>
@endpush
