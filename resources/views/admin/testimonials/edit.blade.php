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
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold mb-0">Edit Product</h4>
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
        <form id="productForm" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-8">

                    {{-- Basic Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-package-variant me-2"></i>Product Details</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Name (Arabic) <span class="text-danger">*</span></label>
                                    <input type="text" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}"
                                           class="form-control @error('name_ar') is-invalid @enderror" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Name (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}"
                                           class="form-control @error('name_en') is-invalid @enderror" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-shape me-1 text-muted"></i>Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name_en }} / {{ $cat->name_ar }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-tag me-1 text-muted"></i>Brand</label>
                                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-currency-usd me-1 text-muted"></i>Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-currency-usd me-1 text-muted"></i>Old Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}" step="0.01" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-package-variant me-1 text-muted"></i>Stock</label>
                                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" min="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" class="form-control" min="0">
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
                                <label class="form-label font-weight-medium"><i class="mdi mdi-text-subject me-1 text-muted"></i>Description (Arabic)</label>
                                <div id="quill_ar" style="height: 200px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="description_ar" id="description_ar_input" value="{{ old('description_ar', $product->description_ar) }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-text-subject me-1 text-muted"></i>Description (English)</label>
                                <div id="quill_en" style="height: 200px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="description_en" id="description_en_input" value="{{ old('description_en', $product->description_en) }}">
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
                                <label class="form-label font-weight-medium"><i class="mdi mdi-ruler me-1 text-muted"></i>Sizes (Press Enter or Comma)</label>
                                <div id="sizesContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2 border cursor-text min-height-40"></div>
                                <input type="text" id="sizeInput" class="form-control" placeholder="S, M, L...">
                                <input type="hidden" name="sizes" id="sizesHidden" value="{{ old('sizes', is_array($product->sizes) ? implode(',', $product->sizes) : $product->sizes) }}">
                            </div>
                            <div>
                                <label class="form-label font-weight-medium"><i class="mdi mdi-palette me-1 text-muted"></i>General Colors (Tags)</label>
                                <div id="colorsContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2 border cursor-text min-height-40"></div>
                                <input type="text" id="colorInput" class="form-control" placeholder="Red, Blue...">
                                <input type="hidden" name="colors" id="colorsHidden" value="{{ old('colors', is_array($product->colors) ? implode(',', $product->colors) : $product->colors) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Gallery with Color Mapping --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-image-multiple me-2"></i>Product Gallery & Color Images</h6>
                        </div>
                        <div class="card-body pt-4">
                            {{-- Main Image Preview --}}
                            <div class="mb-4 pb-4 border-bottom">
                                <label class="form-label font-weight-bold text-dark">Main Image</label>
                                <div class="position-relative mb-3" style="max-width: 250px;">
                                    <img src="{{ Storage::url($product->image) }}" id="mainImagePreview" class="rounded shadow-sm border w-100" style="height:180px; object-fit:cover;">
                                    <span class="badge position-absolute top-0 start-0 m-2 bg-success">Current Main</span>
                                </div>
                                <input type="file" name="image" accept="image/*" class="form-control" onchange="previewMain(event)">
                            </div>

                            {{-- Existing Gallery --}}
                            @if($product->images->count() > 0)
                            <div class="mb-4">
                                <label class="form-label font-weight-bold">Current Gallery</label>
                                <div class="row g-2">
                                    @foreach($product->images as $img)
                                    <div class="col-md-3">
                                        <div class="card border shadow-none overflow-hidden h-100">
                                            <img src="{{ Storage::url($img->image) }}" style="height:100px; object-fit:cover;">
                                            <div class="card-footer p-2 bg-light text-center">
                                                <small class="d-block text-truncate fw-bold">EN: {{ $img->color_en ?? 'N/A' }}</small>
                                                <small class="d-block text-truncate text-muted">AR: {{ $img->color_ar ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Dynamic Gallery Rows --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-bold text-primary">Add New Images with Specific Colors</label>
                                <div id="galleryContainer">
                                    </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addGalleryRow()">
                                    <i class="mdi mdi-plus-circle me-1"></i> Add Image Row
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column (Settings) --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 bg-dark">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-cog me-2"></i>Status & Visibility</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active / Published</label>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>

                            <button type="button" id="submitBtn" class="btn btn-primary btn-lg w-100 shadow-sm mb-3 border-0"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Update Product
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </div>

                    {{-- Meta Info --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body small">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Created:</span>
                                <span>{{ $product->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Update:</span>
                                <span>{{ $product->updated_at->diffForHumans() }}</span>
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
{{-- Quill Editor JS --}}
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    // --- Quill Editor Setup ---
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

    // تحميل البيانات الحالية داخل المحرر
    quillAr.root.innerHTML = document.getElementById('description_ar_input').value;
    quillEn.root.innerHTML = document.getElementById('description_en_input').value;

    // --- Tags Logic (Sizes & Colors) ---
    function initTags(inputId, containerId, hiddenId, bg, textColor) {
        let arr = [];
        const hiddenInput = document.getElementById(hiddenId);
        const container = document.getElementById(containerId);
        const input = document.getElementById(inputId);

        // جلب البيانات المخزنة مسبقاً
        if (hiddenInput.value) {
            let items = hiddenInput.value.split(',').filter(i => i.trim() !== "");
            items.forEach(val => {
                if (!arr.includes(val)) addTagElement(val, container, hiddenInput, arr, bg, textColor);
            });
        }

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const val = this.value.trim().replace(/,/g, '');
                if (val && !arr.includes(val)) {
                    addTagElement(val, container, hiddenInput, arr, bg, textColor);
                }
                this.value = '';
            }
        });
    }

    function addTagElement(val, container, hiddenInput, arr, bg, textColor) {
        arr.push(val);
        const span = document.createElement('span');
        span.className = 'badge d-inline-flex align-items-center gap-1 px-3 py-2 animate__animated animate__fadeIn';
        span.style.cssText = `background:${bg}; color:${textColor}; font-size:13px; border-radius:20px;`;
        span.innerHTML = `${val} <i class="mdi mdi-close ms-1" style="cursor:pointer;"></i>`;

        span.querySelector('i').onclick = function() {
            span.remove();
            let index = arr.indexOf(val);
            if (index > -1) arr.splice(index, 1);
            hiddenInput.value = arr.join(',');
        };

        container.appendChild(span);
        hiddenInput.value = arr.join(',');
    }

    initTags('sizeInput', 'sizesContainer', 'sizesHidden', 'linear-gradient(135deg, #f7971e, #ffd200)', 'black');
    initTags('colorInput', 'colorsContainer', 'colorsHidden', 'linear-gradient(135deg, #667eea, #764ba2)', 'white');

    // --- Dynamic Gallery Rows ---
    function addGalleryRow() {
        const container = document.getElementById('galleryContainer');
        const div = document.createElement('div');
        div.className = 'gallery-row border rounded p-3 mb-3 bg-light position-relative animate__animated animate__fadeIn';
        div.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size:10px" onclick="this.parentElement.remove()"></button>
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="small fw-bold">Image File</label>
                    <input type="file" name="gallery[]" class="form-control form-control-sm" accept="image/*" required>
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">Color (English)</label>
                    <input type="text" name="gallery_color_en[]" class="form-control form-control-sm" placeholder="e.g. Red">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold">Color (Arabic)</label>
                    <input type="text" name="gallery_color_ar[]" class="form-control form-control-sm" placeholder="مثلاً: أحمر">
                </div>
            </div>`;
        container.appendChild(div);
    }

    // --- Preview Main Image ---
    function previewMain(event) {
        const reader = new FileReader();
        reader.onload = () => document.getElementById('mainImagePreview').src = reader.result;
        reader.readAsDataURL(event.target.files[0]);
    }

    // --- Form Submission ---
    document.getElementById('submitBtn').addEventListener('click', () => {
        document.getElementById('description_ar_input').value = quillAr.root.innerHTML;
        document.getElementById('description_en_input').value = quillEn.root.innerHTML;
        document.getElementById('productForm').submit();
    });
</script>
@endpush
