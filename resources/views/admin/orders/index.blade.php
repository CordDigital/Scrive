@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="mdi mdi-shopping me-2" style="color:#667eea;"></i> Orders
            </h4>
            <p class="text-muted mb-0 mt-1">Manage and track customer orders</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white rounded-3"
                 style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <i class="mdi mdi-currency-usd" style="font-size:36px; opacity:.8;"></i>
                    <div>
                        <div class="fs-4 fw-bold">EGP{{ number_format($totalRevenue, 2) }}</div>
                        <div class="small opacity-75">Total Revenue</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white rounded-3"
                 style="background:linear-gradient(135deg,#f7971e,#ffd200);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <i class="mdi mdi-clock-outline" style="font-size:36px; opacity:.8;"></i>
                    <div>
                        <div class="fs-4 fw-bold">{{ $pending }}</div>
                        <div class="small opacity-75">Pending Orders</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white rounded-3"
                 style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <i class="mdi mdi-check-circle" style="font-size:36px; opacity:.8;"></i>
                    <div>
                        <div class="fs-4 fw-bold">{{ $delivered }}</div>
                        <div class="small opacity-75">Delivered Orders</div>
                    </div>
                </div>
            </div>
        </div>
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
                            <th class="px-4 py-3">Order ID</th>
                            <th class="py-3">Customer</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Payment Method</th>
                            <th class="py-3">Payment Status</th>

                            <th class="py-3">Status</th>
                            <th class="py-3">Date</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="px-4 fw-semibold">#{{ $order->order_number }}</td>
                            <td>
                                <div class="fw-semibold">{{ $order->first_name }} {{ $order->last_name }}</div>
                                <div class="small text-muted">{{ $order->email }}</div>
                            </td>
                            <td class="fw-bold">EGP{{ number_format($order->total, 2) }}</td>
                            <td class="fw-bold">{{ str_replace('_',' ', $order->payment_method) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning text-dark') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm text-white"
                                       style="background:linear-gradient(135deg,#667eea,#764ba2); border:none;"
                                       title="View Details">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this order?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-white"
                                                style="background:linear-gradient(135deg,#f093fb,#f5576c); border:none;"
                                                title="Delete Order">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="mdi mdi-shopping-outline" style="font-size:40px;"></i>
                                <p class="mt-2">No orders found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
