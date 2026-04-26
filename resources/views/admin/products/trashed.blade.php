@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width:48px;height:48px;background:linear-gradient(135deg,#f093fb,#f5576c);">
                <i class="mdi mdi-delete-sweep text-white" style="font-size:1.4rem;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Trashed Products</h4>
                <small class="text-muted">{{ $products->count() }} products in trash</small>
            </div>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back to Products
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

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <tr>
                                <th class="text-white border-0">#</th>
                                <th class="text-white border-0">Image</th>
                                <th class="text-white border-0">Name (EN)</th>
                                <th class="text-white border-0">Name (AR)</th>
                                <th class="text-white border-0">Category</th>
                                <th class="text-white border-0">Deleted At</th>
                                <th class="text-white border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td><span class="text-muted">#{{ $product->id }}</span></td>
                                <td>
                                    <img src="{{ Storage::url($product->image) }}"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:8px;opacity:0.6;">
                                </td>
                                <td>{{ Str::limit($product->name_en, 30) }}</td>
                                <td>{{ Str::limit($product->name_ar, 30) }}</td>
                                <td><span class="badge bg-light text-dark">{{ $product->category?->name_en }}</span></td>
                                <td><small class="text-muted">{{ $product->deleted_at->diffForHumans() }}</small></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.products.restore', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm text-white border-0"
                                                    style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                                                <i class="mdi mdi-restore"></i> Restore
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm text-white border-0"
                                                style="background:linear-gradient(135deg,#f093fb,#f5576c);"
                                                onclick="openForceDeleteModal({{ $product->id }}, '{{ addslashes($product->name_en) }}')">
                                            <i class="mdi mdi-delete-forever"></i> Delete Forever
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="mdi mdi-delete-empty text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3 mb-0">Trash is empty</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Force Delete Modal --}}
<div class="modal fade" id="forceDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width:60px;height:60px;background:linear-gradient(135deg,#f5576c,#ff6b6b);">
                        <i class="mdi mdi-delete-forever text-white" style="font-size:1.8rem;"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-2">Permanent Delete?</h5>
                <p class="text-muted mb-1">Delete <strong id="forceDeleteName"></strong>?</p>
                <p class="text-danger small mb-4">This will permanently remove the product and all its images. This cannot be undone.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <form id="forceDeleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn text-white px-4" style="background:linear-gradient(135deg,#f5576c,#ff6b6b);">
                            <i class="mdi mdi-delete-forever me-1"></i> Delete Forever
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openForceDeleteModal(id, name) {
        document.getElementById('forceDeleteForm').action = '/admin/products/' + id + '/force-delete';
        document.getElementById('forceDeleteName').textContent = name;
        new bootstrap.Modal(document.getElementById('forceDeleteModal')).show();
    }
</script>
@endpush
