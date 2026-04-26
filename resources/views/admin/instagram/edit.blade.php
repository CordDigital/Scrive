@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Edit Instagram Image</h4>
            <p class="text-muted mb-0">Update image details</p>
        </div>
        <a href="{{ route('admin.instagram.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="col-12">
        <form action="{{ route('admin.instagram.update', $instagram) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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

                            {{-- Current Image --}}
                            <div class="mb-4">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-image me-1 text-muted"></i>Image</label>
                                <div class="position-relative mb-3">
                                    <img src="{{ Storage::url($instagram->image) }}"
                                         id="imagePreview"
                                         class="rounded shadow-sm d-block"
                                         style="width:100%; height:200px; object-fit:cover;">
                                    <span class="badge position-absolute top-0 start-0 m-2"
                                          style="background: linear-gradient(135deg, #11998e, #38ef7d);">
                                        Current
                                    </span>
                                </div>
                                <input type="file" name="image" accept="image/*"
                                       class="form-control @error('image') is-invalid @enderror"
                                       onchange="previewImage(event)">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>

                            {{-- URL --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-link me-1 text-muted"></i>Instagram URL</label>
                                <input type="url" name="url"
                                       value="{{ old('url', $instagram->url) }}"
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

                    {{-- Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-information me-2"></i>Info
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">ID</small>
                                <small class="font-weight-medium">#{{ $instagram->id }}</small>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">Created</small>
                                <small class="font-weight-medium">{{ $instagram->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <small class="text-muted">Updated</small>
                                <small class="font-weight-medium">{{ $instagram->updated_at->format('d M Y') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-cog me-2"></i>Settings
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                                <input type="number" name="sort_order"
                                       value="{{ old('sort_order', $instagram->sort_order) }}"
                                       class="form-control" min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium d-block"><i class="mdi mdi-toggle-switch me-1 text-muted"></i>Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_active" id="is_active"
                                           {{ $instagram->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save Changes
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
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush