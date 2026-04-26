@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0 fw-bold">
            <i class="mdi mdi-ticket-percent me-2" style="color:#667eea;"></i> Coupons
        </h4>
        <a href="{{ route('admin.coupons.create') }}"
           class="btn text-white" style="background:linear-gradient(135deg,#667eea,#764ba2);">
            <i class="mdi mdi-plus me-1"></i> Add Coupon
        </a>
    </div>

    {{-- Success Message --}}
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
                            <th class="px-4 py-3">Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Min. Order</th>
                            <th>Usage</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td class="px-4 fw-bold font-monospace text-primary">{{ $coupon->code }}</td>
                            <td>
                                <span class="badge {{ $coupon->type === 'percent' ? 'bg-info' : 'bg-primary' }}">
                                    {{ $coupon->type === 'percent' ? 'Percentage %' : 'Fixed Amount' }}
                                </span>
                            </td>
                            <td class="fw-semibold">
                                {{ $coupon->type === 'percent' ? $coupon->value . '%' : 'EGP' . number_format($coupon->value, 2) }}
                            </td>
                            <td>EGP{{ number_format($coupon->min_order, 2) }}</td>
                            <td>
                                {{ $coupon->used_count }}
                                @if($coupon->max_uses) / {{ $coupon->max_uses }} @endif
                            </td>
                            <td class="text-muted small">
                                {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}
                            </td>
                            <td>
                                @if($coupon->isValid())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Expired/Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Edit Button --}}
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                       class="btn btn-sm text-white"
                                       style="background:linear-gradient(135deg,#f7971e,#ffd200); border:none;"
                                       title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                          onsubmit="return confirm('Delete this coupon?')">
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
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="mdi mdi-ticket-outline" style="font-size:40px;"></i>
                                <p class="mt-2">No coupons found</p>
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
        {{ $coupons->links() }}
    </div>
</div>
@endsection
