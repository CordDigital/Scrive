@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow { background-color: #f8f9fa; border-radius: 8px 8px 0 0; border-color: #dee2e6; }
    .ql-container.ql-snow { border-radius: 0 0 8px 8px; border-color: #dee2e6; background-color: #fff; }
    .ql-editor { min-height: 250px; font-size: 16px; }

    .img-upload-area { border: 2px dashed #dee2e6; border-radius: 8px; cursor: pointer; background: #f8f9fa; position: relative; }

    /* تنسيق الصور الفرعية الموجودة */
    .gallery-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px; margin-top: 20px; }
    .gallery-card { position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #eee; shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .gallery-card img { width: 100%; height: 100px; object-fit: cover; }
    .delete-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255,0,0,0.4); display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: 0.3s; cursor: pointer;
    }
    .gallery-card:hover .delete-overlay { opacity: 1; }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Edit Store: {{ $store->name_en }}</h4>
            <p class="text-muted mb-0">Update store details and manage gallery</p>
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
        <form id="storeForm" action="{{ route('admin.stores.update', $store->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Left Column --}}
                <div class="col-lg-8">
                    {{-- Basic Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Name (Arabic)</label>
                                    <input type="text" name="name_ar" value="{{ old('name_ar', $store->name_ar) }}" class="form-control" dir="rtl" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Name (English)</label>
                                    <input type="text" name="name_en" value="{{ old('name_en', $store->name_en) }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <label class="fw-bold text-muted">Arabic Description</label>
                            <div id="quill_ar" dir="rtl"></div>
                            <input type="hidden" name="description_ar" id="description_ar_input" value="{{ old('description_ar', $store->description_ar) }}">

                            <label class="fw-bold text-muted mt-4">English Description</label>
                            <div id="quill_en"></div>
                            <input type="hidden" name="description_en" id="description_en_input" value="{{ old('description_en', $store->description_en) }}">
                        </div>
                    </div>

                    {{-- Gallery Management --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 pt-3">
                            <h6 class="fw-bold text-info"><i class="mdi mdi-image-multiple me-2"></i>Manage Store Gallery</h6>
                        </div>
                        <div class="card-body">
                            {{-- Upload New To Gallery --}}
                            <div class="mb-3">
                                <label class="small fw-bold">Add New Images to Gallery</label>
                                <input type="file" id="ajaxGalleryInput" class="form-control" multiple accept="image/*">
                                <small class="text-muted">Images selected here will be uploaded immediately.</small>
                            </div>

                            {{-- Existing Images --}}
                            <div class="gallery-container" id="galleryWrapper">
                                @foreach($store->images as $image)
                                <div class="gallery-card" id="img-container-{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}">
                                    <div class="delete-overlay" onclick="deleteImage({{ $image->id }})">
                                        <i class="mdi mdi-delete-forever text-white fs-4"></i>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Second Description --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <label class="fw-bold text-muted">Arabic Second Description</label>
                            <div id="quill_second_ar" dir="rtl"></div>
                            <input type="hidden" name="description_second_ar" id="description_second_ar_input" value="{{ old('description_second_ar', $store->description_second_ar) }}">

                            <label class="fw-bold text-muted mt-4">English Second Description</label>
                            <div id="quill_second_en"></div>
                            <input type="hidden" name="description_second_en" id="description_second_en_input" value="{{ old('description_second_en', $store->description_second_en) }}">
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">
                    {{-- Cover Image --}}
                    <div class="card border-0 shadow-sm mb-4 text-center">
                        <div class="card-body">
                            <label class="fw-bold d-block text-start mb-3">Cover Image</label>
                            <div class="img-upload-area p-2" onclick="document.getElementById('coverInput').click()">
                                @if($store->cover_image)
                                    <img id="coverPreview" src="{{ asset('storage/' . $store->cover_image) }}" class="rounded img-fluid" style="max-height: 200px;">
                                @else
                                    <img id="coverPreview" src="" class="d-none rounded img-fluid" style="max-height: 200px;">
                                @endif
                                <div id="coverPlaceholder" class="{{ $store->cover_image ? 'd-none' : '' }}">
                                    <i class="mdi mdi-cloud-upload text-muted" style="font-size: 3rem;"></i>
                                    <p>Change Cover</p>
                                </div>
                            </div>
                            <input type="file" name="cover_image" id="coverInput" class="d-none" accept="image/*" onchange="previewCover(event)">
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="card border-0 shadow-sm mb-4 text-center">
                        <div class="card-body">
                            <label class="fw-bold d-block text-start mb-3">Thumbnail (Small Image)</label>
                            <div class="img-upload-area p-2" onclick="document.getElementById('thumbnailInput').click()">
                                @if($store->thumbnail)
                                    <img id="thumbnailPreview" src="{{ asset('storage/' . $store->thumbnail) }}" class="rounded img-fluid" style="max-height: 150px;">
                                @else
                                    <img id="thumbnailPreview" src="" class="d-none rounded img-fluid" style="max-height: 150px;">
                                @endif
                                <div id="thumbnailPlaceholder" class="{{ $store->thumbnail ? 'd-none' : '' }}">
                                    <i class="mdi mdi-cloud-upload text-muted" style="font-size: 2.5rem;"></i>
                                    <p>Select Thumbnail</p>
                                </div>
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="d-none" accept="image/*" onchange="previewThumbnail(event)">
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <label class="fw-bold mb-2">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ $store->sort_order }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 shadow mb-3">
                        <i class="mdi mdi-content-save me-1"></i> Update Store
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // 1. Initialize Quill with existing data
    const quillAr = new Quill('#quill_ar', { theme: 'snow' });
    const quillEn = new Quill('#quill_en', { theme: 'snow' });

    const quillSecondAr = new Quill('#quill_second_ar', { theme: 'snow' });
    const quillSecondEn = new Quill('#quill_second_en', { theme: 'snow' });

    quillAr.root.innerHTML = `{!! $store->description_ar !!}`;
    quillEn.root.innerHTML = `{!! $store->description_en !!}`;
    quillSecondAr.root.innerHTML = `{!! $store->description_second_ar !!}`;
    quillSecondEn.root.innerHTML = `{!! $store->description_second_en !!}`;
    quillAr.root.setAttribute('dir', 'rtl');
    quillSecondAr.root.setAttribute('dir', 'rtl');

    // 2. Cover Preview
    function previewCover(e) {
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = ev => {
                document.getElementById('coverPreview').src = ev.target.result;
                document.getElementById('coverPreview').classList.remove('d-none');
                document.getElementById('coverPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    }

    // Thumbnail Preview
    function previewThumbnail(e) {
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = ev => {
                document.getElementById('thumbnailPreview').src = ev.target.result;
                document.getElementById('thumbnailPreview').classList.remove('d-none');
                document.getElementById('thumbnailPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    }

    // 3. Delete Image AJAX (Calls imageDestroy in Controller)
    function deleteImage(imageId) {
        if(confirm('Are you sure you want to delete this image?')) {
            axios.delete(`/admin/stores/images/${imageId}`)
            .then(res => {
                if(res.data.success) {
                    document.getElementById(`img-container-${imageId}`).remove();
                }
            })
            .catch(err => alert('Error deleting image'));
        }
    }

    // 4. AJAX Upload Gallery (Calls imagesUpload in Controller)
    document.getElementById('ajaxGalleryInput').addEventListener('change', function(e) {
        const files = e.target.files;
        if(files.length === 0) return;

        let formData = new FormData();
        for(let i=0; i<files.length; i++) {
            formData.append('images[]', files[i]);
        }

        axios.post(`/admin/stores/{{ $store->id }}/images`, formData)
        .then(res => {
            if(res.data.success) {
                // Refresh or append new images to the view
                location.reload();
            }
        })
        .catch(err => alert('Upload failed. Check file size/type.'));
    });

    // 5. Submit Form
    document.getElementById('storeForm').addEventListener('submit', function() {
        document.getElementById('description_ar_input').value = quillAr.root.innerHTML;
        document.getElementById('description_en_input').value = quillEn.root.innerHTML;
        document.getElementById('description_second_ar_input').value = quillSecondAr.root.innerHTML;
        document.getElementById('description_second_en_input').value = quillSecondEn.root.innerHTML;
    });
</script>
@endpush
