@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Testimonials</h4>
            <p class="text-muted mb-0">Manage customer reviews and feedback</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add Testimonial
        </a>
    </div>

    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- Stats --}}
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between py-4">
                        <div>
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Total Reviews</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $testimonials->total() }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-comment-quote text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            {{-- يمكنك إضافة إحصائيات أخرى هنا إذا أردت تمريرها من الكنترولر مثل $active_count --}}
        </div>
    </div>

    {{-- Table --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-white border-0">Order</th>
                                <th class="text-white border-0">User</th>
                                <th class="text-white border-0">Content (AR)</th>
                                <th class="text-white border-0">Content (EN)</th>
                                <th class="text-white border-0">Rating</th>
                                <th class="text-white border-0">Status</th>
                                <th class="text-white border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $item)
                            <tr>
                                <td><span class="badge bg-light text-dark border">#{{ $item->sort_order }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->avatar ? Storage::url($item->avatar) : asset('assets/images/faces/face1.jpg') }}"
                                             style="width:45px; height:45px; object-fit:cover; border-radius:50%;" class="me-2 border">
                                        <span class="fw-bold">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td style="max-width:200px;"><small class="text-muted">{{ Str::limit($item->content_ar, 40) }}</small></td>
                                <td style="max-width:200px;"><small class="text-muted">{{ Str::limit($item->content_en, 40) }}</small></td>
                                <td>
                                    @for($i=1; $i<=5; $i++)
                                        <i class="mdi mdi-star {{ $i <= $item->rating ? 'text-warning' : 'text-light' }}"></i>
                                    @endfor
                                </td>
                                <td>
                                    @if($item->is_active)
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e, #38ef7d); color:white; padding:6px 14px;">
                                        Active
                                    </span>
                                    @else
                                    <span class="badge" style="background: linear-gradient(135deg, #f093fb, #f5576c); color:white; padding:6px 14px;">
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.testimonials.edit', $item) }}"
                                           class="btn btn-sm text-white border-0"
                                           style="background: linear-gradient(135deg, #f7971e, #ffd200);">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.testimonials.destroy', $item) }}"
                                              method="POST" id="del-test-{{ $item->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $item->id }}, 'test')"
                                                    class="btn btn-sm text-white border-0"
                                                    style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="mdi mdi-comment-off text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3 mb-2">No testimonials found</p>
                                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-sm btn-primary">
                                        Add your first testimonial
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="modal-title text-white"><i class="mdi mdi-alert me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <h5>Are you sure?</h5>
                <small class="text-danger">This action will remove this testimonial permanently.</small>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-3 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn px-4 text-white" id="confirmDeleteBtn"
                        style="background: linear-gradient(135deg, #f093fb, #f5576c); border:none;">
                    Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let deleteId = null, deletePrefix = null;
    function confirmDelete(id, prefix) {
        deleteId = id; deletePrefix = prefix;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteId) document.getElementById('del-' + deletePrefix + '-' + deleteId).submit();
    });
</script>
@endpush
