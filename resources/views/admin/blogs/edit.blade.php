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
            <h4 class="font-weight-bold mb-1">Edit Blog Post</h4>
            <p class="text-muted mb-0">{{ $blog->title_en }}</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

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
        <form id="blogForm"
              action="{{ route('admin.blogs.update', $blog) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ✅ hidden input للـ remove_detail_image — بيتغير بالـ JS --}}
            <input type="hidden" name="remove_detail_image" id="removeDetailHidden" value="0">

            <div class="row">

                {{-- ── LEFT ── --}}
                <div class="col-lg-8">

                    {{-- Post Details --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-pencil me-2"></i>Post Details</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Title (Arabic) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_ar"
                                           value="{{ old('title_ar', $blog->title_ar) }}"
                                           class="form-control @error('title_ar') is-invalid @enderror"
                                           dir="rtl" required>
                                    @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Title (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_en"
                                           value="{{ old('title_en', $blog->title_en) }}"
                                           class="form-control @error('title_en') is-invalid @enderror" required>
                                    @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-folder me-1 text-muted"></i>Category</label>
                                    <select name="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror">
                                        <option value="">— Select Category —</option>
                                        @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name_en }} / {{ $cat->name_ar }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-account me-1 text-muted"></i>Author <span class="text-danger">*</span></label>
                                    <input type="text" name="author"
                                           value="{{ old('author', $blog->author) }}"
                                           class="form-control @error('author') is-invalid @enderror" required>
                                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-calendar me-1 text-muted"></i>Published Date</label>
                                    <input type="date" name="published_at"
                                           value="{{ old('published_at', $blog->published_at?->format('Y-m-d')) }}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#f7971e 0%,#ffd200 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-tag-multiple me-2"></i>Tags</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div id="tagsContainer"
                                 class="d-flex flex-wrap gap-2 p-3 rounded mb-2"
                                 style="min-height:50px;border:1px solid #dee2e6;cursor:text;"
                                 onclick="document.getElementById('tagInput').focus()"></div>
                            <input type="text" id="tagInput" class="form-control mt-2"
                                   placeholder="Type tag and press Enter or Comma...">
                            <input type="hidden" name="tags" id="tagsHidden"
                                   value="{{ old('tags', $blog->tags ? implode(',', $blog->tags) : '') }}">
                            <small class="text-muted d-block mt-2">
                                <i class="mdi mdi-information-outline me-1"></i>
                                Press Enter or Comma to add — click × to remove.
                            </small>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-text me-2"></i>Content</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Content (Arabic) <span class="text-danger">*</span></label>
                                <div id="quill_ar"
                                     style="border:1px solid {{ $errors->has('content_ar') ? '#dc3545' : '#dee2e6' }};border-radius:4px;"></div>
                                <input type="hidden" name="content_ar" id="content_ar_input"
                                       value="{{ old('content_ar', $blog->content_ar) }}">
                                @error('content_ar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Content (English) <span class="text-danger">*</span></label>
                                <div id="quill_en"
                                     style="border:1px solid {{ $errors->has('content_en') ? '#dc3545' : '#dee2e6' }};border-radius:4px;"></div>
                                <input type="hidden" name="content_en" id="content_en_input"
                                       value="{{ old('content_en', $blog->content_en) }}">
                                @error('content_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Cover Image --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-image me-2"></i>
                                صورة الكارد (Cover Image)
                                <small class="fw-normal opacity-75">— تظهر في القوائم والكروت</small>
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="position-relative mb-3">
                                <img src="{{ Storage::url($blog->image) }}"
                                     id="imagePreview"
                                     class="rounded shadow-sm d-block"
                                     style="width:100%;height:200px;object-fit:cover;">
                                <span class="badge position-absolute top-0 start-0 m-2"
                                      style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                                    <i class="mdi mdi-check me-1"></i>Current
                                </span>
                            </div>
                            <input type="file" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror"
                                   onchange="previewCover(event)">
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-1">اتركه فارغاً للإبقاء على الصورة الحالية</small>
                        </div>
                    </div>

                    {{-- ✅ Detail Image --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#4facfe 0%,#00f2fe 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-image-size-select-large me-2"></i>
                                الصورة الكبيرة (Detail Image)
                                <small class="fw-normal opacity-75">— تظهر في أعلى صفحة المقال</small>
                            </h6>
                        </div>
                        <div class="card-body pt-4">

                            @if($blog->detail_image)
                            {{-- Current detail image --}}
                            <div class="position-relative mb-3" id="detailCurrentArea">
                                <img src="{{ Storage::url($blog->detail_image) }}"
                                     id="detailCurrentImg"
                                     class="rounded shadow-sm d-block"
                                     style="width:100%;max-height:320px;object-fit:cover;">
                                <span class="badge position-absolute top-0 start-0 m-2"
                                      style="background:linear-gradient(135deg,#4facfe,#00f2fe);">
                                    <i class="mdi mdi-check me-1"></i>Current
                                </span>
                                <div class="position-absolute top-0 end-0 m-2 d-flex gap-2">
                                    <button type="button" id="removeDetailBtn"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmRemoveDetail()"
                                            style="font-size:12px;">
                                        <i class="mdi mdi-delete me-1"></i>حذف الصورة
                                    </button>
                                    <button type="button" id="cancelRemoveBtn"
                                            class="btn btn-sm btn-secondary d-none"
                                            onclick="cancelRemoveDetail()"
                                            style="font-size:12px;">
                                        <i class="mdi mdi-undo me-1"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- Preview for new replacement image --}}
                            <div id="detailNewPreviewArea" class="d-none mb-3">
                                <img id="detailNewPreviewImg" src=""
                                     class="rounded shadow-sm d-block"
                                     style="width:100%;max-height:200px;object-fit:cover;">
                                <small class="text-info d-block mt-1">
                                    <i class="mdi mdi-upload me-1"></i>New image ready to upload
                                </small>
                            </div>
                            @else
                            {{-- No current detail image → show upload area --}}
                            <div class="img-upload-area d-flex align-items-center justify-content-center mb-3"
                                 style="height:250px;"
                                 id="detailUploadArea"
                                 onclick="document.getElementById('detailInput').click()">
                                <img id="detailPreviewNew" src="" class="d-none rounded"
                                     style="max-height:246px;max-width:100%;object-fit:cover;">
                                <div id="detailPlaceholder" class="text-center text-muted">
                                    <i class="mdi mdi-image-size-select-large"
                                       style="font-size:3.5rem;color:#4facfe;"></i>
                                    <p class="mt-2 mb-0 fw-semibold">
                                        Click to upload a larger image </p>
                                    <small>

                                     It appears at the top of the article details page.   </small><br>

                                    <small class="text-muted">JPG, PNG, WEBP — max 4MB</small>
                                </div>
                            </div>
                            @endif

                            <input type="file" name="detail_image" id="detailInput"
                                   accept="image/*"
                                   class="form-control @error('detail_image') is-invalid @enderror"
                                   onchange="previewDetail(event)">
                            @error('detail_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2">
                                <i class="mdi mdi-information-outline me-1 text-info"></i>
                                Optional — If you do not upload a picture, the card image will be used on the details page.
                            </small>
                        </div>
                    </div>

                </div>

                {{-- ── RIGHT ── --}}
                <div class="col-lg-4">

                    {{-- Slug --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#f7971e 0%,#ffd200 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-link me-2"></i>Slug (URL)</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-link-variant me-1 text-muted"></i>Slug (English)</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:12px;">/blog/</span>
                                    <input type="text" name="slug_en"
                                           value="{{ old('slug_en', $blog->slug_en) }}"
                                           class="form-control @error('slug_en') is-invalid @enderror">
                                </div>
                                @error('slug_en')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-link-variant me-1 text-muted"></i>Slug (Arabic)</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted" style="font-size:12px;">/blog/</span>
                                    <input type="text" name="slug_ar"
                                           value="{{ old('slug_ar', $blog->slug_ar) }}"
                                           class="form-control @error('slug_ar') is-invalid @enderror" dir="rtl">
                                </div>
                                @error('slug_ar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- SEO --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#11998e 0%,#38ef7d 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-magnify me-2"></i>SEO Settings</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (AR)</label>
                                <input type="text" name="meta_title_ar"
                                       value="{{ old('meta_title_ar', $blog->meta_title_ar) }}"
                                       class="form-control" dir="rtl">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-format-title me-1 text-muted"></i>Meta Title (EN)</label>
                                <input type="text" name="meta_title_en"
                                       value="{{ old('meta_title_en', $blog->meta_title_en) }}"
                                       class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-text me-1 text-muted"></i>Meta Description (AR)</label>
                                <textarea name="meta_description_ar" rows="2"
                                          class="form-control" dir="rtl">{{ old('meta_description_ar', $blog->meta_description_ar) }}</textarea>
                                <small class="text-muted">Max 160 characters</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-text me-1 text-muted"></i>Meta Description (EN)</label>
                                <textarea name="meta_description_en" rows="2"
                                          class="form-control">{{ old('meta_description_en', $blog->meta_description_en) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-tag-multiple me-1 text-muted"></i>Meta Keywords (AR)</label>
                                <input type="text" name="meta_keywords_ar"
                                       value="{{ old('meta_keywords_ar', $blog->meta_keywords_ar) }}"
                                       class="form-control" dir="rtl">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-tag-multiple me-1 text-muted"></i>Meta Keywords (EN)</label>
                                <input type="text" name="meta_keywords_en"
                                       value="{{ old('meta_keywords_en', $blog->meta_keywords_en) }}"
                                       class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-share-variant me-1 text-muted"></i>OG Image <small class="text-muted fw-normal">(Social Share)</small>
                                </label>
                                @if($blog->og_image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($blog->og_image) }}"
                                         class="rounded" style="max-height:60px;">
                                    <span class="badge bg-success ms-1">Current</span>
                                </div>
                                @endif
                                <input type="file" name="og_image" accept="image/*"
                                       class="form-control" onchange="previewOg(event)">
                                <img id="ogPreview" src="" class="d-none rounded mt-2" style="max-height:80px;">
                                <small class="text-muted d-block mt-1">Leave empty to use the main post image</small>
                            </div>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-cog me-2"></i>Post Settings</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                                <input type="number" name="sort_order"
                                       value="{{ old('sort_order', $blog->sort_order) }}"
                                       class="form-control" min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium d-block"><i class="mdi mdi-toggle-switch me-1 text-muted"></i>Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_active" id="is_active"
                                           {{ $blog->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Published</label>
                                </div>
                            </div>
                            <div class="border-top pt-2">
                                <small class="text-muted">
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    Last updated: {{ $blog->updated_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Save --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="button" id="submitBtn"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.blogs.index') }}"
                               class="btn btn-block btn-lg btn-outline-secondary">
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
// ── Quill ─────────────────────────────────────────────────────────────
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

// ── Tags ──────────────────────────────────────────────────────────────
let tags = [];
const existingTags = document.getElementById('tagsHidden').value;
if (existingTags) existingTags.split(',').forEach(t => { if (t.trim()) addTag(t.trim()); });

document.getElementById('tagInput').addEventListener('keydown', function(e) {
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
    const s = document.querySelector(`[data-tag="${CSS.escape(text)}"]`);
    if (s) s.remove();
    syncTags();
}
function syncTags() { document.getElementById('tagsHidden').value = tags.join(','); }

// ── Submit ────────────────────────────────────────────────────────────
document.getElementById('submitBtn').addEventListener('click', function() {
    document.getElementById('content_ar_input').value = quillAr.root.innerHTML;
    document.getElementById('content_en_input').value = quillEn.root.innerHTML;
    syncTags();
    document.getElementById('blogForm').submit();
});

// ── Cover Image Preview ───────────────────────────────────────────────
function previewCover(e) {
    if (!e.target.files[0]) return;
    const r = new FileReader();
    r.onload = ev => { document.getElementById('imagePreview').src = ev.target.result; };
    r.readAsDataURL(e.target.files[0]);
}

// ── ✅ Detail Image Preview ───────────────────────────────────────────
function previewDetail(e) {
    if (!e.target.files[0]) return;
    const r = new FileReader();
    r.onload = ev => {
        // حالة وجود صورة حالية → اعرض preview منفصل
        const newArea = document.getElementById('detailNewPreviewArea');
        const newImg  = document.getElementById('detailNewPreviewImg');
        if (newArea && newImg) {
            newImg.src = ev.target.result;
            newArea.classList.remove('d-none');
        }
        // حالة عدم وجود صورة → اعرض في الـ upload area
        const placeholder = document.getElementById('detailPlaceholder');
        const previewNew  = document.getElementById('detailPreviewNew');
        if (previewNew && placeholder) {
            previewNew.src = ev.target.result;
            previewNew.classList.remove('d-none');
            placeholder.classList.add('d-none');
        }
        // لو كان ناوي يحذف → ارجع عنه لأنه رفع صورة جديدة
        cancelRemoveDetail();
    };
    r.readAsDataURL(e.target.files[0]);
}

// ── ✅ Remove Detail Image ────────────────────────────────────────────
function confirmRemoveDetail() {
    // ✅ الـ hidden input بيتغير لـ 1 → الـ controller بيشيل الصورة
    document.getElementById('removeDetailHidden').value = '1';

    const area = document.getElementById('detailCurrentArea');
    if (area) area.style.opacity = '0.35';

    const removeBtn = document.getElementById('removeDetailBtn');
    const cancelBtn = document.getElementById('cancelRemoveBtn');
    if (removeBtn) removeBtn.classList.add('d-none');
    if (cancelBtn) cancelBtn.classList.remove('d-none');
}

function cancelRemoveDetail() {
    // ✅ ارجع لـ 0 → الـ controller يبقي الصورة
    document.getElementById('removeDetailHidden').value = '0';

    const area = document.getElementById('detailCurrentArea');
    if (area) area.style.opacity = '1';

    const removeBtn = document.getElementById('removeDetailBtn');
    const cancelBtn = document.getElementById('cancelRemoveBtn');
    if (removeBtn) removeBtn.classList.remove('d-none');
    if (cancelBtn) cancelBtn.classList.add('d-none');
}

// ── OG Image Preview ──────────────────────────────────────────────────
function previewOg(e) {
    if (!e.target.files[0]) return;
    const r = new FileReader();
    r.onload = ev => {
        const img = document.getElementById('ogPreview');
        img.src = ev.target.result;
        img.classList.remove('d-none');
    };
    r.readAsDataURL(e.target.files[0]);
}
</script>
@endpush
