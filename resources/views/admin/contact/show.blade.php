@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Message Details</h4>
        </div>
        <a href="{{ route('admin.contact.messages') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                <h6 class="text-white mb-0 py-1">
                    <i class="mdi mdi-email me-2"></i>Message from {{ $message->name }}
                </h6>
            </div>
            <div class="card-body pt-4">
                <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
                    <div>
                        <small class="text-muted d-block">Name</small>
                        <strong>{{ $message->name }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                    </div>
                    <div>
                        <small class="text-muted d-block">Date</small>
                        <span>{{ $message->created_at->format('d M Y - h:i A') }}</span>
                    </div>
                </div>
                <div>
                    <small class="text-muted d-block mb-2">Message</small>
                    <p class="mb-0" style="line-height:1.8;">{{ $message->message }}</p>
                </div>
            </div>
            <div class="card-footer border-0 bg-white pb-4 px-4">
                <a href="mailto:{{ $message->email }}"
                   class="btn text-white border-0"
                   style="background: linear-gradient(135deg, #667eea, #764ba2);">
                    <i class="mdi mdi-reply me-1"></i> Reply via Email
                </a>
                <form action="{{ route('admin.contact.destroy', $message) }}"
                      method="POST" class="d-inline ms-2">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn text-white border-0"
                            style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <i class="mdi mdi-delete me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection