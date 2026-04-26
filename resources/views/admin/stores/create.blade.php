@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        background-color: #f8f9fa;
        border-top-left-radius: 8px; border-top-right-radius: 8px;
        border-color: #dee2e6;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;
        border-color: #dee2e6; background-color: #fff;
    }
    .ql-editor { min-height: 250px; font-size: 16px; line-height: 1.6; }

    .img-upload-area {
        border: 2px dashed #dee2e6; border-radius: 8px;
        cursor: pointer; background: #f8f9fa; transition: all .2s;
    }
    .img-upload-area:hover { border-color: #667eea; background: #f1f3f9; }

    /* تنسيق معاينة الجاليري */
    .gallery-preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-top: 15px;
    }
    .gallery-preview-item {
        position: relative;
        height: 100px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #ddd;
    }
    .gallery-preview-item img {
        width: 100%; height: 100%; object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Add New Store</h4>
            <p class="text-muted mb-0">Create a store with cover and gallery images</p>
        </div>
        <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    @if($errors->any())
    <div class="col-12 mb-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <form id="storeForm" action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-8">
                    {{-- Names --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Name (Arabic) *</label>
                                    <input type="text" name="name_ar" class="form-control" dir="rtl" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Name (English) *</label>
                                    <input type="text" name="name_en" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description (Word Editor) --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold"><i class="mdi mdi-text text-success me-2"></i>Store Description</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Arabic Description</label>
                                <div id="quill_ar" dir="rtl"></div>
                                <input type="hidden" name="description_ar" id="description_ar_input">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted">English Description</label>
                                <div id="quill_en"></div>
                                <input type="hidden" name="description_en" id="description_en_input">
                            </div>
                        </div>
                    </div>

                    {{-- Sub Images (Gallery) --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold"><i class="mdi mdi-image-multiple text-info me-2"></i>Store Gallery (Sub Images)</h6>
                        </div>
                        <div class="card-body">
                            <div class="img-upload-area p-4 text-center" onclick="document.getElementById('galleryInput').click()">
                                <i class="mdi mdi-plus-box text-muted" style="font-size: 2.5rem;"></i>
                                <p class="mb-0">Click to select multiple images for gallery</p>
                                <small class="text-muted">You can select many files at once</small>
                            </div>
                            <input type="file" name="images[]" id="galleryInput" class="d-none" multiple accept="image/*" onchange="previewGallery(event)">

                            {{-- Gallery Preview --}}
                            <div id="galleryPreview" class="gallery-preview-container"></div>
                        </div>
                    </div>

                    {{-- Second Description (Word Editor) --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold"><i class="mdi mdi-text text-info me-2"></i>Second Description</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Arabic Second Description</label>
                                <div id="quill_second_ar" dir="rtl"></div>
                                <input type="hidden" name="description_second_ar" id="description_second_ar_input">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted">English Second Description</label>
                                <div id="quill_second_en"></div>
                                <input type="hidden" name="description_second_en" id="description_second_en_input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">
                    {{-- Main Cover --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold"><i class="mdi mdi-image text-danger me-2"></i>Main Cover</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="img-upload-area d-flex align-items-center justify-content-center mb-3"
                                 style="min-height: 200px;" onclick="document.getElementById('coverInput').click()">
                                <img id="coverPreview" src="" class="d-none rounded img-fluid" style="max-height: 180px;">
                                <div id="coverPlaceholder">
                                    <i class="mdi mdi-cloud-upload text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-0">Select Cover</p>
                                </div>
                            </div>
                            <input type="file" name="cover_image" id="coverInput" class="d-none" accept="image/*" onchange="previewCover(event)">
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold"><i class="mdi mdi-image-size-select-small text-info me-2"></i>Thumbnail (Small Image)</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="img-upload-area d-flex align-items-center justify-content-center mb-3"
                                 style="min-height: 150px;" onclick="document.getElementById('thumbnailInput').click()">
                                <img id="thumbnailPreview" src="" class="d-none rounded img-fluid" style="max-height: 130px;">
                                <div id="thumbnailPlaceholder">
                                    <i class="mdi mdi-cloud-upload text-muted" style="font-size: 2.5rem;"></i>
                                    <p class="text-muted mb-0">Select Thumbnail</p>
                                </div>
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="d-none" accept="image/*" onchange="previewThumbnail(event)">
                        </div>
                    </div>

                    {{-- Sort Order --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <label class="fw-bold mb-2">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <button type="button" id="submitBtn" class="btn btn-primary btn-lg w-100 mb-3 shadow">
                        <i class="mdi mdi-content-save me-1"></i> Save Store
                    </button>
                    <a href="{{ route('admin.stores.index') }}" class="btn btn-light w-100">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Quill Editors setup (Word Style)
    const toolbarOptions = [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        [{ 'direction': 'rtl' }],
        ['link', 'image'],
        ['clean']
    ];

    const quillAr = new Quill('#quill_ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });
    const quillEn = new Quill('#quill_en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
    const quillSecondAr = new Quill('#quill_second_ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });
    const quillSecondEn = new Quill('#quill_second_en', { theme: 'snow', modules: { toolbar: toolbarOptions } });

    // Cover Preview
    function previewCover(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = ev => {
                document.getElementById('coverPreview').src = ev.target.result;
                document.getElementById('coverPreview').classList.remove('d-none');
                document.getElementById('coverPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    }

    // Gallery Preview (Multi-images)
    function previewGallery(e) {
        const container = document.getElementById('galleryPreview');
        container.innerHTML = ''; // Clear previous

        if (e.target.files) {
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = ev => {
                    const div = document.createElement('div');
                    div.className = 'gallery-preview-item';
                    div.innerHTML = `<img src="${ev.target.result}">`;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // Thumbnail Preview
    function previewThumbnail(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = ev => {
                document.getElementById('thumbnailPreview').src = ev.target.result;
                document.getElementById('thumbnailPreview').classList.remove('d-none');
                document.getElementById('thumbnailPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    }

    // Form Submit
    document.getElementById('submitBtn').addEventListener('click', function() {
        document.getElementById('description_ar_input').value = quillAr.root.innerHTML;
        document.getElementById('description_en_input').value = quillEn.root.innerHTML;
        document.getElementById('description_second_ar_input').value = quillSecondAr.root.innerHTML;
        document.getElementById('description_second_en_input').value = quillSecondEn.root.innerHTML;
        document.getElementById('storeForm').submit();
    });
</script>
@endpush
