@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="mdi mdi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Add New FAQ</h4>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-folder me-1 text-muted"></i>Category (Arabic) <span class="text-danger">*</span></label>
                        <input type="text" name="category_ar"
                               class="form-control @error('category_ar') is-invalid @enderror"
                               value="{{ old('category_ar') }}"
                               placeholder="e.g. Purchase Method">
                        @error('category_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-folder me-1 text-muted"></i>Category (English) <span class="text-danger">*</span></label>
                        <input type="text" name="category_en"
                               class="form-control @error('category_en') is-invalid @enderror"
                               value="{{ old('category_en') }}"
                               placeholder="e.g. How to buy">
                        @error('category_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Question --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-help-circle me-1 text-muted"></i>Question (Arabic) <span class="text-danger">*</span></label>
                        <input type="text" name="question_ar"
                               class="form-control @error('question_ar') is-invalid @enderror"
                               value="{{ old('question_ar') }}">
                        @error('question_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-help-circle me-1 text-muted"></i>Question (English) <span class="text-danger">*</span></label>
                        <input type="text" name="question_en"
                               class="form-control @error('question_en') is-invalid @enderror"
                               value="{{ old('question_en') }}">
                        @error('question_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Answer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-comment-text me-1 text-muted"></i>Answer (Arabic) <span class="text-danger">*</span></label>
                        <textarea name="answer_ar" rows="4"
                                  class="form-control @error('answer_ar') is-invalid @enderror">{{ old('answer_ar') }}</textarea>
                        @error('answer_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-comment-text me-1 text-muted"></i>Answer (English) <span class="text-danger">*</span></label>
                        <textarea name="answer_en" rows="4"
                                  class="form-control @error('answer_en') is-invalid @enderror">{{ old('answer_en') }}</textarea>
                        @error('answer_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Sort + Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', 0) }}" min="0">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="btn text-white px-5"
                                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="mdi mdi-content-save me-1"></i> Save FAQ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
