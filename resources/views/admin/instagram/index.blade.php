@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    {{-- Header --}}
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Instagram Images</h4>
            <p class="text-muted mb-0">Manage your instagram section</p>
        </div>
        <a href="{{ route('admin.instagram.create') }}" class="btn btn-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i>
            Add New Image
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div class="col-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>
            {{ session('success') }}
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
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Total Images</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $total }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-instagram text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between py-4">
                        <div>
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Active</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $active }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-check-circle text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between py-4">
                        <div>
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Inactive</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $inactive }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-eye-off text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
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
                                <th class="text-white border-0">#</th>
                                <th class="text-white border-0">Image</th>
                                <th class="text-white border-0">URL</th>
                                <th class="text-white border-0">Order</th>
                                <th class="text-white border-0">Status</th>
                                <th class="text-white border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($images as $item)
                            <tr>
                                <td><span class="font-weight-bold text-muted">#{{ $item->id }}</span></td>
                                <td>
                                    <img src="{{ Storage::url($item->image) }}"
                                         style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                </td>
                                <td>
                                    <a href="{{ $item->url }}" target="_blank" class="text-muted small">
                                        {{ Str::limit($item->url, 30) }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge"
                                          style="background: linear-gradient(135deg, #667eea, #764ba2); color:white; padding:6px 12px;">
                                        {{ $item->sort_order }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge"
                                              style="background: linear-gradient(135deg, #11998e, #38ef7d); color:white; padding:6px 14px;">
                                            <i class="mdi mdi-check me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge"
                                              style="background: linear-gradient(135deg, #f093fb, #f5576c); color:white; padding:6px 14px;">
                                            <i class="mdi mdi-close me-1"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.instagram.edit', $item) }}"
                                           class="btn btn-sm btn-icon-text"
                                           style="background: linear-gradient(135deg, #f7971e, #ffd200); color:white; border:none;">
                                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.instagram.destroy', $item) }}"
                                              method="POST" id="delete-form-{{ $item->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete({{ $item->id }})"
                                                    class="btn btn-sm btn-icon-text"
                                                    style="background: linear-gradient(135deg, #f093fb, #f5576c); color:white; border:none;">
                                                <i class="mdi mdi-delete btn-icon-prepend"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="mdi mdi-instagram text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3 mb-2">No images found</p>
                                    <a href="{{ route('admin.instagram.create') }}" class="btn btn-sm btn-primary mt-1">
                                        Add your first image
                                    </a>
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

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0"
                 style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="modal-title text-white">
                    <i class="mdi mdi-alert me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="background: linear-gradient(135deg, #f093fb, #f5576c); width:80px; height:80px;">
                    <i class="mdi mdi-delete text-white" style="font-size:2.5rem;"></i>
                </div>
                <h5 class="font-weight-bold">Are you sure?</h5>
                <p class="text-muted mb-0">You are about to delete this image.</p>
                <small class="text-danger">This action cannot be undone.</small>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-3 pb-4">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i> Cancel
                </button>
                <button type="button" class="btn px-4 text-white" id="confirmDeleteBtn"
                        style="background: linear-gradient(135deg, #f093fb, #f5576c); border:none;">
                    <i class="mdi mdi-delete me-1"></i> Yes, Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let deleteId = null;
    function confirmDelete(id) {
        deleteId = id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (deleteId) document.getElementById('delete-form-' + deleteId).submit();
    });
</script>
@endpush