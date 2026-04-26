@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <h4 class="mb-4 fw-bold">
        <i class="mdi mdi-lightning-bolt me-2" style="color:#667eea;"></i>
        Flash Sale Settings
    </h4>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width:700px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.flash-sale.update') }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">

                    {{-- Titles --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-format-title me-1 text-muted"></i>Title (Arabic)</label>
                        <input type="text" name="title_ar" class="form-control"
                               value="{{ old('title_ar', $flashSale->title_ar) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-format-title me-1 text-muted"></i>Title (English)</label>
                        <input type="text" name="title_en" class="form-control"
                               value="{{ old('title_en', $flashSale->title_en) }}" required>
                    </div>

                    {{-- Subtitles --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-subtitles me-1 text-muted"></i>Subtitle (Arabic)</label>
                        <input type="text" name="subtitle_ar" class="form-control"
                               value="{{ old('subtitle_ar', $flashSale->subtitle_ar) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-subtitles me-1 text-muted"></i>Subtitle (English)</label>
                        <input type="text" name="subtitle_en" class="form-control"
                               value="{{ old('subtitle_en', $flashSale->subtitle_en) }}">
                    </div>

                    {{-- Image --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold"><i class="mdi mdi-image me-1 text-muted"></i>Flash Sale Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($flashSale->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $flashSale->image) }}" alt="Flash Sale Image"
                                     class="rounded" style="max-height:150px;">
                            </div>
                        @endif
                    </div>

                    {{-- Numerical Settings --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="mdi mdi-percent me-1 text-muted"></i>Discount %</label>
                        <input type="number" name="discount_percent" class="form-control"
                               value="{{ old('discount_percent', $flashSale->discount_percent) }}"
                               min="0" max="100" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="mdi mdi-currency-usd me-1 text-muted"></i>Min Amount $</label>
                        <input type="number" name="min_amount" class="form-control"
                               value="{{ old('min_amount', $flashSale->min_amount) }}"
                               min="0" step="0.01">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="mdi mdi-calendar-clock me-1 text-muted"></i>Ends At</label>
                        <input type="datetime-local" name="ends_at" class="form-control"
                               value="{{ old('ends_at', $flashSale->ends_at?->format('Y-m-d\TH:i')) }}">
                    </div>

                    {{-- Active Status --}}
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1"
                                   {{ old('is_active', $flashSale->is_active ?? true) ? 'checked' : '' }}>
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
