@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Products</h4>
            <p class="text-muted mb-0">Manage your products</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.trashed') }}" class="btn btn-outline-danger btn-icon-text">
                <i class="mdi mdi-delete-sweep btn-icon-prepend"></i> Trash
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-icon-text">
                <i class="mdi mdi-plus btn-icon-prepend"></i> Add Product
            </a>
        </div>
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
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Total Products</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $total }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-package-variant text-white" style="font-size:2rem;"></i>
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
                            <i class="mdi mdi-close-circle text-white" style="font-size:2rem;"></i>
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
                                <th class="text-dark border-0">#</th>
                                <th class="text-dark border-0">Image</th>
                                <th class="text-dark border-0">Name (AR)</th>
                                <th class="text-dark border-0">Name (EN)</th>
                                <th class="text-dark border-0">Category</th>
                                <th class="text-dark border-0">Price</th>
                                <th class="text-dark border-0">Stock</th>
                                <th class="text-dark border-0">Status</th>
                                <th class="text-dark border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td><span class="text-muted">#{{ $product->id }}</span></td>
                                <td>
                                    <img src="{{ Storage::url($product->image) }}"
                                         style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                </td>
                                <td>{{ Str::limit($product->name_ar, 25) }}</td>
                                <td>{{ Str::limit($product->name_en, 25) }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $product->category?->name_en }}
                                    </span>
                                </td>
                              <td>
    @if(!$product->price || $product->price == 0)
        <div>Price on request</div>
    @else
        <div>EGP{{ number_format($product->price, 2) }}</div>

        @if($product->old_price)
            <small class="text-muted">
                <del>EGP{{ number_format($product->old_price, 2) }}</del>
            </small>
        @endif
    @endif
</td>
                                <td>
                                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    @if($product->is_active)
                                    <span class="badge" style="background: linear-gradient(135deg, #11998e, #38ef7d); color:white; padding:6px 14px;">
                                        <i class="mdi mdi-check me-1"></i> Active
                                    </span>
                                    @else
                                    <span class="badge" style="background: linear-gradient(135deg, #f093fb, #f5576c); color:white; padding:6px 14px;">
                                        <i class="mdi mdi-close me-1"></i> Inactive
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="btn btn-sm text-white border-0"
                                           style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="btn btn-sm text-white border-0"
                                           style="background: linear-gradient(135deg, #f7971e, #ffd200);">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST" id="del-prod-{{ $product->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $product->id }}, 'prod')"
                                                    class="btn btn-sm text-white border-0"
                                                    style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="mdi mdi-package-variant-closed text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3 mb-2">No products found</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                                        Add your first product
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
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="modal-title text-white"><i class="mdi mdi-alert me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <h5>Are you sure?</h5>
                <small class="text-danger">This action cannot be undone.</small>
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
