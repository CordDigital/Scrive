@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold mb-0">Add Category</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
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

    <div class="col-lg-8">
        <form id="catForm" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0"
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                    <h6 class="text-white mb-0 py-1"><i class="mdi mdi-shape me-2"></i>Category Details</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Name (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                                   class="form-control @error('name_ar') is-invalid @enderror" required>
                            @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" value="{{ old('name_en') }}"
                                   class="form-control @error('name_en') is-invalid @enderror" required>
                            @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-file-tree me-1 text-muted"></i>Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- None (Main Category) --</option>
                                @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name_en }} / {{ $parent->name_ar }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                   class="form-control" min="0">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label font-weight-medium" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Image --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0"
                     style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                    <h6 class="text-white mb-0 py-1"><i class="mdi mdi-image me-2"></i>Category Image</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="d-flex align-items-center justify-content-center mb-3"
                         style="height:180px; background:#f8f9fa; border: 2px dashed #dee2e6; border-radius:50%; width:180px; margin:auto; cursor:pointer; overflow:hidden;"
                         onclick="document.getElementById('imageInput').click()">
                        <img id="imagePreview" src="" class="d-none"
                             style="width:180px; height:180px; object-fit:cover; border-radius:50%;">
                        <div id="imagePlaceholder" class="text-center text-muted">
                            <i class="mdi mdi-camera" style="font-size:3rem;"></i>
                        </div>
                    </div>
                    <input type="file" name="image" id="imageInput" accept="image/*"
                           class="form-control mt-3 @error('image') is-invalid @enderror"
                           onchange="previewImage(event)">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Circular image recommended — JPG, PNG, WEBP</small>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit"
                        class="btn btn-lg text-white border-0 px-5"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="mdi mdi-content-save me-2"></i> Save
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-lg btn-outline-secondary px-5">
                    <i class="mdi mdi-close me-2"></i> Cancel
                </a>
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
