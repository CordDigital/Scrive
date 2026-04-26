@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-size: 15px; }
    .ql-editor { min-height: 200px; line-height: 1.7; }
    .ql-toolbar { border-radius: 4px 4px 0 0; }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">About Page</h4>
            <p class="text-muted mb-0">Manage your about page content</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <form action="{{ route('admin.about.update') }}" method="POST"
              enctype="multipart/form-data" id="about-form">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Left Col --}}
                <div class="col-lg-8">

                    {{-- Description Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-text me-2"></i>
                                Description
                            </h6>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Arabic --}}
                            <div class="mb-4">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-translate me-1 text-muted"></i>Description (Arabic) <span class="text-danger">*</span>
                                </label>
                                <div id="quill_ar"
                                     style="border: 1px solid {{ $errors->has('description_ar') ? '#dc3545' : '#dee2e6' }}; border-radius: 4px;"></div>
                                <input type="hidden"
                                       name="description_ar"
                                       id="description_ar_input"
                                       value="{{ old('description_ar', $about->description_ar) }}">
                                @error('description_ar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- English --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-translate me-1 text-muted"></i>Description (English) <span class="text-danger">*</span>
                                </label>
                                <div id="quill_en"
                                     style="border: 1px solid {{ $errors->has('description_en') ? '#dc3545' : '#dee2e6' }}; border-radius: 4px;"></div>
                                <input type="hidden"
                                       name="description_en"
                                       id="description_en_input"
                                       value="{{ old('description_en', $about->description_en) }}">
                                @error('description_en')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Images Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-image-multiple me-2"></i>
                                Images (3 Images)
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                @foreach([1, 2, 3] as $i)
                                <div class="col-md-4 mb-4">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-image me-1 text-muted"></i>Image {{ $i }}</label>

                                    <div class="position-relative mb-2">
                                        @if($about->{"image_$i"})
                                        <img src="{{ Storage::url($about->{"image_$i"}) }}"
                                             id="preview-{{ $i }}"
                                             class="rounded shadow-sm d-block"
                                             style="width: 100%; height: 140px; object-fit: cover;">
                                        <span class="badge position-absolute top-0 start-0 m-1"
                                              style="background: linear-gradient(135deg, #11998e, #38ef7d); font-size: 0.65rem;">
                                            Current
                                        </span>
                                        @else
                                        <div id="preview-{{ $i }}-container"
                                             class="rounded mb-2 d-flex align-items-center justify-content-center"
                                             style="width: 100%; height: 140px; background: #f8f9fa; border: 2px dashed #dee2e6;">
                                            <img id="preview-{{ $i }}" src="" class="rounded d-none"
                                                 style="width: 100%; height: 140px; object-fit: cover;">
                                            <span id="placeholder-{{ $i }}" class="text-muted">
                                                <i class="mdi mdi-image-plus" style="font-size: 2rem;"></i>
                                            </span>
                                        </div>
                                        @endif
                                    </div>

                                    <input type="file"
                                           name="image_{{ $i }}"
                                           accept="image/*"
                                           class="form-control form-control-sm @error("image_$i") is-invalid @enderror"
                                           onchange="previewImg(event, {{ $i }})">
                                    @error("image_$i")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Leave empty to keep current</small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Col --}}
                <div class="col-lg-4">

                    {{-- Info Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-information me-2"></i>
                                Info
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">Last Updated</small>
                                <small class="font-weight-medium">
                                    {{ $about->updated_at ? $about->updated_at->format('d M Y') : 'Never' }}
                                </small>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <small class="text-muted">Images</small>
                                <small class="font-weight-medium">3 Images</small>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            {{-- ✅ type="button" عشان الـ JS يتحكم في الـ submit --}}
                            <button type="button"
                                    id="save-btn"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i>
                                Save Changes
                            </button>
                            <a href="{{ route('admin.dashboard') }}"
                               class="btn btn-block btn-lg btn-outline-secondary">
                                <i class="mdi mdi-close me-2"></i>
                                Cancel
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
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        ['link', 'blockquote', 'code-block'],
        ['clean']
    ];

    // ── Arabic Editor ─────────────────────────────────────────────────
    const quillAr = new Quill('#quill_ar', {
        theme: 'snow',
        modules: { toolbar: toolbarOptions },
    });
    quillAr.root.setAttribute('dir', 'rtl');

    const contentAr = document.getElementById('description_ar_input').value;
    if (contentAr) quillAr.root.innerHTML = contentAr;

    // ── English Editor ────────────────────────────────────────────────
    const quillEn = new Quill('#quill_en', {
        theme: 'snow',
        modules: { toolbar: toolbarOptions },
    });

    const contentEn = document.getElementById('description_en_input').value;
    if (contentEn) quillEn.root.innerHTML = contentEn;

    // ── ✅ Save Button — يحط المحتوى ثم يعمل submit ───────────────────
    document.getElementById('save-btn').addEventListener('click', function () {
        // نقل محتوى الـ Quill للـ hidden inputs أولاً
        document.getElementById('description_ar_input').value = quillAr.root.innerHTML;
        document.getElementById('description_en_input').value = quillEn.root.innerHTML;

        // ثم نعمل submit للـ form
        document.getElementById('about-form').submit();
    });

    // ── Image Preview ─────────────────────────────────────────────────
    function previewImg(event, index) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview     = document.getElementById('preview-' + index);
            const placeholder = document.getElementById('placeholder-' + index);
            if (preview)     { preview.src = e.target.result; preview.classList.remove('d-none'); }
            if (placeholder) { placeholder.classList.add('d-none'); }
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush