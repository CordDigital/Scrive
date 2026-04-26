@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Contact Messages</h4>
            <p class="text-muted mb-0">Messages from visitors</p>
        </div>
        <a href="{{ route('admin.contact.edit') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
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
                            <i class="mdi mdi-email text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between py-4">
                        <div>
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Unread</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $unread }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-email-mark-as-unread text-white" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="card-body d-flex align-items-center justify-content-between py-4">
                        <div>
                            <p class="mb-1 text-white" style="opacity:0.8; font-size:0.85rem;">Read</p>
                            <h2 class="mb-0 font-weight-bold text-white">{{ $read }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="background:rgba(255,255,255,0.2); width:65px; height:65px;">
                            <i class="mdi mdi-email-open text-white" style="font-size:2rem;"></i>
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
                                <th class="text-white border-0">Name</th>
                                <th class="text-white border-0">Email</th>
                                <th class="text-white border-0">Message</th>
                                <th class="text-white border-0">Status</th>
                                <th class="text-white border-0">Date</th>
                                <th class="text-white border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                            <tr class="{{ !$msg->is_read ? 'table-light fw-bold' : '' }}">
                                <td><span class="text-muted">#{{ $msg->id }}</span></td>
                                <td>{{ $msg->name }}</td>
                                <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
                                <td>{{ Str::limit($msg->message, 50) }}</td>
                                <td>
                                    @if(!$msg->is_read)
                                        <span class="badge"
                                              style="background: linear-gradient(135deg, #f093fb, #f5576c); color:white; padding:6px 12px;">
                                            <i class="mdi mdi-circle me-1" style="font-size:0.5rem;"></i> Unread
                                        </span>
                                    @else
                                        <span class="badge"
                                              style="background: linear-gradient(135deg, #11998e, #38ef7d); color:white; padding:6px 12px;">
                                            <i class="mdi mdi-check me-1"></i> Read
                                        </span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $msg->created_at->format('d M Y') }}</small></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.contact.show', $msg) }}"
                                           class="btn btn-sm btn-icon-text"
                                           style="background: linear-gradient(135deg, #667eea, #764ba2); color:white; border:none;">
                                            <i class="mdi mdi-eye btn-icon-prepend"></i> View
                                        </a>
                                        <form action="{{ route('admin.contact.destroy', $msg) }}"
                                              method="POST" id="delete-form-{{ $msg->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete({{ $msg->id }})"
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
                                <td colspan="7" class="text-center py-5">
                                    <i class="mdi mdi-email-off text-muted" style="font-size:4rem;"></i>
                                    <p class="text-muted mt-3">No messages yet</p>
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