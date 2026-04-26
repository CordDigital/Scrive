@extends('admin.layouts.app')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-size: 15px; }
    .ql-editor { min-height: 300px; line-height: 1.7; }
    .ql-toolbar { border-radius: 4px 4px 0 0; }
</style>
@endpush

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Return Policy</h4>
            <p class="text-muted mb-0">Manage your return policy content</p>
        </div>
        <a href="{{ route('return-policy') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="mdi mdi-eye me-1"></i> View Page
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- Alert Errors --}}
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
        <form action="{{ route('admin.return-policy.update') }}" method="POST" id="return-policy-form">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Left Col --}}
                <div class="col-lg-8">

                    {{-- Arabic Content --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-text me-2"></i>
                                Content (Arabic)
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div id="quill_ar" style="border: 1px solid #dee2e6; border-radius: 4px;"></div>
                            <input type="hidden" name="content_ar" id="content_ar_input"
                                   value="{{ old('content_ar', $page->content_ar) }}">
                            @error('content_ar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- English Content --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-text me-2"></i>
                                Content (English)
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div id="quill_en" style="border: 1px solid #dee2e6; border-radius: 4px;"></div>
                            <input type="hidden" name="content_en" id="content_en_input"
                                   value="{{ old('content_en', $page->content_en) }}">
                            @error('content_en')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- Right Col --}}
                <div class="col-lg-4">

                    {{-- Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0 pb-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-3">
                                <i class="mdi mdi-refresh me-2"></i>
                                Info
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">Page</small>
                                <small class="font-weight-medium">Return Policy</small>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <small class="text-muted">Last Updated</small>
                                <small class="font-weight-medium">
                                    {{ $page->updated_at ? $page->updated_at->format('d M Y') : 'Never' }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
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

    // Arabic Editor
    const quillAr = new Quill('#quill_ar', {
        theme: 'snow',
        modules: { toolbar: toolbarOptions }
    });
    quillAr.root.setAttribute('dir', 'rtl');
    const contentAr = document.getElementById('content_ar_input').value;
    if (contentAr) quillAr.root.innerHTML = contentAr;

    // English Editor
    const quillEn = new Quill('#quill_en', {
        theme: 'snow',
        modules: { toolbar: toolbarOptions }
    });
    const contentEn = document.getElementById('content_en_input').value;
    if (contentEn) quillEn.root.innerHTML = contentEn;

    // On submit: sync Quill content to hidden inputs
    document.getElementById('return-policy-form').addEventListener('submit', function () {
        document.getElementById('content_ar_input').value = quillAr.root.innerHTML;
        document.getElementById('content_en_input').value = quillEn.root.innerHTML;
    });
</script>
@endpush