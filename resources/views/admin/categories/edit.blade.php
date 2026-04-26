@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold mb-0">Edit Category</h4>
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
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0"
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                    <h6 class="text-white mb-0 py-1"><i class="mdi mdi-shape me-2"></i>Category Details</h6>
                </div>
                <div class="card-body pt-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Name (Arabic) <span class="text-danger">*</span></label>
                            <input type="text" name="name_ar" value="{{ old('name_ar', $category->name_ar) }}"
                                   class="form-control @error('name_ar') is-invalid @enderror" required>
                            @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-translate me-1 text-muted"></i>Name (English) <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" value="{{ old('name_en', $category->name_en) }}"
                                   class="form-control @error('name_en') is-invalid @enderror" required>
                            @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-file-tree me-1 text-muted"></i>Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- None (Main Category) --</option>
                                @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name_en }} / {{ $parent->name_ar }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                                   class="form-control" min="0">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end pb-1">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                       {{ $category->is_active ? 'checked' : '' }}>
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
                    <div class="d-flex justify-content-center mb-3">
                        @if($category->image)
                        <img src="{{ Storage::url($category->image) }}"
                             id="imagePreview"
                             style="width:180px; height:180px; object-fit:cover; border-radius:50%;">
                        @else
                        <div id="imagePreview"
                             style="width:180px; height:180px; border-radius:50%; background:#f8f9fa; border:2px dashed #dee2e6; display:flex; align-items:center; justify-content:center;">
                            <i class="mdi mdi-camera text-muted" style="font-size:3rem;"></i>
                        </div>
                        @endif
                    </div>
                    <input type="file" name="image" accept="image/*"
                           class="form-control @error('image') is-invalid @enderror"
                           onchange="previewImage(event)">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Leave empty to keep current image</small>
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit"
                        class="btn btn-lg text-white border-0 px-5"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="mdi mdi-content-save me-2"></i> Save Changes
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
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
            preview.style.cssText = 'width:180px; height:180px; object-fit:cover; border-radius:50%;';
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush
