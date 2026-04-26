@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Categories</h4>
            <p class="text-muted mb-0">Manage product categories & subcategories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-icon-text">
            <i class="mdi mdi-plus btn-icon-prepend"></i> Add Category
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
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Total</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $total }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-shape text-white" style="font-size:2rem;"></i>
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
                                <th class="text-black border-0">#</th>
                                <th class="text-black border-0">Image</th>
                                <th class="text-black border-0">Name (AR)</th>
                                <th class="text-black border-0">Name (EN)</th>
                                <th class="text-black border-0">Subcategories</th>
                                <th class="text-black border-0">Products</th>
                                <th class="text-black border-0">Status</th>
                                <th class="text-black border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            {{-- Parent Category Row --}}
                            <tr style="background-color: #f8f9ff;">
                                <td><span class="text-muted">#{{ $category->id }}</span></td>
                                <td>
                                    @if($category->image)
                                    <img src="{{ Storage::url($category->image) }}"
                                         style="width:60px; height:60px; object-fit:cover; border-radius:50%;">
                                    @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width:60px; height:60px;">
                                        <i class="mdi mdi-image text-muted" style="font-size:1.5rem;"></i>
                                    </div>
                                    @endif
                                </td>
                                <td><strong>{{ $category->name_ar }}</strong></td>
                                <td><strong>{{ $category->name_en }}</strong></td>
                                <td>
                                    <span class="badge bg-primary">{{ $category->children->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $category->products_count }}</span>
                                </td>
                                <td>
                                    @if($category->is_active)
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
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-sm text-white border-0"
                                           style="background: linear-gradient(135deg, #f7971e, #ffd200);">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST" id="del-cat-{{ $category->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $category->id }}, 'cat')"
                                                    class="btn btn-sm text-white border-0"
                                                    style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Subcategory Rows --}}
                            @foreach($category->children as $child)
                            <tr>
                                <td><span class="text-muted">#{{ $child->id }}</span></td>
                                <td>
                                    @if($child->image)
                                    <img src="{{ Storage::url($child->image) }}"
                                         style="width:45px; height:45px; object-fit:cover; border-radius:50%;">
                                    @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width:45px; height:45px;">
                                        <i class="mdi mdi-image text-muted" style="font-size:1.2rem;"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted me-1"><i class="mdi mdi-subdirectory-arrow-right"></i></span>
                                    {{ $child->name_ar }}
                                </td>
                                <td>
                                    <span class="text-muted me-1"><i class="mdi mdi-subdirectory-arrow-right"></i></span>
                                    {{ $child->name_en }}
                                </td>
                                <td>—</td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $child->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($child->is_active)
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
                                        <a href="{{ route('admin.categories.edit', $child) }}"
                                           class="btn btn-sm text-white border-0"
                                           style="background: linear-gradient(135deg, #f7971e, #ffd200);">
                                            <i class="mdi mdi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $child) }}"
                                              method="POST" id="del-cat-{{ $child->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $child->id }}, 'cat')"
                                                    class="btn btn-sm text-white border-0"
                                                    style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="mdi mdi-shape-outline text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3 mb-2">No categories found</p>
                                    <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary">
                                        Add your first category
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
    {{ $categories->links('pagination::bootstrap-5') }}
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
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <h5 class="modal-title text-white"><i class="mdi mdi-alert me-2"></i>Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <h5>Are you sure?</h5>
                <small class="text-danger">This action cannot be undone. Subcategories will be unlinked.</small>
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
