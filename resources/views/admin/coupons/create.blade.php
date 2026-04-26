@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="mdi mdi-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Add New Coupon</h4>
    </div>

    <div class="card border-0 shadow-sm" style="max-width:700px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.coupons.store') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-barcode me-1 text-muted"></i>Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase"
                               value="{{ old('code') }}" placeholder="e.g. SAVE20" required>
                        @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-tag me-1 text-muted"></i>Discount Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select">
                            <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Percentage %</option>
                            <option value="fixed"   {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount $</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="mdi mdi-currency-usd me-1 text-muted"></i>Value <span class="text-danger">*</span></label>
                        <input type="number" name="value" class="form-control"
                               value="{{ old('value') }}" min="0" step="0.01" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="mdi mdi-cart me-1 text-muted"></i>Min. Order Amount</label>
                        <input type="number" name="min_order" class="form-control"
                               value="{{ old('min_order', 0) }}" min="0" step="0.01">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold"><i class="mdi mdi-counter me-1 text-muted"></i>Usage Limit</label>
                        <input type="number" name="max_uses" class="form-control"
                               value="{{ old('max_uses') }}" min="1" placeholder="Unlimited">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold"><i class="mdi mdi-calendar-clock me-1 text-muted"></i>Expiry Date</label>
                        <input type="date" name="expires_at" class="form-control"
                               value="{{ old('expires_at') }}">
                    </div>

                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="btn text-white px-5 shadow-sm border-0"
                                style="background:linear-gradient(135deg,#667eea,#764ba2);">
                            <i class="mdi mdi-content-save me-1"></i> Save Coupon
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
