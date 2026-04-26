@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Stores</h4>
            <p class="text-muted mb-0">Manage your stores</p>
        </div>
        <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i>
            Add New Store
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
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Total Stores</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $total ?? $stores->total() }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-store text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between py-4">
                        <div>
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">With Images</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $stores->sum('images_count') }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-image text-white" style="font-size:2rem;"></i>
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
                                <th class="text-black border-0">#</th>
                                <th class="text-black border-0">Image</th>
                                <th class="text-black border-0">Name (AR / EN)</th>
                                <th class="text-black border-0">Description</th>
                                <th class="text-black border-0">Images</th>
                                <th class="text-black border-0">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($stores as $store)
                            <tr>
                                <td class="text-muted">#{{ $store->id }}</td>

                                <td>
                                    @if($store->cover_image)
                                    <img src="{{ Storage::url($store->cover_image) }}"
                                         style="width:70px; height:50px; object-fit:cover; border-radius:8px;">
                                    @else
                                    <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                {{-- Name --}}
                                <td>
                                    <div>
                                        <strong>{{ $store->name_ar }}</strong>
                                    </div>
                                    <div class="text-muted" style="font-size: 12px;">
                                        {{ $store->name_en }}
                                    </div>
                                </td>

                                {{-- Description --}}
                                <td>
                                    <div>
                                        {{ Str::limit($store->description_ar, 30) }}
                                    </div>
                                    <div class="text-muted" style="font-size: 12px;">
                                        {{ Str::limit($store->description_en, 30) }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $store->images_count }}
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="{{ route('admin.stores.show', $store) }}"
                                           class="btn btn-sm btn-info text-white">
                                            View
                                        </a>

                                        <a href="{{ route('admin.stores.edit', $store) }}"
                                           class="btn btn-sm btn-warning text-white">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.stores.destroy', $store) }}"
                                              method="POST"
                                              id="delete-form-{{ $store->id }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    onclick="confirmDelete({{ $store->id }})"
                                                    class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="mdi mdi-store-outline text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3">No stores found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $stores->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center py-4">
                <h5>Are you sure?</h5>
                <small class="text-danger">This action cannot be undone.</small>
            </div>

            <div class="modal-footer justify-content-center gap-3 pb-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
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
        if (deleteId) {
            document.getElementById('delete-form-' + deleteId).submit();
        }
    });
</script>
@endpush
