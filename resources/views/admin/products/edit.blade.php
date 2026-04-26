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
                                    <input type="text" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}"
                                           class="form-control @error('name_ar') is-invalid @enderror" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-format-title me-1 text-muted"></i>Name (English) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}"
                                           class="form-control @error('name_en') is-invalid @enderror" required>
                                </div>

                                {{-- Categories --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-folder me-1 text-muted"></i>Main Category <span class="text-danger">*</span>
                                    </label>
                                    <select id="parent_category" class="form-control" required>
                                        <option value="">-- Select Main Category --</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            data-children='@json($cat->children->map(fn($c) => ["id" => $c->id, "name" => $c->name_en . " / " . $c->name_ar]))'>
                                            {{ $cat->name_en }} / {{ $cat->name_ar }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-file-tree me-1 text-muted"></i>Subcategory
                                    </label>
                                    <select id="subcategory" class="form-control" disabled>
                                        <option value="">-- Select Main Category First --</option>
                                    </select>
                                </div>
                                <input type="hidden" name="category_id" id="category_id_hidden" value="{{ $product->category_id }}">

                                {{-- Brand --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-tag me-1 text-muted"></i>Brand
                                    </label>
                                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-control">
                                </div>

                                {{-- Stock --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-warehouse me-1 text-muted"></i>Stock
                                    </label>
                                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" min="0">
                                </div>

                                {{-- ══ Price on Request Toggle ══ --}}
                                <div class="col-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="price_on_request"
                                               id="priceOnRequest"
                                               {{ (!$product->price) ? 'checked' : '' }}>
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
                                                       value="{{ old('price', $product->price) }}"
                                                       step="0.01" min="0" class="form-control"
                                                       placeholder="0.00">
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
                                                       value="{{ old('old_price', $product->old_price) }}"
                                                       step="0.01" min="0" class="form-control"
                                                       placeholder="0.00">
                                            </div>
                                        </div>

                                        {{-- Discount % --}}
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label font-weight-medium">
                                                <i class="mdi mdi-percent me-1 text-muted"></i>Discount %
                                                <small class="text-muted fw-normal">(Enter percentage to calculate old price)</small>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" id="discountInput"
                                                       value="{{ old('discount_percent', $product->old_price && $product->price ? round((($product->old_price - $product->price) / $product->old_price) * 100) : '') }}"
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
                                    <i class="mdi mdi-text-subject me-1 text-muted"></i>Description (Arabic)
                                </label>
                                <div id="quill_ar" style="height: 200px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="description_ar" id="description_ar_input"
                                       value="{{ old('description_ar', $product->description_ar) }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-text-subject me-1 text-muted"></i>Description (English)
                                </label>
                                <div id="quill_en" style="height: 200px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="description_en" id="description_en_input"
                                       value="{{ old('description_en', $product->description_en) }}">
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
                                    <i class="mdi mdi-play-circle me-1 text-muted"></i>Video URL (Arabic)
                                </label>
                                <input type="url" name="video_ar" value="{{ old('video_ar', $product->video_ar) }}"
                                       class="form-control @error('video_ar') is-invalid @enderror"
                                       placeholder="https://www.youtube.com/embed/...">
                                <small class="text-muted">Arabic video URL (YouTube embed or Vimeo)</small>
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-play-circle me-1 text-muted"></i>Video URL (English)
                                </label>
                                <input type="url" name="video_en" value="{{ old('video_en', $product->video_en) }}"
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
                                    <i class="mdi mdi-ruler me-1 text-muted"></i>Sizes (Press Enter or Comma)
                                </label>
                                <div id="sizesContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2 border cursor-text min-height-40"></div>
                                <input type="text" id="sizeInput" class="form-control" placeholder="S, M, L...">
                                <input type="hidden" name="sizes" id="sizesHidden"
                                       value="{{ old('sizes', is_array($product->sizes) ? implode(',', $product->sizes) : $product->sizes) }}">
                            </div>
                            <div>
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-palette me-1 text-muted"></i>General Colors (Tags)
                                </label>
                                <div id="colorsContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2 border cursor-text min-height-40"></div>
                                <input type="text" id="colorInput" class="form-control" placeholder="Red, Blue...">
                                <input type="hidden" name="colors" id="colorsHidden"
                                       value="{{ old('colors', is_array($product->colors) ? implode(',', $product->colors) : $product->colors) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Gallery --}}
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
                                    <img src="{{ Storage::url($product->image) }}" id="mainImagePreview"
                                         class="rounded shadow-sm border w-100" style="height:180px; object-fit:cover;">
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
                                        <div class="card border shadow-none overflow-hidden position-relative">
                                            <button type="button"
                                                    class="btn btn-danger btn-sm rounded-circle d-flex align-items-center justify-content-center position-absolute top-0 end-0 m-1"
                                                    style="width:24px;height:24px;padding:0;font-size:12px;z-index:2;"
                                                    onclick="openDeleteModal('{{ route('admin.product-images.destroy', $img->id) }}', '{{ $img->color_en }}')">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                            <img src="{{ Storage::url($img->image) }}" style="height:100px; object-fit:cover;">
                                            <div class="card-footer p-1 bg-light text-center">
                                                <small class="d-block text-truncate">EN: {{ $img->color_en }}</small>
                                                <small class="d-block text-truncate text-muted">AR: {{ $img->color_ar }}</small>
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
                                    <div class="gallery-row border rounded p-3 mb-3 bg-light position-relative">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="small fw-bold">Image File</label>
                                                <input type="file" name="gallery[]" class="form-control form-control-sm" accept="image/*">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold">Color (English)</label>
                                                <input type="text" name="gallery_color_en[]" class="form-control form-control-sm" placeholder="e.g. Midnight Black">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small fw-bold">Color (Arabic)</label>
                                                <input type="text" name="gallery_color_ar[]" class="form-control form-control-sm" placeholder="e.g. Night Black">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addGalleryRow()">
                                    <i class="mdi mdi-plus-circle me-1"></i> Add Another Row
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
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (Arabic)
                                </label>
                                <input type="text" name="meta_title_ar" value="{{ old('meta_title_ar', $product->meta_title_ar) }}"
                                       class="form-control form-control-sm" placeholder="Page title in Arabic" maxlength="70">
                                <div class="text-muted" style="font-size:11px;">Ideally less than 60 characters</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (English)
                                </label>
                                <input type="text" name="meta_title_en" value="{{ old('meta_title_en', $product->meta_title_en) }}"
                                       class="form-control form-control-sm" placeholder="Page title in English" maxlength="70">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-text me-1 text-muted"></i>Meta Description (Arabic)
                                </label>
                                <textarea name="meta_description_ar" rows="2"
                                          class="form-control form-control-sm"
                                          placeholder="Page description in Arabic" maxlength="160">{{ old('meta_description_ar', $product->meta_description_ar) }}</textarea>
                                <div class="text-muted" style="font-size:11px;">Ideally less than 160 characters</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-text me-1 text-muted"></i>Meta Description (English)
                                </label>
                                <textarea name="meta_description_en" rows="2"
                                          class="form-control form-control-sm"
                                          placeholder="Page description in English" maxlength="160">{{ old('meta_description_en', $product->meta_description_en) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-key me-1 text-muted"></i>Keywords (Arabic)
                                </label>
                                <input type="text" name="meta_keywords_ar" value="{{ old('meta_keywords_ar', $product->meta_keywords_ar) }}"
                                       class="form-control form-control-sm" placeholder="keyword1, keyword2, keyword3">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-key me-1 text-muted"></i>Keywords (English)
                                </label>
                                <input type="text" name="meta_keywords_en" value="{{ old('meta_keywords_en', $product->meta_keywords_en) }}"
                                       class="form-control form-control-sm" placeholder="keyword1, keyword2, keyword3">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-medium small">
                                    <i class="mdi mdi-image me-1 text-muted"></i>OG Image <small class="text-muted">(Social Sharing)</small>
                                </label>
                                @if($product->og_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($product->og_image) }}" class="rounded border w-100" style="height:80px; object-fit:cover;">
                                    <span class="badge bg-success mt-1">Current OG Image</span>
                                </div>
                                @endif
                                <input type="file" name="og_image" accept="image/*"
                                       class="form-control form-control-sm @error('og_image') is-invalid @enderror">
                                <div class="text-muted" style="font-size:11px;">1200×630px — Leave blank to use the main image</div>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 bg-dark">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-cog me-2"></i>Status & Visibility</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       {{ $product->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active / Published</label>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                       {{ $product->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>

                            <button type="button" id="submitBtn" class="btn btn-primary btn-lg w-100 shadow-sm mb-3 border-0"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save Changes
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
                {{-- end right column --}}
            </div>
        </form>
    </div>
</div>

{{-- Delete Image Modal --}}
<div class="modal fade" id="deleteImageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width:60px;height:60px;background:linear-gradient(135deg,#f5576c,#ff6b6b);">
                        <i class="mdi mdi-delete-outline text-white" style="font-size:1.8rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Delete Image?</h5>
                <p class="text-muted mb-4">Are you sure you want to delete <strong id="deleteColorName"></strong>? This cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteImageForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn text-white px-4"
                                style="background:linear-gradient(135deg,#f5576c,#ff6b6b);">
                            <i class="mdi mdi-delete me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
/* ══════════════════════════════════════════
   Quill Editor
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

const initialAr = document.getElementById('description_ar_input').value;
const initialEn = document.getElementById('description_en_input').value;
if (initialAr) quillAr.root.innerHTML = initialAr;
if (initialEn) quillEn.root.innerHTML = initialEn;

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
togglePriceFields(); // run on page load

/* ══════════════════════════════════════════
   Live Discount Calculator
   Logic:
     • Edit Price or Old Price  → recalc % badge
     • Edit Discount %          → recalc Old Price from Price
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
        // old_price = price / (1 - pct/100)
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

// Show badge on page load if values already exist
calcDiscountFromPrices();

/* ══════════════════════════════════════════
   Tags (Sizes & Colors)
══════════════════════════════════════════ */
let sizesArr = [], colorsArr = [];

function initTags(inputId, containerId, hiddenId, arr, bg, textColor) {
    const hidden    = document.getElementById(hiddenId);
    const container = document.getElementById(containerId);
    const input     = document.getElementById(inputId);

    if (hidden.value) {
        hidden.value.split(',').forEach(t => {
            if (t.trim()) addTagItem(t.trim(), container, hidden, arr, bg, textColor);
        });
    }

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const val = this.value.trim().replace(/,/g, '');
            if (val && !arr.includes(val)) addTagItem(val, container, hidden, arr, bg, textColor);
            this.value = '';
        }
    });
}

function addTagItem(text, container, hidden, arr, bg, textColor) {
    if (arr.includes(text)) return;
    arr.push(text);

    const span = document.createElement('span');
    span.className = 'badge d-inline-flex align-items-center gap-1 px-3 py-2';
    span.style.cssText = `background:${bg}; color:${textColor}; font-size:13px; border-radius:20px;`;

    const closeBtn = document.createElement('i');
    closeBtn.className = 'mdi mdi-close ms-1';
    closeBtn.style.cursor = 'pointer';
    closeBtn.addEventListener('click', () => {
        span.remove();
        arr.splice(arr.indexOf(text), 1);
        hidden.value = arr.join(',');
    });

    span.appendChild(document.createTextNode(text + ' '));
    span.appendChild(closeBtn);
    container.appendChild(span);
    hidden.value = arr.join(',');
}

initTags('sizeInput',  'sizesContainer',  'sizesHidden',  sizesArr,  'linear-gradient(135deg, #f7971e, #ffd200)', 'black');
initTags('colorInput', 'colorsContainer', 'colorsHidden', colorsArr, 'linear-gradient(135deg, #667eea, #764ba2)', 'white');

/* ══════════════════════════════════════════
   Dynamic Gallery Rows
══════════════════════════════════════════ */
function addGalleryRow() {
    const container = document.getElementById('galleryContainer');
    const div = document.createElement('div');
    div.className = 'gallery-row border rounded p-3 mb-3 bg-light position-relative';
    div.innerHTML = `
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2"
                style="font-size:10px" onclick="this.parentElement.remove()"></button>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="small fw-bold">Image File</label>
                <input type="file" name="gallery[]" class="form-control form-control-sm" accept="image/*" required>
            </div>
            <div class="col-md-4">
                <label class="small fw-bold">Color (English)</label>
                <input type="text" name="gallery_color_en[]" class="form-control form-control-sm" placeholder="e.g. Midnight Black">
            </div>
            <div class="col-md-4">
                <label class="small fw-bold">Color (Arabic)</label>
                <input type="text" name="gallery_color_ar[]" class="form-control form-control-sm" placeholder="e.g. Night Black">
            </div>
        </div>
    `;
    container.appendChild(div);
}

/* ══════════════════════════════════════════
   Main Image Preview
══════════════════════════════════════════ */
function previewMain(event) {
    const reader = new FileReader();
    reader.onload = () => document.getElementById('mainImagePreview').src = reader.result;
    reader.readAsDataURL(event.target.files[0]);
}

/* ══════════════════════════════════════════
   Delete Image Modal
══════════════════════════════════════════ */
function openDeleteModal(url, colorName) {
    document.getElementById('deleteImageForm').action = url;
    document.getElementById('deleteColorName').textContent = colorName || 'this image';
    new bootstrap.Modal(document.getElementById('deleteImageModal')).show();
}

/* ══════════════════════════════════════════
   Category / Subcategory
══════════════════════════════════════════ */
const parentSelect = document.getElementById('parent_category');
const subSelect    = document.getElementById('subcategory');
const hiddenCatId  = document.getElementById('category_id_hidden');

parentSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const children = selected.value && selected.dataset.children
        ? JSON.parse(selected.dataset.children) : [];

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
    hiddenCatId.value = this.value || parentSelect.value;
});

// Auto-select current category on load
(function() {
    const currentCatId = '{{ $product->category_id }}';
    for (let i = 0; i < parentSelect.options.length; i++) {
        const opt = parentSelect.options[i];
        if (!opt.value) continue;
        const children = opt.dataset.children ? JSON.parse(opt.dataset.children) : [];
        const found = children.find(c => c.id == currentCatId);
        if (found) {
            parentSelect.value = opt.value;
            parentSelect.dispatchEvent(new Event('change'));
            subSelect.value = currentCatId;
            hiddenCatId.value = currentCatId;
            return;
        }
        if (opt.value == currentCatId) {
            parentSelect.value = opt.value;
            parentSelect.dispatchEvent(new Event('change'));
            hiddenCatId.value = currentCatId;
            return;
        }
    }
})();

/* ══════════════════════════════════════════
   Form Submit
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