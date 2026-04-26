@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Create Slider</h4>
            <p class="text-muted mb-0">Add a new slider to your homepage</p>
        </div>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary btn-icon-text">
            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
            Back to Sliders
        </a>
    </div>

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
        <form action="{{ route('admin.sliders.store') }}" method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- Left Col --}}
                <div class="col-lg-8">

                    {{-- Titles Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-translate me-2"></i>
                                Titles
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-format-title me-1 text-muted"></i>Title (Arabic) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="title_ar"
                                           value="{{ old('title_ar') }}"
                                           class="form-control @error('title_ar') is-invalid @enderror"
                                           placeholder="أدخل العنوان بالعربي"
                                           required>
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-format-title me-1 text-muted"></i>Title (English) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="title_en"
                                           value="{{ old('title_en') }}"
                                           class="form-control @error('title_en') is-invalid @enderror"
                                           placeholder="Enter title in English"
                                           required>
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-subtitles me-1 text-muted"></i>Subtitle (Arabic) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="subtitle_ar"
                                           value="{{ old('subtitle_ar') }}"
                                           class="form-control @error('subtitle_ar') is-invalid @enderror"
                                           placeholder="أدخل النص الفرعي بالعربي"
                                           required>
                                    @error('subtitle_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-subtitles me-1 text-muted"></i>Subtitle (English) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="subtitle_en"
                                           value="{{ old('subtitle_en') }}"
                                           class="form-control @error('subtitle_en') is-invalid @enderror"
                                           placeholder="Enter subtitle in English"
                                           required>
                                    @error('subtitle_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Image Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-image me-2"></i>
                                Slider Image
                            </h6>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Upload --}}
                            <div>
                                <label class="form-label font-weight-medium">
                                    <i class="mdi mdi-image me-1 text-muted"></i>Upload Image <span class="text-danger">*</span>
                                </label>

                                {{-- Custom Upload Area --}}
                                <div id="uploadArea"
                                     onclick="document.getElementById('imageInput').click()"
                                     style="border: 2px dashed #11998e; border-radius: 12px; padding: 40px;
                                            text-align: center; cursor: pointer; transition: all 0.3s;
                                            background: #f8fff8;">
                                    <i class="mdi mdi-cloud-upload text-success" style="font-size: 3rem;"></i>
                                    <p class="mt-2 mb-1 font-weight-medium">Click to upload image</p>
                                    <small class="text-muted">jpg, jpeg, png, webp — max 2MB</small>
                                </div>

                                <input type="file"
                                       name="image"
                                       id="imageInput"
                                       accept="image/*"
                                       class="d-none @error('image') is-invalid @enderror"
                                       onchange="previewImage(event)"
                                       required>
                                @error('image')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Preview --}}
                            <div id="previewContainer" class="mt-4 d-none">
                                <label class="form-label font-weight-medium">Preview</label>
                                <div class="position-relative d-inline-block">
                                    <img id="imagePreview"
                                         class="rounded shadow-sm d-block"
                                         style="width: 100%; max-width: 350px; height: 200px; object-fit: cover;">
                                    <span class="badge position-absolute top-0 start-0 m-2"
                                          style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                                        <i class="mdi mdi-check me-1"></i> Ready
                                    </span>
                                    <button type="button"
                                            onclick="removeImage()"
                                            class="badge position-absolute top-0 end-0 m-2 border-0"
                                            style="background: linear-gradient(135deg, #f093fb, #f5576c); cursor: pointer;">
                                        <i class="mdi mdi-close"></i> Remove
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Right Col: Settings --}}
                <div class="col-lg-4">

                    {{-- Settings Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-cog me-2"></i>
                                Settings
                            </h6>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Sort Order --}}
                            <div class="mb-4">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                                <input type="number"
                                       name="sort_order"
                                       value="{{ old('sort_order', 0) }}"
                                       min="0"
                                       class="form-control @error('sort_order') is-invalid @enderror">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    Lower number = shown first
                                </small>
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium d-block"><i class="mdi mdi-toggle-switch me-1 text-muted"></i>Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox"
                                           name="is_active"
                                           value="1"
                                           id="is_active"
                                           class="form-check-input"
                                           style="width: 3rem; height: 1.5rem; cursor: pointer;"
                                           checked>
                                    <label class="form-check-label ms-2 font-weight-medium" for="is_active">
                                        Active
                                    </label>
                                </div>
                                <small class="text-muted">Show this slider on the website</small>
                            </div>

                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-plus me-2"></i>
                                Create Slider
                            </button>
                            <a href="{{ route('admin.sliders.index') }}"
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
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('previewContainer').classList.remove('d-none');
            document.getElementById('uploadArea').style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        document.getElementById('imageInput').value = '';
        document.getElementById('previewContainer').classList.add('d-none');
        document.getElementById('uploadArea').style.display = 'block';
    }

    // Drag & Drop
    const uploadArea = document.getElementById('uploadArea');

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#764ba2';
        uploadArea.style.background = '#f5f0ff';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = '#11998e';
        uploadArea.style.background = '#f8fff8';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = '#11998e';
        uploadArea.style.background = '#f8fff8';
        const file = e.dataTransfer.files[0];
        if (file) {
            const input = document.getElementById('imageInput');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            previewImage({ target: input });
        }
    });
</script>
@endpush