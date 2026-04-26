@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">
                <i class="mdi mdi-comment-multiple me-2" style="color:#667eea;"></i>
                Blog Comments
            </h4>
            <p class="text-muted mb-0 mt-1">Manage blog comments</p>
        </div>
        @if($pendingCount > 0)
        <span class="badge fs-6 px-3 py-2" style="background:linear-gradient(135deg,#f093fb,#f5576c);">
            {{ $pendingCount }} pending
        </span>
        @endif
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
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Post</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Comment</th>
                            <th class="py-3">Rating</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                        <tr class="{{ !$comment->is_approved ? 'table-warning' : '' }}">
                            <td class="px-4">{{ $comment->id }}</td>
                            <td>
                                <a href="{{ route('blog.show', $comment->blog) }}" target="_blank"
                                   class="text-decoration-none fw-semibold text-dark">
                                    {{ Str::limit($comment->blog->title, 30) }}
                                </a>
                            </td>
                            <td>{{ $comment->name }}</td>
                            <td class="text-muted small">{{ $comment->email }}</td>
                            <td style="max-width:220px;">
                                <span title="{{ $comment->message }}">
                                    {{ Str::limit($comment->message, 60) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="mdi mdi-star{{ $i <= $comment->rating ? '' : '-outline' }}"
                                           style="color:#ffd200; font-size:14px;"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="text-muted small">{{ $comment->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($comment->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Approve / Unapprove --}}
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="btn btn-sm"
                                            style="background:linear-gradient(135deg,#11998e,#38ef7d); color:white; border:none;"
                                            title="{{ $comment->is_approved ? 'Unapprove' : 'Approve' }}">
                                            <i class="mdi {{ $comment->is_approved ? 'mdi-eye-off' : 'mdi-check' }}"></i>
                                        </button>
                                    </form>
                                    {{-- Delete --}}
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                          onsubmit="return confirm('Delete this comment?')">
                                        @csrf @method('DELETE')
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
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="mdi mdi-comment-off-outline" style="font-size:40px;"></i>
                                <p class="mt-2">No comments yet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection
