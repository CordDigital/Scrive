@extends('admin.layouts.app')

@push('styles')
{{-- Quill Editor CSS --}}
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-size: 15px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; }
    .ql-editor { min-height: 150px; line-height: 1.6; }
    .ql-toolbar { border-radius: 4px 4px 0 0; border-color: #dee2e6 !important; }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold mb-0">Add New Testimonial</h4>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary">
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

    <div class="col-12">
        <form id="testimonialForm" action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                {{-- Left Column --}}
                <div class="col-lg-8">

                    {{-- User Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-account me-2"></i>Customer Details</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-account me-1 text-muted"></i>Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="e.g. John Doe" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-star me-1 text-muted"></i>Rating <span class="text-danger">*</span></label>
                                    <select name="rating" class="form-select" required>
                                        <option value="5">5 Stars ⭐⭐⭐⭐⭐</option>
                                        <option value="4">4 Stars ⭐⭐⭐⭐</option>
                                        <option value="3">3 Stars ⭐⭐⭐</option>
                                        <option value="2">2 Stars ⭐⭐</option>
                                        <option value="1">1 Star ⭐</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-briefcase me-1 text-muted"></i>Job Title (Arabic)</label>
                                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="form-control" placeholder="مثلاً: مدير تسويق">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-briefcase me-1 text-muted"></i>Job Title (English)</label>
                                    <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control" placeholder="e.g. Marketing Manager">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Feedback Content with Quill --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-comment-text me-2"></i>Feedback Content</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-4">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-comment-text me-1 text-muted"></i>Feedback (Arabic) <span class="text-danger">*</span></label>
                                <div id="quill_ar" style="height: 150px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="content_ar" id="content_ar_input" value="{{ old('content_ar') }}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label font-weight-medium"><i class="mdi mdi-comment-text me-1 text-muted"></i>Feedback (English) <span class="text-danger">*</span></label>
                                <div id="quill_en" style="height: 150px; border: 1px solid #dee2e6;"></div>
                                <input type="hidden" name="content_en" id="content_en_input" value="{{ old('content_en') }}">
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column --}}
                <div class="col-lg-4">

                    {{-- Avatar Upload --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 bg-dark">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-image me-2"></i>Customer Avatar</h6>
                        </div>
                        <div class="card-body text-center py-4">
                            <div class="border rounded d-flex align-items-center justify-content-center mb-3 overflow-hidden mx-auto"
                                 style="width:150px; height:150px; background:#f8f9fa; border:2px dashed #dee2e6 !important; cursor:pointer; border-radius: 50% !important;"
                                 onclick="document.getElementById('avatarInput').click()">
                                <img id="avatarPreview" src="" class="d-none w-100 h-100" style="object-fit:cover;">
                                <div id="avatarPlaceholder" class="text-center text-muted">
                                    <i class="mdi mdi-camera-plus" style="font-size:2.5rem;"></i>
                                    <p class="small mb-0">Upload Photo</p>
                                </div>
                            </div>
                            <input type="file" name="avatar" id="avatarInput" accept="image/*" class="form-control d-none" onchange="previewAvatar(event)">
                            <small class="text-muted">Recommended: Square image (1:1)</small>
                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 bg-secondary">
                            <h6 class="text-white mb-0 py-1"><i class="mdi mdi-cog me-2"></i>Display Settings</h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0">
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Publish on Website</label>
                            </div>

                            <button type="button" id="submitBtn" class="btn btn-primary btn-lg w-100 shadow-sm border-0"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save Testimonial
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- Quill Editor JS --}}
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    // --- Quill Editor Setup ---
    const toolbarOptions = [
        ['bold', 'italic', 'underline'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        ['clean']
    ];

    const quillAr = new Quill('#quill_ar', { theme: 'snow', modules: { toolbar: toolbarOptions } });
    quillAr.root.setAttribute('dir', 'rtl');

    const quillEn = new Quill('#quill_en', { theme: 'snow', modules: { toolbar: toolbarOptions } });

    // Load old values if validation fails
    @if(old('content_ar')) quillAr.root.innerHTML = `{!! old('content_ar') !!}`; @endif
    @if(old('content_en')) quillEn.root.innerHTML = `{!! old('content_en') !!}`; @endif

    // --- Avatar Preview ---
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('avatarPreview').classList.remove('d-none');
            document.getElementById('avatarPlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    // --- Form Submission Logic ---
    document.getElementById('submitBtn').addEventListener('click', () => {
        document.getElementById('content_ar_input').value = quillAr.root.innerHTML;
        document.getElementById('content_en_input').value = quillEn.root.innerHTML;
        document.getElementById('testimonialForm').submit();
    });
</script>
@endpush
