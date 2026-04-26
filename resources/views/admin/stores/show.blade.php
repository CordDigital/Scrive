@extends('admin.layouts.app')

@push('styles')
<style>
    .store-cover-header {
        height: 350px;
        background-size: cover;
        background-position: center;
        border-radius: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .store-overlay {
        position: absolute;
        bottom: 0; left: 0; width: 100%;
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        padding: 30px;
        color: white;
    }
    .content-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }
    /* تنسيق الوصف ليظهر بنفس تنسيق الوورد */
    .description-box {
        line-height: 1.8;
        font-size: 16px;
        color: #444;
    }
    .description-box img { max-width: 100%; height: auto; border-radius: 8px; }

    /* تنسيق معرض الصور */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 15px;
    }
    .gallery-img-item {
        height: 150px;
        border-radius: 10px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s;
        border: 1px solid #eee;
    }
    .gallery-img-item:hover {
        transform: scale(1.05);
    }
    .badge-sort {
        background: #667eea;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- Top Action Bar --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.stores.index') }}">Stores</a></li>
                <li class="breadcrumb-item active">{{ $store->name_en }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-primary">
                <i class="mdi mdi-pencil me-1"></i> Edit Store
            </a>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">

            {{-- 1. Store Cover & Titles --}}
            <div class="store-cover-header mb-4"
                 style="background-image: url('{{ $store->cover_image ? asset('storage/' . $store->cover_image) : asset('assets/images/default-store.jpg') }}')">
                <div class="store-overlay">
                    <span class="badge-sort mb-2 d-inline-block">Order: {{ $store->sort_order }}</span>
                    <h2 class="fw-bold mb-1">{{ $store->name_en }}</h2>
                    <h4 dir="rtl" class="mb-0">{{ $store->name_ar }}</h4>
                </div>
            </div>

            {{-- 2. Descriptions --}}
            <div class="card content-card mb-4">
                <div class="card-body p-4">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="en-tab" data-bs-toggle="pill" data-bs-target="#desc-en" type="button">English Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ar-tab" data-bs-toggle="pill" data-bs-target="#desc-ar" type="button">الوصف العربي</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        {{-- English Content --}}
                        <div class="tab-pane fade show active" id="desc-en" role="tabpanel">
                            <div class="description-box p-2">
                                {!! $store->description_en ?: '<p class="text-muted">No English description provided.</p>' !!}
                            </div>
                        </div>
                        {{-- Arabic Content --}}
                        <div class="tab-pane fade" id="desc-ar" role="tabpanel" dir="rtl">
                            <div class="description-box p-2 text-end">
                                {!! $store->description_ar ?: '<p class="text-muted text-center">لا يوجد وصف عربي متاح.</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Image Gallery --}}
            <div class="card content-card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="mdi mdi-image-multiple text-info me-2"></i>Store Gallery</h5>
                </div>
                <div class="card-body">
                    @if($store->images->count() > 0)
                        <div class="gallery-grid">
                            @foreach($store->images as $img)
                                <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="gallery-img-item w-100 shadow-sm" alt="Gallery Image">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-image-off-outline text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted">No gallery images uploaded for this store.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. Second Description --}}
            <div class="card content-card">
                <div class="card-body p-4">
                    <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="en-tab2" data-bs-toggle="pill" data-bs-target="#desc2-en" type="button">English Second Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ar-tab2" data-bs-toggle="pill" data-bs-target="#desc2-ar" type="button">الوصف الثاني</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent2">
                        <div class="tab-pane fade show active" id="desc2-en" role="tabpanel">
                            <div class="description-box p-2">
                                {!! $store->description_second_en ?: '<p class="text-muted">No English second description provided.</p>' !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="desc2-ar" role="tabpanel" dir="rtl">
                            <div class="description-box p-2 text-end">
                                {!! $store->description_second_ar ?: '<p class="text-muted text-center">لا يوجد وصف ثاني بالعربية.</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Column: Meta Info --}}
        <div class="col-lg-4">
            <div class="card content-card mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">System Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Store ID</label>
                        <span class="fw-bold">#{{ $store->id }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">English Slug</label>
                        <code class="bg-light p-1 rounded">{{ $store->slug_en }}</code>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Arabic Slug</label>
                        <code class="bg-light p-1 rounded text-end d-block" dir="rtl">{{ $store->slug_ar }}</code>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Created At</label>
                        <span>{{ $store->created_at->format('M d, Y - h:i A') }}</span>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Last Updated</label>
                        <span>{{ $store->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="card content-card bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h3 class="mb-1">{{ $store->images->count() }}</h3>
                    <p class="mb-0 opacity-75">Gallery Images</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
