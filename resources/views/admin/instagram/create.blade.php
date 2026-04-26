@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Add Instagram Image</h4>
            <p class="text-muted mb-0">Add a new image to instagram section</p>
        </div>
        <a href="{{ route('admin.instagram.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Alert for errors --}}
    @if ($errors->any())
    <div class="col-12 mb-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <form action="{{ route('admin.instagram.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- Left --}}
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-image me-2"></i>Image & URL
                            </h6>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Image Upload --}}
                            <div class="mb-4">
                                <label class="form-label font-weight-medium">
                                    Image <span class="text-danger">*</span>
                                </label>
                                <div class="border rounded d-flex align-items-center justify-content-center mb-3"
                                     style="height:200px; background:#f8f9fa; border: 2px dashed #dee2e6 !important; cursor:pointer;"
                                     onclick="document.getElementById('imageInput').click()">
                                    <img id="imagePreview" src="{{ old('image') ? asset('storage/'.old('image')) : '' }}" class="d-none rounded"
                                         style="max-height:196px; max-width:100%; object-fit:cover;">
                                    <div id="imagePlaceholder" class="text-center text-muted">
                                        <i class="mdi mdi-cloud-upload" style="font-size:3rem;"></i>
                                        <p class="mt-2 mb-0">Click to upload image</p>
                                        <small>JPG, PNG, WEBP — max 2MB</small>
                                    </div>
                                </div>
                                <input type="file" name="image" id="imageInput" accept="image/*"
                                       class="form-control @error('image') is-invalid @enderror"
                                       onchange="previewImage(event)" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- URL --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">Instagram URL</label>
                                <input type="url" name="url"
                                       value="{{ old('url', 'https://www.instagram.com/') }}"
                                       class="form-control @error('url') is-invalid @enderror"
                                       placeholder="https://www.instagram.com/...">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Right --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-cog me-2"></i>Settings
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">Sort Order</label>
                                <input type="number" name="sort_order"
                                       value="{{ old('sort_order', 0) }}"
                                       class="form-control @error('sort_order') is-invalid @enderror" min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                @error('is_active')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save
                            </button>
                            <a href="{{ route('admin.instagram.index') }}"
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
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('d-none');
            document.getElementById('imagePlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush