@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="mdi mdi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Edit FAQ</h4>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf @method('PUT')

                <div class="row g-3">

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-folder me-1 text-muted"></i>Category (Arabic)</label>
                        <input type="text" name="category_ar" class="form-control"
                               value="{{ old('category_ar', $faq->category_ar) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-folder me-1 text-muted"></i>Category (English)</label>
                        <input type="text" name="category_en" class="form-control"
                               value="{{ old('category_en', $faq->category_en) }}">
                    </div>

                    {{-- Question --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-help-circle me-1 text-muted"></i>Question (Arabic)</label>
                        <input type="text" name="question_ar" class="form-control"
                               value="{{ old('question_ar', $faq->question_ar) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-help-circle me-1 text-muted"></i>Question (English)</label>
                        <input type="text" name="question_en" class="form-control"
                               value="{{ old('question_en', $faq->question_en) }}">
                    </div>

                    {{-- Answer --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-comment-text me-1 text-muted"></i>Answer (Arabic)</label>
                        <textarea name="answer_ar" rows="4" class="form-control">{{ old('answer_ar', $faq->answer_ar) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-comment-text me-1 text-muted"></i>Answer (English)</label>
                        <textarea name="answer_en" rows="4" class="form-control">{{ old('answer_en', $faq->answer_en) }}</textarea>
                    </div>

                    {{-- Sort & Status --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-sort-numeric-ascending me-1 text-muted"></i>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control"
                               value="{{ old('sort_order', $faq->sort_order) }}" min="0">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn text-white px-5 shadow-sm border-0"
                                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="mdi mdi-content-save me-1"></i> Update FAQ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
