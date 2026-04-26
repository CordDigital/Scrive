@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width:48px;height:48px;background:linear-gradient(135deg,#667eea,#764ba2);">
                <i class="mdi mdi-package-variant text-white" style="font-size:1.4rem;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">{{ $product->name_en }}</h4>
                <small class="text-muted">{{ $product->name_ar }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn text-white border-0 shadow-sm"
               style="background:linear-gradient(135deg,#f7971e,#ffd200);">
                <i class="mdi mdi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row g-4" style="max-width:1100px;">

        {{-- Main Image & Info --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <img src="{{ Storage::url($product->image) }}" class="card-img-top" style="height:300px;object-fit:cover;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h3 class="fw-bold mb-0">EGP{{ number_format($product->price, 2) }}</h3>
                            @if($product->old_price)
                                <small class="text-muted"><del>EGP{{ number_format($product->old_price, 2) }}</del></small>
                                <span class="badge bg-danger ms-1">-{{ $product->discount_percent }}%</span>
                            @endif
                        </div>
                        <div>
                            @if($product->is_active)
                                <span class="badge" style="background:linear-gradient(135deg,#11998e,#38ef7d);padding:6px 14px;">Active</span>
                            @else
                                <span class="badge" style="background:linear-gradient(135deg,#f093fb,#f5576c);padding:6px 14px;">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted fw-semibold" style="width:40%;">Category</td>
                            <td><span class="badge bg-light text-dark">{{ $product->category?->name_en }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Stock</td>
                            <td>
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Brand</td>
                            <td>{{ $product->brand ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Featured</td>
                            <td>
                                @if($product->is_featured)
                                    <i class="mdi mdi-star text-warning"></i> Yes
                                @else
                                    <i class="mdi mdi-star-outline text-muted"></i> No
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Sort Order</td>
                            <td>{{ $product->sort_order }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Created</td>
                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="col-lg-8">

            {{-- Sizes & Colors --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                    <h6 class="text-white mb-0"><i class="mdi mdi-palette me-2"></i>Sizes & Colors</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase mb-2">Sizes</label>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($product->sizes ?? [] as $size)
                                    <span class="badge bg-light text-dark border px-3 py-2">{{ $size }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase mb-2">Colors</label>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($product->colors ?? [] as $color)
                                    <span class="badge bg-light text-dark border px-3 py-2">{{ $color }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                    <h6 class="text-white mb-0"><i class="mdi mdi-text me-2"></i>Description</h6>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#desc-en">English</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#desc-ar">Arabic</a>
                        </li>
                    </ul>
                    <div class="tab-content pt-3">
                        <div class="tab-pane active" id="desc-en">
                            <div class="ql-editor p-0">{!! $product->description_en !!}</div>
                        </div>
                        <div class="tab-pane" id="desc-ar" dir="rtl">
                            <div class="ql-editor p-0">{!! $product->description_ar !!}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gallery --}}
            @if($product->images->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#f7971e,#ffd200);">
                    <h6 class="text-white mb-0"><i class="mdi mdi-image-multiple me-2"></i>Gallery ({{ $product->images->count() }} images)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($product->images as $img)
                        <div class="col-md-3 col-6">
                            <div class="card border overflow-hidden shadow-none">
                                <img src="{{ Storage::url($img->image) }}" style="height:120px;object-fit:cover;">
                                <div class="card-footer p-2 bg-light text-center">
                                    <small class="d-block text-truncate fw-semibold">{{ $img->color_en }}</small>
                                    <small class="d-block text-truncate text-muted">{{ $img->color_ar }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- SEO --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                    <h6 class="text-white mb-0"><i class="mdi mdi-magnify me-2"></i>SEO</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Meta Title (EN)</label>
                            <p class="mb-0">{{ $product->meta_title_en ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Meta Title (AR)</label>
                            <p class="mb-0">{{ $product->meta_title_ar ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Meta Description (EN)</label>
                            <p class="mb-0 small">{{ $product->meta_description_en ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Meta Description (AR)</label>
                            <p class="mb-0 small">{{ $product->meta_description_ar ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Keywords (EN)</label>
                            <p class="mb-0 small">{{ $product->meta_keywords_en ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Keywords (AR)</label>
                            <p class="mb-0 small">{{ $product->meta_keywords_ar ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
