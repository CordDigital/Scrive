@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="mdi mdi-frequently-asked-questions me-2" style="color:#667eea;"></i>
                Frequently Asked Questions
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your FAQ entries</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}"
           class="btn text-white"
           style="background:linear-gradient(135deg,#667eea,#764ba2);">
            <i class="mdi mdi-plus me-1"></i> Add Question
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:linear-gradient(135deg,#667eea,#764ba2); color:white;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Category</th>
                            <th class="py-3">Question (AR)</th>
                            <th class="py-3">Question (EN)</th>
                            <th class="py-3">Order</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr>
                            <td class="px-4">{{ $faq->id }}</td>
                            <td>
                                <span class="badge px-3 py-1 text-white"
                                      style="background:linear-gradient(135deg,#667eea,#764ba2);">
                                    {{ $faq->category_en }}
                                </span>
                            </td>
                            <td style="max-width:200px;">{{ Str::limit($faq->question_ar, 50) }}</td>
                            <td style="max-width:200px;">{{ Str::limit($faq->question_en, 50) }}</td>
                            <td>{{ $faq->sort_order }}</td>
                            <td>
                                @if($faq->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Hidden</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                       class="btn btn-sm text-white"
                                       style="background:linear-gradient(135deg,#f7971e,#ffd200); border:none;"
                                       title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                                          onsubmit="return confirm('Delete this question?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-white"
                                                style="background:linear-gradient(135deg,#f093fb,#f5576c); border:none;"
                                                title="Delete">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="mdi mdi-help-circle-outline" style="font-size:40px;"></i>
                                <p class="mt-2">No questions found yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">{{ $faqs->links() }}</div>

</div>
@endsection
