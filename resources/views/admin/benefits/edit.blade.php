@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Edit Benefit</h4>
            <p class="text-muted mb-0">Update benefit details</p>
        </div>
        <a href="{{ route('admin.benefits.index') }}" class="btn btn-secondary btn-icon-text">
            <i class="mdi mdi-arrow-left btn-icon-prepend"></i>
            Back to Benefits
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
        <form action="{{ route('admin.benefits.update', $benefit) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Left Col --}}
                <div class="col-lg-8">

                    {{-- Icon Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-emoticon me-2"></i>
                                Icon
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <label class="form-label font-weight-medium">
                                <i class="mdi mdi-emoticon me-1 text-muted"></i>Icon Class (Font Awesome) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                                    <i id="iconPreview" class="{{ old('icon', $benefit->icon) }} text-white"></i>
                                </span>
                                <input type="text"
                                       name="icon"
                                       id="iconInput"
                                       value="{{ old('icon', $benefit->icon) }}"
                                       class="form-control @error('icon') is-invalid @enderror"
                                       placeholder="fa-solid fa-phone"
                                       oninput="previewIcon(this.value)"
                                       required>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted mt-1 d-block">
                                <i class="mdi mdi-information-outline me-1"></i>
                                Example: <code>fa-solid fa-phone</code> — <code>fa-solid fa-truck</code> — <code>fa-solid fa-certificate</code>
                            </small>

                            {{-- Icon Preview --}}
                            <div class="mt-3 p-3 rounded"
                                 style="background: #f8f9fa; border: 1px dashed #dee2e6;">
                                <small class="text-muted d-block mb-2">Preview:</small>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="background: linear-gradient(135deg, #667eea, #764ba2); width: 55px; height: 55px;">
                                        <i id="iconPreviewLarge" class="{{ old('icon', $benefit->icon) }} text-white"
                                           style="font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 font-weight-medium">Icon Preview</p>
                                        <small class="text-muted" id="iconClassText">{{ old('icon', $benefit->icon) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Titles Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-translate me-2"></i>
                                Titles & Descriptions
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
                                           value="{{ old('title_ar', $benefit->title_ar) }}"
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
                                           value="{{ old('title_en', $benefit->title_en) }}"
                                           class="form-control @error('title_en') is-invalid @enderror"
                                           placeholder="Enter title in English"
                                           required>
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-text me-1 text-muted"></i>Description (Arabic) <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description_ar"
                                              rows="4"
                                              class="form-control @error('description_ar') is-invalid @enderror"
                                              placeholder="أدخل الوصف بالعربي"
                                              required>{{ old('description_ar', $benefit->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium">
                                        <i class="mdi mdi-text me-1 text-muted"></i>Description (English) <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description_en"
                                              rows="4"
                                              class="form-control @error('description_en') is-invalid @enderror"
                                              placeholder="Enter description in English"
                                              required>{{ old('description_en', $benefit->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Col --}}
                <div class="col-lg-4">

                    {{-- Settings Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
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
                                       value="{{ old('sort_order', $benefit->sort_order) }}"
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
                                           {{ old('is_active', $benefit->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2 font-weight-medium" for="is_active">
                                        Active
                                    </label>
                                </div>
                                <small class="text-muted">Show this benefit on the website</small>
                            </div>

                        </div>
                    </div>

                    {{-- Info Card --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-information me-2"></i>
                                Info
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">ID</small>
                                <small class="font-weight-medium">#{{ $benefit->id }}</small>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">Created</small>
                                <small class="font-weight-medium">{{ $benefit->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <small class="text-muted">Last Updated</small>
                                <small class="font-weight-medium">{{ $benefit->updated_at->format('d M Y') }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i>
                                Update Benefit
                            </button>
                            <a href="{{ route('admin.benefits.index') }}"
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
    function previewIcon(value) {
        document.getElementById('iconPreview').className = value + ' text-white';
        document.getElementById('iconPreviewLarge').className = value + ' text-white';
        document.getElementById('iconClassText').textContent = value;
        document.getElementById('iconPreviewLarge').style.fontSize = '1.5rem';
    }
</script>
@endpush