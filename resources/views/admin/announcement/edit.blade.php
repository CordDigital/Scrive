@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <h4 class="mb-4 fw-bold">
        <i class="mdi mdi-bullhorn me-2" style="color:#667eea;"></i>
        Announcement Bar Settings
    </h4>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width:700px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.announcement.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="row g-3">

                    {{-- Text Arabic --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold"><i class="mdi mdi-translate me-1 text-muted"></i>Text (Arabic)</label>
                        <input type="text" name="text_ar" class="form-control"
                               value="{{ old('text_ar', $announcement->text_ar) }}" required>
                    </div>

                    {{-- Text English --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold"><i class="mdi mdi-translate me-1 text-muted"></i>Text (English)</label>
                        <input type="text" name="text_en" class="form-control"
                               value="{{ old('text_en', $announcement->text_en) }}" required>
                    </div>

                    {{-- Active Status --}}
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Is Active</label>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn text-white px-5 shadow-sm border-0"
                                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="mdi mdi-content-save me-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
