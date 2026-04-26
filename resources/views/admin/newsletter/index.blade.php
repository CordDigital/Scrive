@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="mdi mdi-email-newsletter me-2" style="color:#667eea;"></i>
                Newsletter
            </h4>
            <p class="text-muted mb-0 mt-1">Manage newsletter subscribers</p>
        </div>
        <a href="{{ route('admin.newsletter.export') }}"
           class="btn btn-sm text-white"
           style="background:linear-gradient(135deg,#11998e,#38ef7d);">
            <i class="mdi mdi-download me-1"></i> Export CSV
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white rounded-3"
                 style="background:linear-gradient(135deg,#667eea,#764ba2);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <i class="mdi mdi-account-multiple" style="font-size:36px; opacity:.8;"></i>
                    <div>
                        <div class="fs-4 fw-bold">{{ $totalCount }}</div>
                        <div class="small opacity-75">Total Subscribers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white rounded-3"
                 style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <i class="mdi mdi-account-check" style="font-size:36px; opacity:.8;"></i>
                    <div>
                        <div class="fs-4 fw-bold">{{ $activeCount }}</div>
                        <div class="small opacity-75">Active Subscribers</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white rounded-3"
                 style="background:linear-gradient(135deg,#f093fb,#f5576c);">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <i class="mdi mdi-account-off" style="font-size:36px; opacity:.8;"></i>
                    <div>
                        <div class="fs-4 fw-bold">{{ $totalCount - $activeCount }}</div>
                        <div class="small opacity-75">Inactive Subscribers</div>
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

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:linear-gradient(135deg,#667eea,#764ba2); color:white;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Email Address</th>
                            <th class="py-3">Subscription Date</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscribers as $sub)
                        <tr>
                            <td class="px-4">{{ $sub->id }}</td>
                            <td class="fw-semibold">{{ $sub->email }}</td>
                            <td class="text-muted small">{{ $sub->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($sub->is_active)
                                    <span class="badge bg-success px-3 py-1">Active</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Toggle --}}
                                    <form action="{{ route('admin.newsletter.toggle', $sub) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm text-white" style="background:linear-gradient(135deg,#f7971e,#ffd200); border:none;"
                                                title="{{ $sub->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="mdi {{ $sub->is_active ? 'mdi-account-off' : 'mdi-account-check' }}"></i>
                                        </button>
                                    </form>
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.newsletter.destroy', $sub) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this subscriber?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-white"
                                                style="background:linear-gradient(135deg,#f093fb,#f5576c); border:none;">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="mdi mdi-email-off-outline" style="font-size:40px;"></i>
                                <p class="mt-2">No subscribers found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">{{ $subscribers->links() }}</div>

</div>
@endsection
