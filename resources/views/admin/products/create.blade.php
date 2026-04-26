@extends('admin.layouts.app')

@push('styles')
{{-- Quill Editor CSS --}}
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-size: 15px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; }
    .ql-editor { min-height: 200px; line-height: 1.7; }
    .ql-toolbar { border-radius: 4px 4px 0 0; border-color: #dee2e6 !important; }
    .cursor-text { cursor: text; }
    .min-height-40 { min-height: 40px; }

    /* Price on Request */
    .price-fields-wrap { transition: opacity .25s; }
    .price-fields-wrap.disabled { opacity: .4; pointer-events: none; }

    /* Discount badge live */
    #discountBadge {
        display: none;
        font-size: 13px; font-weight: 700;
        background: #fef2f2; color: #dc2626;
        padding: 4px 12px; border-radius: 20px;
        border: 1px solid #fecaca;
    }
    #discountBadge.show { display: inline-block; }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold mb-0">Add New Product</h4>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- ══════════════ Left Column ══════════════ --}}
                <div class="col-lg-8">

                    {{-- Basic Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-package-variant me-2"></i>Product Details</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">

                                {{-- Names --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-format-title me-1 text-muted"></i>Name (Arabic) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                                           class="form-control @error('name_ar') is-invalid @enderror" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-format-title me-1 text-muted"></i>Name (English) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_en" value="{{ old('name_en') }}"
                                           class="form-control @error('name_en') is-invalid @enderror" required>
                                </div>

                                {{-- Categories --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-folder me-1 text-muted"></i>Main Category <span class="text-danger">*</span>
                                    </label>
                                    <select id="parent_category" class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">-- Select Main Category --</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" data-children='@json($cat->children->map(fn($c) => ["id" => $c->id, "name" => $c->name_en . " / " . $c->name_ar]))'>
                                            {{ $cat->name_en }} / {{ $cat->name_ar }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-file-tree me-1 text-muted"></i>Subcategory
                                    </label>
                                    <select id="subcategory" class="form-control" disabled>
                                        <option value="">-- Select Main Category First --</option>
                                    </select>
                                </div>
                                <input type="hidden" name="category_id" id="category_id_hidden" value="{{ old('category_id') }}">

                                {{-- Brand & Stock --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-tag-text me-1 text-muted"></i>Brand
                                    </label>
                                    <input type="text" name="brand" value="{{ old('brand') }}" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-warehouse me-1 text-muted"></i>Stock
                                    </label>
                                    <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-control" min="0">
                                </div>

                                {{-- ══ Price on Request Toggle ══ --}}
                                <div class="col-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="price_on_request"
                                               id="priceOnRequest"
                                               {{ old('price_on_request') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold text-danger" for="priceOnRequest">
                                            <i class="mdi mdi-tag-off me-1"></i>
                                            Price on Request &nbsp;<small class="text-muted fw-normal">(Price will be hidden on the website)</small>
                                        </label>
                                    </div>
                                </div>

                                {{-- ══ Price Fields (hidden when price_on_request) ══ --}}
                                <div class="col-12 price-fields-wrap" id="priceFieldsWrap">
                                    <div class="row">
                                        {{-- Price --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-medium">
                                                <i class="mdi mdi-currency-usd me-1 text-muted"></i>Price (EGP)
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">EGP</span>
                                                <input type="number" name="price" id="priceInput"
                                                       value="{{ old('price') }}" step="0.01" min="0"
                                                       class="form-control" placeholder="0.00">
                                            </div>
                                        </div>

                                        {{-- Old Price --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-medium">
                                                <i class="mdi mdi-currency-usd me-1 text-muted"></i>Old Price (EGP)
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">EGP</span>
                                                <input type="number" name="old_price" id="oldPriceInput"
                                                       value="{{ old('old_price') }}" step="0.01" min="0"
                                                       class="form-control" placeholder="0.00">
                                            </div>
                                        </div>

                                        {{-- Discount % --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-medium">
                                                <i class="mdi mdi-percent me-1 text-muted"></i>Discount %
                                                <small class="text-muted fw-normal">(Enter % to calculate old price)</small>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" id="discountInput"
                                                       value="{{ old('discount_percent') }}"
                                                       step="1" min="0" max="99" class="form-control"
                                                       placeholder="e.g. 20">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            {{-- Live badge --}}
                                            <div class="mt-2">
                                                <span id="discountBadge"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- end price fields --}}

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order
                                    </label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description with Quill Editor --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-text-subject me-2"></i>Product Description</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-text me-1 text-muted"></i>Description (Arabic)
                                </label>
                                <div id="quill_ar" style="height: 200px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="description_ar" id="description_ar_input" value="{{ old('description_ar') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-text me-1 text-muted"></i>Description (English)
                                </label>
                                <div id="quill_en" style="height: 200px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="description_en" id="description_en_input" value="{{ old('description_en') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Videos --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-play-circle me-2"></i>Product Video</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-video me-1 text-muted"></i>Video URL (Arabic)
                                </label>
                                <input type="url" name="video_ar" value="{{ old('video_ar') }}"
                                       class="form-control @error('video_ar') is-invalid @enderror"
                                       placeholder="https://www.youtube.com/embed/...">
                                <small class="text-muted">Arabic video URL (YouTube embed or Vimeo)</small>
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-video me-1 text-muted"></i>Video URL (English)
                                </label>
                                <input type="url" name="video_en" value="{{ old('video_en') }}"
                                       class="form-control @error('video_en') is-invalid @enderror"
                                       placeholder="https://www.youtube.com/embed/...">
                                <small class="text-muted">English video URL (YouTube embed or Vimeo)</small>
                            </div>
                        </div>
                    </div>

                    {{-- Sizes & Colors --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-ruler me-2"></i>Sizes & General Colors</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-ruler me-1 text-muted"></i>Sizes <small class="text-muted">(Press Enter or Comma)</small>
                                </label>
                                <div id="sizesContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2 border cursor-text min-height-40"></div>
                                <input type="text" id="sizeInput" class="form-control" placeholder="S, M, L, XL...">
                                <input type="hidden" name="sizes" id="sizesHidden" value="{{ old('sizes') }}">
                            </div>
                            <div>
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-palette me-1 text-muted"></i>General Colors <small class="text-muted">(Press Enter or Comma)</small>
                                </label>
                                <div id="colorsContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2 border cursor-text min-height-40"></div>
                                <input type="text" id="colorInput" class="form-control" placeholder="Red, Green, Blue...">
                                <input type="hidden" name="colors" id="colorsHidden" value="{{ old('colors') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Gallery with Colors --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-image-multiple me-2"></i>Images & Color Gallery</h6>
                        </div>
                        <div class="card-body pt-4">
                            {{-- Main Image --}}
                            <div class="mb-4 pb-4 border-bottom">
                                <label class="form-label font-weight-bold">Main Image <span class="text-danger">*</span></label>
                                <div class="border rounded d-flex align-items-center justify-content-center mb-3 overflow-hidden"
                                     style="height:200px; background:#f8f9fa; border:2px dashed #dee2e6 !important; cursor:pointer;"
                                     onclick="document.getElementById('mainImageInput').click()">
                                    <img id="mainImagePreview" src="" class="d-none w-100 h-100" style="object-fit:cover;">
                                    <div id="mainImagePlaceholder" class="text-center text-muted">
                                        <i class="mdi mdi-cloud-upload" style="font-size:3rem;"></i>
                                        <p class="mt-2 mb-0">Click to upload main image</p>
                                    </div>
                                </div>
                                <input type="file" name="image" id="mainImageInput" accept="image/*" class="form-control d-none" onchange="previewMain(event)" required>
                            </div>

                            {{-- Dynamic Gallery --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-bold text-primary">Gallery Images with Colors</label>
                                <div id="galleryContainer">
                                    <div class="gallery-row border rounded p-3 mb-3 bg-light position-relative animate__animated animate__fadeIn">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="small fw-bold">Image</label>
                                                <input type="file" name="gallery[]" class="form-control form-control-sm" accept="image/*">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold">Color (English)</label>
                                                <input type="text" name="gallery_color_en[]" class="form-control form-control-sm" placeholder="e.g. Midnight Black">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold">Color (Arabic)</label>
                                                <input type="text" name="gallery_color_ar[]" class="form-control form-control-sm" placeholder="e.g. أسود ليل">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addGalleryRow()">
                                    <i class="mdi mdi-plus-circle me-1"></i> Add Another Color Image
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- end left column --}}

                {{-- ══════════════ Right Column ══════════════ --}}
                <div class="col-lg-4">

                    {{-- SEO --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-magnify me-2"></i>SEO</h6>
                        </div>
                        <div class="card-body pt-4">

                            <div class="mb-3">
                                <label class="form-label fw-medium small"><i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (Arabic)</label>
                                <input type="text" name="meta_title_ar" value="{{ old('meta_title_ar') }}"
                                       class="form-control form-control-sm" placeholder="Page title in Arabic" maxlength="70">
                                <div class="text-muted" style="font-size:11px;">Ideally less than 60 characters</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small"><i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (English)</label>
                                <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}"
                                       class="form-control form-control-sm" placeholder="Page title in English" maxlength="70">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium small"><i class="mdi mdi-text me-1 text-muted"></i>Meta Description (Arabic)</label>
                                <textarea name="meta_description_ar" rows="2"
                                          class="form-control form-control-sm"
                                          placeholder="Page description in Arabic" maxlength="160">{{ old('meta_description_ar') }}</textarea>
                                <div class="text-muted" style="font-size:11px;">Ideally less than 160 characters</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small"><i class="mdi mdi-text me-1 text-muted"></i>Meta Description (English)</label>
                                <textarea name="meta_description_en" rows="2"
                                          class="form-control form-control-sm"
                                          placeholder="Page description in English" maxlength="160">{{ old('meta_description_en') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium small"><i class="mdi mdi-tag-multiple me-1 text-muted"></i>Keywords (Arabic)</label>
                                <input type="text" name="meta_keywords_ar" value="{{ old('meta_keywords_ar') }}"
                                       class="form-control form-control-sm" placeholder="keyword1, keyword2, keyword3">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small"><i class="mdi mdi-tag-multiple me-1 text-muted"></i>Keywords (English)</label>
                                <input type="text" name="meta_keywords_en" value="{{ old('meta_keywords_en') }}"
                                       class="form-control form-control-sm" placeholder="keyword1, keyword2, keyword3">
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-medium small"><i class="mdi mdi-share-variant me-1 text-muted"></i>OG Image <small class="text-muted">(Social Sharing)</small></label>
                                <input type="file" name="og_image" accept="image/*"
                                       class="form-control form-control-sm @error('og_image') is-invalid @enderror">
                                <div class="text-muted" style="font-size:11px;">1200×630px — Leave blank to use the main image</div>
                            </div>

                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 bg-dark">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-cog me-2"></i>Settings</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active / Published</label>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>

                            <button type="button" id="submitBtn" class="btn btn-primary btn-lg w-100 shadow-sm mb-3 border-0"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Quill Editor JS --}}
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
/* ══════════════════════════════════════════
   Quill Editor Setup
══════════════════════════════════════════ */
const toolbarOptions = [
    [{ 'header': [1, 2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'align': [] }],
    ['link', 'clean']
];

const quillAr = new Quill('#quill_ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });
quillAr.root.setAttribute('dir', 'rtl');

const quillEn = new Quill('#quill_en', { theme: 'snow', modules: { toolbar: toolbarOptions } });

@if(old('description_ar'))
    quillAr.root.innerHTML = `{!! old('description_ar') !!}`;
@endif
@if(old('description_en'))
    quillEn.root.innerHTML = `{!! old('description_en') !!}`;
@endif

/* ══════════════════════════════════════════
   Price on Request Toggle
══════════════════════════════════════════ */
const priceOnRequestChk = document.getElementById('priceOnRequest');
const priceFieldsWrap   = document.getElementById('priceFieldsWrap');
const priceInput        = document.getElementById('priceInput');
const oldPriceInput     = document.getElementById('oldPriceInput');
const discountInput     = document.getElementById('discountInput');
const discountBadge     = document.getElementById('discountBadge');

function togglePriceFields() {
    const isOn = priceOnRequestChk.checked;
    priceFieldsWrap.classList.toggle('disabled', isOn);
    if (isOn) {
        priceInput.value    = '';
        oldPriceInput.value = '';
        discountInput.value = '';
        discountBadge.classList.remove('show');
    }
}
priceOnRequestChk.addEventListener('change', togglePriceFields);
togglePriceFields();

/* ══════════════════════════════════════════
   Live Discount Calculator
══════════════════════════════════════════ */
function calcDiscountFromPrices() {
    const price    = parseFloat(priceInput.value);
    const oldPrice = parseFloat(oldPriceInput.value);

    if (price > 0 && oldPrice > 0 && oldPrice > price) {
        const pct = Math.round(((oldPrice - price) / oldPrice) * 100);
        discountInput.value = pct;
        discountBadge.textContent = `${pct}% Discount — Save ${(oldPrice - price).toFixed(2)} EGP`;
        discountBadge.classList.add('show');
    } else {
        discountInput.value = '';
        discountBadge.classList.remove('show');
    }
}

function calcOldPriceFromDiscount() {
    const price   = parseFloat(priceInput.value);
    const pct     = parseFloat(discountInput.value);

    if (price > 0 && pct > 0 && pct < 100) {
        const oldPrice = price / (1 - pct / 100);
        oldPriceInput.value = oldPrice.toFixed(2);
        discountBadge.textContent = `${pct}% Discount — Old Price ${oldPrice.toFixed(2)} EGP`;
        discountBadge.classList.add('show');
    } else if (!pct) {
        oldPriceInput.value = '';
        discountBadge.classList.remove('show');
    }
}

priceInput.addEventListener('input',    calcDiscountFromPrices);
oldPriceInput.addEventListener('input', calcDiscountFromPrices);
discountInput.addEventListener('input', calcOldPriceFromDiscount);

// Trigger calculations on load if validation failed and old inputs exist
calcDiscountFromPrices();

/* ══════════════════════════════════════════
   Tags System (Sizes & Colors)
══════════════════════════════════════════ */
let sizesArr = [], colorsArr = [];

function initTags(inputId, containerId, hiddenId, arr, bg, textColor) {
    const input = document.getElementById(inputId);
    const container = document.getElementById(containerId);
    const hidden = document.getElementById(hiddenId);

    if(hidden.value) {
        let existing = hidden.value.split(',');
        existing.forEach(v => { if(v) addTagElement(v, container, hidden, arr, bg, textColor); });
    }

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const val = this.value.trim().replace(/,/g, '');
            if (val && !arr.includes(val)) {
                addTagElement(val, container, hidden, arr, bg, textColor);
            }
            this.value = '';
        }
    });
}

function addTagElement(val, container, hidden, arr, bg, textColor) {
    arr.push(val);
    const span = document.createElement('span');
    span.className = 'badge d-inline-flex align-items-center gap-1 px-3 py-2 animate__animated animate__fadeIn';
    span.style.cssText = `background:${bg}; color:${textColor}; font-size:13px; border-radius:20px;`;
    span.innerHTML = `${val} <i class="mdi mdi-close ms-1" style="cursor:pointer;" onclick="removeTag(this, '${val}', '${hidden.id}', ${JSON.stringify(arr)})"></i>`;
    container.appendChild(span);
    hidden.value = arr.join(',');
}

function removeTag(el, val, hiddenId, arrRef) {
    el.parentElement.remove();
    const hidden = document.getElementById(hiddenId);
    let items = hidden.value.split(',').filter(i => i !== val);
    hidden.value = items.join(',');
}

initTags('sizeInput', 'sizesContainer', 'sizesHidden', sizesArr, 'linear-gradient(135deg, #f7971e, #ffd200)', 'black');
initTags('colorInput', 'colorsContainer', 'colorsHidden', colorsArr, 'linear-gradient(135deg, #667eea, #764ba2)', 'white');

/* ══════════════════════════════════════════
   Dynamic Gallery Rows
══════════════════════════════════════════ */
function addGalleryRow() {
    const container = document.getElementById('galleryContainer');
    const div = document.createElement('div');
    div.className = 'gallery-row border rounded p-3 mb-3 bg-light position-relative animate__animated animate__fadeIn';
    div.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size:10px" onclick="this.parentElement.remove()"></button>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="small fw-bold">Image</label>
                <input type="file" name="gallery[]" class="form-control form-control-sm" accept="image/*" required>
            </div>
            <div class="col-md-4">
                <label class="small fw-bold">Color (English)</label>
                <input type="text" name="gallery_color_en[]" class="form-control form-control-sm" placeholder="e.g. Midnight Black">
            </div>
            <div class="col-md-4">
                <label class="small fw-bold">Color (Arabic)</label>
                <input type="text" name="gallery_color_ar[]" class="form-control form-control-sm" placeholder="e.g. أسود ليل">
            </div>
        </div>
    `;
    container.appendChild(div);
}

/* ══════════════════════════════════════════
   Main Image Preview
══════════════════════════════════════════ */
function previewMain(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('mainImagePreview').src = e.target.result;
        document.getElementById('mainImagePreview').classList.remove('d-none');
        document.getElementById('mainImagePlaceholder').classList.add('d-none');
    };
    reader.readAsDataURL(file);
}

/* ══════════════════════════════════════════
   Category / Subcategory Logic
══════════════════════════════════════════ */
const parentSelect = document.getElementById('parent_category');
const subSelect = document.getElementById('subcategory');
const hiddenCatId = document.getElementById('category_id_hidden');

parentSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const children = selected.value && selected.dataset.children ? JSON.parse(selected.dataset.children) : [];

    if (children.length > 0) {
        subSelect.innerHTML = '<option value="">-- Select Subcategory --</option>';
        children.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.name;
            subSelect.appendChild(opt);
        });
        subSelect.disabled = false;
        hiddenCatId.value = this.value;
    } else {
        subSelect.innerHTML = '<option value="">-- No Subcategories --</option>';
        subSelect.disabled = true;
        hiddenCatId.value = this.value;
    }
});

subSelect.addEventListener('change', function() {
    if (this.value) {
        hiddenCatId.value = this.value;
    } else {
        hiddenCatId.value = parentSelect.value;
    }
});

// Restore old values on validation error
@if(old('category_id'))
    (function() {
        const oldCatId = '{{ old("category_id") }}';
        for (let i = 0; i < parentSelect.options.length; i++) {
            const opt = parentSelect.options[i];
            if (!opt.value || !opt.dataset.children) continue;
            const children = JSON.parse(opt.dataset.children);
            const found = children.find(c => c.id == oldCatId);
            if (found) {
                parentSelect.value = opt.value;
                parentSelect.dispatchEvent(new Event('change'));
                subSelect.value = oldCatId;
                hiddenCatId.value = oldCatId;
                return;
            }
            if (opt.value == oldCatId) {
                parentSelect.value = opt.value;
                parentSelect.dispatchEvent(new Event('change'));
                hiddenCatId.value = oldCatId;
                return;
            }
        }
    })();
@endif

/* ══════════════════════════════════════════
   Form Submission Logic
══════════════════════════════════════════ */
document.getElementById('submitBtn').addEventListener('click', () => {
    document.getElementById('description_ar_input').value = quillAr.root.innerHTML;
    document.getElementById('description_en_input').value = quillEn.root.innerHTML;

    if (!hiddenCatId.value && parentSelect.value) {
        hiddenCatId.value = parentSelect.value;
    }

    document.getElementById('productForm').submit();
});
</script>
@endpush