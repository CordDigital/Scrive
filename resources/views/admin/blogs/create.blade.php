@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-editor { min-height: 200px; line-height: 1.7; }
    .img-upload-area {
        border: 2px dashed #dee2e6; border-radius: 8px;
        cursor: pointer; background: #f8f9fa; transition: border-color .2s;
    }
    .img-upload-area:hover { border-color: #667eea; }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Add Blog Post</h4>
            <p class="text-muted mb-0">Create a new blog post</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="mdi mdi-alert-circle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <form id="blogForm" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-8">

                    {{-- Details --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-pencil me-2"></i>Post Details</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Title (Arabic) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" dir="rtl"
                                           class="form-control @error('title_ar') is-invalid @enderror" required>
                                    @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Title (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_en" value="{{ old('title_en') }}"
                                           class="form-control @error('title_en') is-invalid @enderror" required>
                                    @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-folder me-1 text-muted"></i>Category</label>
                                    <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">— Select Category —</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name_en }} / {{ $cat->name_ar }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-account me-1 text-muted"></i>Author <span class="text-danger">*</span></label>
                                    <input type="text" name="author" value="{{ old('author') }}"
                                           class="form-control @error('author') is-invalid @enderror" required>
                                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-calendar me-1 text-muted"></i>Published Date</label>
                                    <input type="date" name="published_at"
                                           value="{{ old('published_at', now()->format('Y-m-d')) }}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#f7971e 0%,#ffd200 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-tag-multiple me-2"></i>Tags</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div id="tagsContainer" class="d-flex flex-wrap gap-2 p-3 rounded mb-2"
                                 style="min-height:50px;border:1px solid #dee2e6;cursor:text;"
                                 onclick="document.getElementById('tagInput').focus()"></div>
                            <input type="text" id="tagInput" class="form-control mt-2" placeholder="Type tag and press Enter or Comma...">
                            <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags') }}">
                            <small class="text-muted d-block mt-2">
                                <i class="mdi mdi-information-outline me-1"></i>Press Enter or Comma to add tag — click × to remove.
                            </small>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-text me-2"></i>Content</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Content (Arabic) <span class="text-danger">*</span></label>
                                <div id="quill_ar" style="border:1px solid {{ $errors->has('content_ar') ? '#dc3545' : '#dee2e6' }};border-radius:4px;"></div>
                                <input type="hidden" name="content_ar" id="content_ar_input" value="{{ old('content_ar') }}">
                                @error('content_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Content (English) <span class="text-danger">*</span></label>
                                <div id="quill_en" style="border:1px solid {{ $errors->has('content_en') ? '#dc3545' : '#dee2e6' }};border-radius:4px;"></div>
                                <input type="hidden" name="content_en" id="content_en_input" value="{{ old('content_en') }}">
                                @error('content_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Cover Image --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-image me-2"></i>Cover Image
                                <small class="fw-normal opacity-75">— Displayed in lists and cards</small>
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="img-upload-area d-flex align-items-center justify-content-center mb-3"
                                 style="height:200px;" onclick="document.getElementById('imageInput').click()">
                                <img id="imagePreview" src="" class="d-none rounded"
                                     style="max-height:196px;max-width:100%;object-fit:cover;">
                                <div id="imagePlaceholder" class="text-center text-muted">
                                    <i class="mdi mdi-cloud-upload" style="font-size:3rem;"></i>
                                    <p class="mt-2 mb-0">Click to upload cover image</p>
                                    <small>JPG, PNG, WEBP — max 4MB</small>
                                </div>
                            </div>
                            <input type="file" name="image" id="imageInput" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror"
                                   onchange="previewCover(event)" required>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Detail Image --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-image-size-select-large me-2"></i>Large Detail Image
                                <small class="fw-normal opacity-75">— Displayed at the top of the article details</small>
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="img-upload-area d-flex align-items-center justify-content-center mb-3"
                                 style="height:250px;" onclick="document.getElementById('detailInput').click()">
                                <img id="detailPreview" src="" class="d-none rounded"
                                     style="max-height:246px;max-width:100%;object-fit:cover;">
                                <div id="detailPlaceholder" class="text-center text-muted">
                                    <i class="mdi mdi-image-size-select-large" style="font-size:3.5rem;color:#4facfe;"></i>
                                    <p class="mt-2 mb-0 fw-semibold">Click to upload large detail image</p>
                                    <small>Will appear at the top of the blog page</small><br>
                                    <small class="text-muted">JPG, PNG, WEBP — max 4MB</small>
                                </div>
                            </div>
                            <input type="file" name="detail_image" id="detailInput" accept="image/*"
                                   class="form-control @error('detail_image') is-invalid @enderror"
                                   onchange="previewDetail(event)">
                            @error('detail_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2">
                                <i class="mdi mdi-information-outline me-1 text-info"></i>
                                Optional — If not provided, the cover image will be used in the details page.
                            </small>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-lg-4">

                    {{-- Slug --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#f7971e 0%,#ffd200 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-link me-2"></i>Slug (URL)</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-link-variant me-1 text-muted"></i>Slug (English)</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:12px;">/blog/</span>
                                    <input type="text" name="slug_en" id="slugEnInput" value="{{ old('slug_en') }}"
                                           class="form-control @error('slug_en') is-invalid @enderror" placeholder="auto-generated">
                                </div>
                                @error('slug_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <small class="text-muted">Leave empty for auto-generation</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-link-variant me-1 text-muted"></i>Slug (Arabic)</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:12px;">/blog/</span>
                                    <input type="text" name="slug_ar" value="{{ old('slug_ar') }}"
                                           class="form-control @error('slug_ar') is-invalid @enderror"
                                           placeholder="auto-generated" dir="rtl">
                                </div>
                                @error('slug_ar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-magnify me-2"></i>SEO Settings</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (AR)</label>
                                <input type="text" name="meta_title_ar" value="{{ old('meta_title_ar') }}"
                                       class="form-control" placeholder="Leave empty to use title" dir="rtl">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (EN)</label>
                                <input type="text" name="meta_title_en" value="{{ old('meta_title_en') }}"
                                       class="form-control" placeholder="Leave empty to use title">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-text me-1 text-muted"></i>Meta Description (AR)</label>
                                <textarea name="meta_description_ar" rows="2" class="form-control"
                                          placeholder="Short SEO description..." dir="rtl">{{ old('meta_description_ar') }}</textarea>
                                <small class="text-muted">Max 160 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-text me-1 text-muted"></i>Meta Description (EN)</label>
                                <textarea name="meta_description_en" rows="2" class="form-control"
                                          placeholder="Short SEO description...">{{ old('meta_description_en') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-tag-multiple me-1 text-muted"></i>Meta Keywords (AR)</label>
                                <input type="text" name="meta_keywords_ar" value="{{ old('meta_keywords_ar') }}"
                                       class="form-control" placeholder="keyword1, keyword2" dir="rtl">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-tag-multiple me-1 text-muted"></i>Meta Keywords (EN)</label>
                                <input type="text" name="meta_keywords_en" value="{{ old('meta_keywords_en') }}"
                                       class="form-control" placeholder="keyword1, keyword2">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-share-variant me-1 text-muted"></i>OG Image <small class="text-muted fw-normal">(Social Share)</small></label>
                                <input type="file" name="og_image" accept="image/*" class="form-control" onchange="previewOg(event)">
                                <img id="ogPreview" src="" class="d-none rounded mt-2" style="max-height:80px;">
                                <small class="text-muted d-block mt-1">Leave empty to use main image</small>
                            </div>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0" style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-cog me-2"></i>Post Settings</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium d-block"><i class="mdi mdi-toggle-switch me-1 text-muted"></i>Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Published</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="button" id="submitBtn"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save Post
                            </button>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-block btn-lg btn-outline-secondary">
                                <i class="mdi mdi-close me-2"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const toolbarOptions = [
    [{ 'font': [] }, { 'size': ['small', false, 'large', 'huge'] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'align': [] }],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    ['link', 'blockquote'], ['clean']
];

const quillAr = new Quill('#quill_ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });
quillAr.root.setAttribute('dir', 'rtl');
const arVal = document.getElementById('content_ar_input').value;
if (arVal) quillAr.root.innerHTML = arVal;

const quillEn = new Quill('#quill_en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const enVal = document.getElementById('content_en_input').value;
if (enVal) quillEn.root.innerHTML = enVal;

// Tags Logic
let tags = [];
const existingTags = document.getElementById('tagsHidden').value;
if (existingTags) existingTags.split(',').forEach(t => { if (t.trim()) addTag(t.trim()); });

document.getElementById('tagInput').addEventListener('keydown', function (e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        const val = this.value.trim().replace(/,/g, '');
        if (val && !tags.includes(val)) addTag(val);
        this.value = '';
    }
    if (e.key === 'Backspace' && this.value === '' && tags.length > 0) removeTag(tags[tags.length - 1]);
});

function addTag(text) {
    if (tags.includes(text)) return;
    tags.push(text);
    const span = document.createElement('span');
    span.className = 'badge d-inline-flex align-items-center gap-1 px-3 py-2';
    span.style.cssText = 'background:linear-gradient(135deg,#667eea,#764ba2);color:white;font-size:13px;border-radius:20px;';
    span.dataset.tag = text;
    span.innerHTML = `${text} <i class="mdi mdi-close ms-1" style="cursor:pointer;font-size:14px;" onclick="removeTag('${text.replace(/'/g,"\\'")}')"></i>`;
    document.getElementById('tagsContainer').appendChild(span);
    syncTags();
}
function removeTag(text) {
    tags = tags.filter(t => t !== text);
    const span = document.querySelector(`[data-tag="${CSS.escape(text)}"]`);
    if (span) span.remove();
    syncTags();
}
function syncTags() { document.getElementById('tagsHidden').value = tags.join(','); }

// Form Submission
document.getElementById('submitBtn').addEventListener('click', function () {
    document.getElementById('content_ar_input').value = quillAr.root.innerHTML;
    document.getElementById('content_en_input').value = quillEn.root.innerHTML;
    syncTags();
    document.getElementById('blogForm').submit();
});

// Image Previews
function previewCover(e) {
    if (!e.target.files || !e.target.files[0]) return;
    const r = new FileReader();
    r.onload = ev => {
        const img = document.getElementById('imagePreview');
        const ph  = document.getElementById('imagePlaceholder');
        img.src = ev.target.result; img.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
    };
    r.readAsDataURL(e.target.files[0]);
}

function previewDetail(e) {
    if (!e.target.files || !e.target.files[0]) return;
    const r = new FileReader();
    r.onload = ev => {
        const img = document.getElementById('detailPreview');
        const ph  = document.getElementById('detailPlaceholder');
        img.src = ev.target.result; img.classList.remove('d-none');
        if (ph) ph.classList.add('d-none');
    };
    r.readAsDataURL(e.target.files[0]);
}

function previewOg(e) {
    if (!e.target.files || !e.target.files[0]) return;
    const r = new FileReader();
    r.onload = ev => {
        const img = document.getElementById('ogPreview');
        img.src = ev.target.result;
        img.classList.remove('d-none');
    };
    r.readAsDataURL(e.target.files[0]);
}

// English Title to Slug generator
document.getElementById('slugEnInput').addEventListener('focus', function () {
    if (!this.value) {
        const t = document.querySelector('input[name="title_en"]').value;
        if (t) {
            this.value = t.toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g,'')
                .replace(/\s+/g,'-')
                .replace(/-+/g,'-');
        }
    }
});
</script>
@endpush
