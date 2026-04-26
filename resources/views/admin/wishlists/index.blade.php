@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="mdi mdi-heart-multiple-outline me-2" style="color:#667eea;"></i>
                User Wishlist
            </h4>
            <p class="text-muted mb-0 mt-1">Manage users' favorite products</p>
        </div>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Wishlist Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:linear-gradient(135deg,#667eea,#764ba2); color:white;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">User</th>
                            <th class="py-3">Product</th>
                            <th class="py-3">Added At</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wishlists as $item)
                        <tr>
                            <td class="px-4">{{ $item->id }}</td>
                            <td>{{ $item->user->name ?? 'N/A' }}</td>
                            <td>
                                @if($item->product)
                                    <a href="{{ route('shop.show', $item->product->id) }}" target="_blank" class="text-decoration-none fw-semibold text-dark">
                                        {{ Str::limit($item->product->name, 30) }}
                                    </a>
                                @else
                                    <span class="text-muted">Product deleted</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $item->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.wishlists.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Delete this wishlist item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm"
                                            style="background:linear-gradient(135deg,#f093fb,#f5576c); color:white; border:none;">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="mdi mdi-heart-off-outline" style="font-size:40px;"></i>
                                <p class="mt-2">No wishlist items yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $wishlists->links() }}
    </div>
</div>
@endsection
