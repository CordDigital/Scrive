@extends('frontend.layouts.app')

@section('seo_title',       $product->meta_title)
@section('seo_description', $product->meta_description)
@section('seo_keywords',    $product->meta_keywords)
@section('og_image',        $product->og_image_url)
@section('og_type',         'product')

@push('seo')
@if($product->price)
<meta property="product:price:amount" content="{{ $product->price }}">
@endif
    <meta property="product:price:currency" content="USD">
    @if($product->brand)
    <meta property="product:brand" content="{{ $product->brand }}">
    @endif
    <meta property="product:availability" content="{{ $product->stock > 0 ? 'in stock' : 'out of stock' }}">
@endpush

@push('styles')
<style>
/* ── Layout ── */
.pd-wrap { padding: 60px 0 80px; }
.pd-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: start; }
@media(max-width:1024px){ .pd-grid { grid-template-columns: 1fr; gap: 40px; } }

/* ── Gallery ── */
.pd-gallery { position: sticky; top: 100px; }
.pd-main-img {
    border-radius: 24px; overflow: hidden; background: #f8f8f8;
    aspect-ratio: 1/1; position: relative; cursor: zoom-in;
}
.pd-main-img img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .6s cubic-bezier(.25,.46,.45,.94);
    pointer-events: none;
    user-select: none;
}
.pd-badge-sale {
    position: absolute; top: 16px; left: 16px; z-index: 2;
    background: #16a34a; color: #fff; font-size: 12px; font-weight: 700;
    padding: 4px 12px; border-radius: 20px; letter-spacing: .5px;
}

/* ── Zoom Lens (hover) ── */
.pd-zoom-lens {
    position: absolute;
    border: 2px solid #111;
    border-radius: 50%;
    width: 120px; height: 120px;
    pointer-events: none;
    display: none;
    z-index: 5;
    background: rgba(0,0,0,.04);
    box-shadow: 0 0 0 1px rgba(255,255,255,.6) inset;
}
.pd-main-img:hover .pd-zoom-lens { display: block; }

/* ── Zoom Preview Box ── */
.pd-zoom-preview {
    position: absolute;
    right: calc(100% + 16px);
    top: 0;
    width: 380px;
    height: 380px;
    border-radius: 20px;
    overflow: hidden;
    background: #f8f8f8;
    display: none;
    z-index: 100;
    border: 1px solid #e5e7eb;
    box-shadow: 0 20px 60px rgba(0,0,0,.15);
    pointer-events: none;
}
.pd-zoom-preview img {
    position: absolute;
    max-width: none;
    width: auto;
    height: auto;
    pointer-events: none;
    user-select: none;
}
@media(max-width:1200px){
    .pd-zoom-preview { right: auto; left: 0; top: calc(100% + 10px); width: 100%; height: 300px; }
}
@media(max-width:1024px){ .pd-zoom-preview { display: none !important; } }

/* ── Lightbox ── */
#pd-lightbox {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.92);
    display: none; align-items: center; justify-content: center;
    animation: lbFadeIn .25s ease;
}
#pd-lightbox.open { display: flex; }
@keyframes lbFadeIn { from{opacity:0} to{opacity:1} }
.pd-lb-inner {
    position: relative;
    max-width: 90vw; max-height: 90vh;
    display: flex; align-items: center; justify-content: center;
    cursor: zoom-out;
}
.pd-lb-inner img {
    max-width: 90vw; max-height: 90vh;
    object-fit: contain;
    border-radius: 12px;
    transform: scale(1);
    transition: transform .35s cubic-bezier(.25,.46,.45,.94);
    user-select: none;
    -webkit-user-drag: none;
}
.pd-lb-inner img.zoomed { transform: scale(2); cursor: crosshair; }
.pd-lb-close {
    position: fixed; top: 20px; right: 24px;
    width: 44px; height: 44px; border-radius: 50%;
    background: rgba(255,255,255,.12); color: #fff;
    border: none; cursor: pointer; font-size: 22px;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
    z-index: 10;
}
.pd-lb-close:hover { background: rgba(255,255,255,.25); }
.pd-lb-hint {
    position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
    color: rgba(255,255,255,.5); font-size: 12px; letter-spacing: .5px;
    pointer-events: none;
}

.pd-thumbs { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
.pd-thumb {
    width: 76px; height: 76px; border-radius: 14px; overflow: hidden;
    cursor: pointer; border: 2px solid transparent;
    transition: border-color .2s, transform .2s;
    background: #f8f8f8;
}
.pd-thumb:hover { transform: translateY(-2px); }
.pd-thumb.active { border-color: #111; }
.pd-thumb img { width: 100%; height: 100%; object-fit: cover; }

/* ── Info ── */
.pd-info {}
.pd-cat {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: #888; letter-spacing: 1px;
    text-transform: uppercase;
}
.pd-cat::before { content:''; display:inline-block; width:20px; height:2px; background:#888; border-radius:2px; }
.pd-title { font-size: 32px; font-weight: 800; color: #111; line-height: 1.25; margin: 10px 0 0; }
@media(max-width:600px){ .pd-title { font-size: 24px; } }

.pd-price-row { display: flex; align-items: center; gap: 14px; margin-top: 20px; }
.pd-price { font-size: 34px; font-weight: 800; color: #111; }
.pd-old-price { font-size: 20px; color: #bbb; text-decoration: line-through; }
.pd-discount-badge {
    background: #fef2f2; color: #dc2626; font-size: 13px; font-weight: 700;
    padding: 4px 12px; border-radius: 20px;
}

.pd-divider { height: 1px; background: #f0f0f0; margin: 24px 0; }

/* Description */
.pd-desc { font-size: 15px; color: #666; line-height: 1.8; }
.pd-desc p { margin: 0 0 10px; }

/* Video */
.pd-video { border-radius: 16px; overflow: hidden; margin-top: 20px; position: relative; padding-top: 56.25%; }
.pd-video iframe { position: absolute; inset: 0; width: 100%; height: 100%; }

/* Sizes */
.pd-label { font-size: 13px; font-weight: 700; color: #111; margin-bottom: 10px; }
.pd-sizes { display: flex; gap: 8px; flex-wrap: wrap; }
.pd-size-label { cursor: pointer; }
.pd-size-label input { display: none; }
.pd-size-btn {
    width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;
    border: 1.5px solid #e5e7eb; border-radius: 12px; font-size: 13px; font-weight: 600;
    color: #555; transition: all .2s; background: #fff;
}
.pd-size-label input:checked + .pd-size-btn,
.pd-size-btn:hover { border-color: #111; background: #111; color: #fff; }

/* Colors */
.pd-colors { display: flex; gap: 10px; flex-wrap: wrap; }
.pd-color-label { cursor: pointer; }
.pd-color-label input { display: none; }
.pd-color-btn {
    padding: 8px 18px; border: 1.5px solid #e5e7eb; border-radius: 24px;
    font-size: 13px; font-weight: 600; color: #555; transition: all .2s; background: #fff;
}
.pd-color-label input:checked + .pd-color-btn,
.pd-color-btn:hover { border-color: #111; background: #111; color: #fff; }

/* Actions */
.pd-actions { display: flex; gap: 12px; margin-top: 28px; align-items: center; flex-wrap: wrap; }
.pd-qty {
    display: flex; align-items: center; border: 1.5px solid #e5e7eb;
    border-radius: 14px; overflow: hidden; background: #fff;
}
.pd-qty-btn {
    width: 44px; height: 50px; display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 300; cursor: pointer; color: #555;
    transition: background .2s; border: none; background: transparent;
}
.pd-qty-btn:hover { background: #f5f5f5; }
.pd-qty-input {
    width: 52px; height: 50px; text-align: center; border: none;
    font-size: 15px; font-weight: 700; color: #111; outline: none;
    border-left: 1.5px solid #e5e7eb; border-right: 1.5px solid #e5e7eb;
}
.pd-add-btn {
    flex: 1; min-width: 180px; height: 50px; display: flex; align-items: center;
    justify-content: center; gap: 8px; background: #111; color: #fff;
    border: none; border-radius: 14px; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: all .25s; letter-spacing: .3px;
}
.pd-add-btn:hover { background: #333; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(0,0,0,.2); }
.pd-continue-btn { background: #fff; color: #111; border: 1.5px solid #111; text-decoration: none; }
.pd-continue-btn:hover { background: #111; color: #fff; }
.pd-add-btn:active { transform: translateY(0); }
.pd-add-btn.loading { opacity: .7; pointer-events: none; }

/* Stock */
.pd-stock { display: flex; align-items: center; gap: 6px; font-size: 13px; margin-top: 16px; color: #888; }
.pd-stock-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.pd-stock-dot.in  { background: #16a34a; box-shadow: 0 0 0 3px #dcfce7; }
.pd-stock-dot.out { background: #dc2626; box-shadow: 0 0 0 3px #fee2e2; }

/* Trust */
.pd-trust { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-top: 28px; }
.pd-trust-item {
    display: flex; flex-direction: column; align-items: center; gap: 6px;
    padding: 14px 8px; background: #f8f8f8; border-radius: 14px;
    font-size: 11px; font-weight: 600; color: #555; text-align: center; line-height: 1.4;
}
.pd-trust-item i { font-size: 22px; color: #111; }

/* Tabs */
.pd-tabs-wrap { margin-top: 72px; }
.pd-tabs-nav { display: flex; gap: 4px; border-bottom: 1px solid #e5e7eb; margin-bottom: 32px; }
.pd-tab-btn {
    padding: 12px 24px; font-size: 14px; font-weight: 600; color: #888;
    border: none; background: none; cursor: pointer; border-bottom: 2px solid transparent;
    margin-bottom: -1px; transition: color .2s, border-color .2s;
}
.pd-tab-btn.active { color: #111; border-bottom-color: #111; }
.pd-tab-panel { display: none; animation: fadeIn .3s; }
.pd-tab-panel.active { display: block; }
@keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
.pd-tab-content { font-size: 15px; color: #555; line-height: 1.9; max-width: 720px; }
.pd-tab-content p { margin: 0 0 14px; }
</style>
@endpush

@section('content')

   <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shop Detail</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop Detail</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 pb-5">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner border">
                        <div class="carousel-item active">
                            <img class="w-100 h-100" src="img/product-1.jpg" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="img/product-2.jpg" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="img/product-3.jpg" alt="Image">
                        </div>
                        <div class="carousel-item">
                            <img class="w-100 h-100" src="img/product-4.jpg" alt="Image">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 pb-5">
                <h3 class="font-weight-semi-bold">Colorful Stylish Shirt</h3>
                <div class="d-flex mb-3">
                    <div class="text-primary mr-2">
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star"></small>
                        <small class="fas fa-star-half-alt"></small>
                        <small class="far fa-star"></small>
                    </div>
                    <small class="pt-1">(50 Reviews)</small>
                </div>
                <h3 class="font-weight-semi-bold mb-4">$150.00</h3>
                <p class="mb-4">Volup erat ipsum diam elitr rebum et dolor. Est nonumy elitr erat diam stet sit clita ea. Sanc invidunt ipsum et, labore clita lorem magna lorem ut. Erat lorem duo dolor no sea nonumy. Accus labore stet, est lorem sit diam sea et justo, amet at lorem et eirmod ipsum diam et rebum kasd rebum.</p>
                <div class="d-flex mb-3">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                    <form>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-1" name="size">
                            <label class="custom-control-label" for="size-1">XS</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-2" name="size">
                            <label class="custom-control-label" for="size-2">S</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-3" name="size">
                            <label class="custom-control-label" for="size-3">M</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-4" name="size">
                            <label class="custom-control-label" for="size-4">L</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="size-5" name="size">
                            <label class="custom-control-label" for="size-5">XL</label>
                        </div>
                    </form>
                </div>
                <div class="d-flex mb-4">
                    <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                    <form>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-1" name="color">
                            <label class="custom-control-label" for="color-1">Black</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-2" name="color">
                            <label class="custom-control-label" for="color-2">White</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-3" name="color">
                            <label class="custom-control-label" for="color-3">Red</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-4" name="color">
                            <label class="custom-control-label" for="color-4">Blue</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" id="color-5" name="color">
                            <label class="custom-control-label" for="color-5">Green</label>
                        </div>
                    </form>
                </div>
                <div class="d-flex align-items-center mb-4 pt-2">
                    <div class="input-group quantity mr-3" style="width: 130px;">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-minus" >
                            <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control bg-secondary text-center" value="1">
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-plus">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <button class="btn btn-primary px-3"><i class="fa fa-shopping-cart mr-1"></i> Add To Cart</button>
                </div>
                <div class="d-flex pt-2">
                    <p class="text-dark font-weight-medium mb-0 mr-2">Share on:</p>
                    <div class="d-inline-flex">
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a class="text-dark px-2" href="">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                    <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-2">Information</a>
                    <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Reviews (0)</a>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-pane-1">
                        <h4 class="mb-3">Product Description</h4>
                        <p>Eos no lorem eirmod diam diam, eos elitr et gubergren diam sea. Consetetur vero aliquyam invidunt duo dolores et duo sit. Vero diam ea vero et dolore rebum, dolor rebum eirmod consetetur invidunt sed sed et, lorem duo et eos elitr, sadipscing kasd ipsum rebum diam. Dolore diam stet rebum sed tempor kasd eirmod. Takimata kasd ipsum accusam sadipscing, eos dolores sit no ut diam consetetur duo justo est, sit sanctus diam tempor aliquyam eirmod nonumy rebum dolor accusam, ipsum kasd eos consetetur at sit rebum, diam kasd invidunt tempor lorem, ipsum lorem elitr sanctus eirmod takimata dolor ea invidunt.</p>
                        <p>Dolore magna est eirmod sanctus dolor, amet diam et eirmod et ipsum. Amet dolore tempor consetetur sed lorem dolor sit lorem tempor. Gubergren amet amet labore sadipscing clita clita diam clita. Sea amet et sed ipsum lorem elitr et, amet et labore voluptua sit rebum. Ea erat sed et diam takimata sed justo. Magna takimata justo et amet magna et.</p>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-2">
                        <h4 class="mb-3">Additional Information</h4>
                        <p>Eos no lorem eirmod diam diam, eos elitr et gubergren diam sea. Consetetur vero aliquyam invidunt duo dolores et duo sit. Vero diam ea vero et dolore rebum, dolor rebum eirmod consetetur invidunt sed sed et, lorem duo et eos elitr, sadipscing kasd ipsum rebum diam. Dolore diam stet rebum sed tempor kasd eirmod. Takimata kasd ipsum accusam sadipscing, eos dolores sit no ut diam consetetur duo justo est, sit sanctus diam tempor aliquyam eirmod nonumy rebum dolor accusam, ipsum kasd eos consetetur at sit rebum, diam kasd invidunt tempor lorem, ipsum lorem elitr sanctus eirmod takimata dolor ea invidunt.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0">
                                        Sit erat duo lorem duo ea consetetur, et eirmod takimata.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Amet kasd gubergren sit sanctus et lorem eos sadipscing at.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Duo amet accusam eirmod nonumy stet et et stet eirmod.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Takimata ea clita labore amet ipsum erat justo voluptua. Nonumy.
                                    </li>
                                  </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0">
                                        Sit erat duo lorem duo ea consetetur, et eirmod takimata.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Amet kasd gubergren sit sanctus et lorem eos sadipscing at.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Duo amet accusam eirmod nonumy stet et et stet eirmod.
                                    </li>
                                    <li class="list-group-item px-0">
                                        Takimata ea clita labore amet ipsum erat justo voluptua. Nonumy.
                                    </li>
                                  </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-pane-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-4">1 review for "Colorful Stylish Shirt"</h4>
                                <div class="media mb-4">
                                    <img src="img/user.jpg" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                                    <div class="media-body">
                                        <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>
                                        <div class="text-primary mb-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <p>Diam amet duo labore stet elitr ea clita ipsum, tempor labore accusam ipsum et no at. Kasd diam tempor rebum magna dolores sed sed eirmod ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mb-4">Leave a review</h4>
                                <small>Your email address will not be published. Required fields are marked *</small>
                                <div class="d-flex my-3">
                                    <p class="mb-0 mr-2">Your Rating * :</p>
                                    <div class="text-primary">
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </div>
                                </div>
                                <form>
                                    <div class="form-group">
                                        <label for="message">Your Review *</label>
                                        <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Your Name *</label>
                                        <input type="text" class="form-control" id="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Your Email *</label>
                                        <input type="email" class="form-control" id="email">
                                    </div>
                                    <div class="form-group mb-0">
                                        <input type="submit" value="Leave Your Review" class="btn btn-primary px-3">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel related-carousel">
                    <div class="card product-item border-0">
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
                    <div class="card product-item border-0">
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
                    <div class="card product-item border-0">
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
                    <div class="card product-item border-0">
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
                    <div class="card product-item border-0">
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
            </div>
        </div>
    </div>
    <!-- Products End -->


@endsection

@push('scripts')
<script>
var CSRF   = '{{ csrf_token() }}';
var LOCALE = '{{ app()->getLocale() }}';

/* ══════════════════════════════════════════
   IMAGE ZOOM — Hover Preview + Lightbox
══════════════════════════════════════════ */
(function(){
    var wrap        = document.getElementById('pd-main-img-wrap');
    var mainImg     = document.getElementById('main-product-img');
    var lens        = document.getElementById('pd-zoom-lens');
    var preview     = document.getElementById('pd-zoom-preview');
    var previewImg  = document.getElementById('pd-zoom-preview-img');
    var lightbox    = document.getElementById('pd-lightbox');
    var lbImg       = document.getElementById('pd-lb-img');
    var lbInner     = document.getElementById('pd-lb-inner');
    var lbClose     = document.getElementById('pd-lb-close');

    var ZOOM_FACTOR = 3.5;  /* how much the preview magnifies */
    var lbZoomed    = false;
    var isDragging  = false;
    var dragStart   = { x:0, y:0 };
    var imgOffset   = { x:0, y:0 };

    /* ── Hover zoom (desktop ≥1025px) ── */
    if(wrap && lens && preview && previewImg){
        wrap.addEventListener('mousemove', function(e){
            var rect    = wrap.getBoundingClientRect();
            var lensW   = lens.offsetWidth;
            var lensH   = lens.offsetHeight;

            /* Cursor position relative to image */
            var cx = e.clientX - rect.left;
            var cy = e.clientY - rect.top;

            /* Clamp lens inside image */
            var lx = Math.max(lensW/2, Math.min(rect.width  - lensW/2, cx)) - lensW/2;
            var ly = Math.max(lensH/2, Math.min(rect.height - lensH/2, cy)) - lensH/2;

            lens.style.left = lx + 'px';
            lens.style.top  = ly + 'px';

            /* Move preview image */
            var ratioX = (lx + lensW/2) / rect.width;
            var ratioY = (ly + lensH/2) / rect.height;
            var pw = preview.offsetWidth;
            var ph = preview.offsetHeight;
            var iw = mainImg.naturalWidth  || rect.width;
            var ih = mainImg.naturalHeight || rect.height;
            var scale = Math.max(pw/rect.width, ph/rect.height) * ZOOM_FACTOR;

            previewImg.style.width  = (rect.width  * scale) + 'px';
            previewImg.style.height = (rect.height * scale) + 'px';
            previewImg.style.left   = -(ratioX * rect.width  * scale - pw/2) + 'px';
            previewImg.style.top    = -(ratioY * rect.height * scale - ph/2) + 'px';

            preview.style.display = 'block';
        });

        wrap.addEventListener('mouseleave', function(){
            preview.style.display = 'none';
        });
    }

    /* ── Click → open lightbox ── */
    if(wrap && lightbox && lbImg){
        wrap.addEventListener('click', function(){
            lbImg.src     = mainImg.src;
            previewImg && (previewImg.src = mainImg.src);
            lbZoomed      = false;
            lbImg.classList.remove('zoomed');
            lbImg.style.transform      = 'scale(1)';
            lbImg.style.transformOrigin = 'center center';
            imgOffset = {x:0, y:0};
            lightbox.classList.add('open');
            document.body.style.overflow = 'hidden';
        });
    }

    /* Close lightbox */
    function closeLightbox(){
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
        lbZoomed = false;
        lbImg.classList.remove('zoomed');
        lbImg.style.transform = 'scale(1)';
        imgOffset = {x:0, y:0};
    }

    if(lbClose) lbClose.addEventListener('click', closeLightbox);
    if(lightbox){
        lightbox.addEventListener('click', function(e){
            if(e.target === lightbox) closeLightbox();
        });
    }

    /* ESC to close */
    document.addEventListener('keydown', function(e){
        if(e.key === 'Escape') closeLightbox();
    });

    /* Click inside lightbox → toggle zoom */
    if(lbInner && lbImg){
        lbInner.addEventListener('click', function(e){
            if(isDragging) return;
            if(e.target === lbImg){
                lbZoomed = !lbZoomed;
                if(lbZoomed){
                    var rect   = lbImg.getBoundingClientRect();
                    var ox     = ((e.clientX - rect.left) / rect.width  * 100).toFixed(1) + '%';
                    var oy     = ((e.clientY - rect.top)  / rect.height * 100).toFixed(1) + '%';
                    lbImg.style.transformOrigin = ox + ' ' + oy;
                    lbImg.style.transform       = 'scale(2.5)';
                    lbImg.style.cursor          = 'grab';
                } else {
                    lbImg.style.transform       = 'scale(1)';
                    lbImg.style.transformOrigin = 'center center';
                    lbImg.style.cursor          = 'zoom-in';
                    imgOffset = {x:0, y:0};
                }
            }
        });

        /* Drag-to-pan when zoomed */
        lbImg.addEventListener('mousedown', function(e){
            if(!lbZoomed) return;
            isDragging = false;
            var startX = e.clientX - imgOffset.x;
            var startY = e.clientY - imgOffset.y;
            lbImg.style.cursor = 'grabbing';
            var moved = false;

            function onMove(ev){
                moved = true;
                isDragging = true;
                imgOffset.x = ev.clientX - startX;
                imgOffset.y = ev.clientY - startY;
                lbImg.style.transform = 'scale(2.5) translate(' + (imgOffset.x/2.5) + 'px,' + (imgOffset.y/2.5) + 'px)';
            }
            function onUp(){
                lbImg.style.cursor = 'grab';
                document.removeEventListener('mousemove', onMove);
                document.removeEventListener('mouseup', onUp);
                setTimeout(function(){ isDragging = false; }, 10);
            }
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
            e.preventDefault();
        });

        /* Touch pinch / pan */
        var lastDist = 0;
        lbImg.addEventListener('touchstart', function(e){
            if(e.touches.length === 2){
                lastDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
            }
        }, {passive:true});
        lbImg.addEventListener('touchmove', function(e){
            if(e.touches.length === 2){
                var dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                var scale = Math.min(4, Math.max(1, 2.5 * (dist/lastDist)));
                lbImg.style.transform = 'scale('+scale+')';
                if(scale > 1) lbZoomed = true;
            }
        }, {passive:true});
    }

    /* ── Sync preview image when main image changes ── */
    window._syncZoomSrc = function(src){
        if(previewImg) previewImg.src = src;
    };
})();

/* ══════════════════════════════════════════
   Gallery
══════════════════════════════════════════ */
function changeImg(src, el, colorName){
    document.getElementById('main-product-img').src = src;
    window._syncZoomSrc && window._syncZoomSrc(src);
    document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    var label = document.getElementById('active-color-label');
    if(label) label.textContent = colorName || '';
}

/* ══════════════════════════════════════════
   Quantity
══════════════════════════════════════════ */
function changeQty(delta){
    var input = document.getElementById('qty-input');
    var min = parseInt(input.min) || 1;
    var max = parseInt(input.max) || 99;
    var val = (parseInt(input.value) || min) + delta;
    if(val < min) val = min;
    if(val > max) val = max;
    input.value = val;
}

/* ══════════════════════════════════════════
   Tabs
══════════════════════════════════════════ */
function switchTab(id, btn){
    document.querySelectorAll('.pd-tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.pd-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    btn.classList.add('active');
}

/* ══════════════════════════════════════════
   Toast
══════════════════════════════════════════ */
function showToast(msg, type){
    type = type || 'success';
    var t = document.getElementById('cart-toast');
    if(!t){
        t = document.createElement('div');
        t.id = 'cart-toast';
        t.style.cssText = 'position:fixed;bottom:28px;right:28px;z-index:9999;padding:14px 22px;border-radius:14px;font-size:14px;font-weight:700;transition:all .35s;opacity:0;transform:translateY(10px);display:flex;align-items:center;gap:8px;';
        document.body.appendChild(t);
    }
    t.style.background = type === 'success' ? '#111' : '#dc2626';
    t.style.color = '#fff';
    t.innerHTML = '<i class="ph-bold ph-' + (type==='success'?'check-circle':'warning-circle') + '" style="font-size:18px;"></i> ' + msg;
    t.style.opacity = '1';
    t.style.transform = 'translateY(0)';
    clearTimeout(t._t);
    t._t = setTimeout(function(){ t.style.opacity='0'; t.style.transform='translateY(10px)'; }, 3000);
}

/* ══════════════════════════════════════════
   Add to Cart
══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function(){
    var qtyInput = document.getElementById('qty-input');
    if(qtyInput){
        qtyInput.addEventListener('change', function(){
            var min = parseInt(this.min) || 1;
            var max = parseInt(this.max) || 99;
            var val = parseInt(this.value) || min;
            if(val < min) val = min;
            if(val > max) val = max;
            this.value = val;
        });
    }

    var addBtn = document.getElementById('add-to-cart-btn');
    if(addBtn){
        addBtn.addEventListener('click', function(){
            var form      = document.getElementById('add-to-cart-form');
            var productId = form.querySelector('input[name="product_id"]').value;
            var sizeEl    = form.querySelector('input[name="size"]:checked');
            var colorEl   = form.querySelector('input[name="color"]:checked');
            var size      = sizeEl  ? sizeEl.value  : '';
            var color     = colorEl ? colorEl.value : '';
            var qty       = parseInt(form.querySelector('input[name="quantity"]').value) || 1;

            addBtn.classList.add('loading');
            addBtn.innerHTML = '<i class="ph ph-circle-notch" style="font-size:18px;animation:spin 1s linear infinite;"></i>';

            var url = '{{ app()->getLocale() === "en" ? "/en/cart/add" : "/cart/add" }}';
            fetch(url, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
                body: JSON.stringify({ product_id: productId, size: size, color: color, quantity: qty })
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                addBtn.classList.remove('loading');
                addBtn.innerHTML = '<i class="ph ph-shopping-bag-open" style="font-size:18px;"></i><span>{{ app()->getLocale()==="ar"?"أضف للسلة":"Add to Cart" }}</span>';
                if(data.success){
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el){ el.textContent = data.count; });
                    showToast(data.message || '{{ app()->getLocale()==="ar"?"تمت الإضافة!":"Added to cart!" }}', 'success');
                } else {
                    showToast(data.message || '{{ app()->getLocale()==="ar"?"خطأ":"Error" }}', 'error');
                }
            })
            .catch(function(){
                addBtn.classList.remove('loading');
                addBtn.innerHTML = '<i class="ph ph-shopping-bag-open" style="font-size:18px;"></i><span>{{ app()->getLocale()==="ar"?"أضف للسلة":"Add to Cart" }}</span>';
                showToast('{{ app()->getLocale()==="ar"?"خطأ":"Error" }}', 'error');
            });
        });
    }
});
</script>
<style>
@keyframes spin { from{transform:rotate(0)} to{transform:rotate(360deg)} }
</style>
@endpush
