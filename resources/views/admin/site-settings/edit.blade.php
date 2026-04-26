@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
             style="width:48px;height:48px;background:linear-gradient(135deg,#667eea,#764ba2);">
            <i class="mdi mdi-cog text-white" style="font-size:1.4rem;"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">Site Settings</h4>
            <small class="text-muted">Manage logos, favicon & SEO defaults</small>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="max-width:900px;">
        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4" style="max-width:900px;">

            {{-- ============ LOGOS CARD ============ --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#667eea,#764ba2);">
                        <h6 class="text-white mb-0">
                            <i class="mdi mdi-image-multiple me-2"></i>Logos & Favicon
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">

                            {{-- Header Logo --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase">Header Logo</label>
                                <div class="border rounded-3 p-3 text-center position-relative bg-light" style="min-height:120px;" id="header-logo-zone">
                                    @if($setting->header_logo)
                                        <img src="{{ asset('storage/' . $setting->header_logo) }}" alt="Header Logo" class="rounded" style="max-height:70px;max-width:100%;" id="header-logo-preview">
                                    @else
                                        <div class="py-2" id="header-logo-placeholder">
                                            <i class="mdi mdi-cloud-upload text-muted" style="font-size:2rem;"></i>
                                            <div class="text-muted small mt-1">Upload header logo</div>
                                        </div>
                                        <img src="" alt="" class="rounded d-none" style="max-height:70px;max-width:100%;" id="header-logo-preview">
                                    @endif
                                </div>
                                <input type="file" name="header_logo" class="form-control form-control-sm mt-2" accept="image/*"
                                       onchange="previewImg(this,'header-logo-preview','header-logo-placeholder')">
                            </div>

                            {{-- Footer Logo --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase">Footer Logo</label>
                                <div class="border rounded-3 p-3 text-center position-relative bg-light" style="min-height:120px;" id="footer-logo-zone">
                                    @if($setting->footer_logo)
                                        <img src="{{ asset('storage/' . $setting->footer_logo) }}" alt="Footer Logo" class="rounded" style="max-height:70px;max-width:100%;" id="footer-logo-preview">
                                    @else
                                        <div class="py-2" id="footer-logo-placeholder">
                                            <i class="mdi mdi-cloud-upload text-muted" style="font-size:2rem;"></i>
                                            <div class="text-muted small mt-1">Upload footer logo</div>
                                        </div>
                                        <img src="" alt="" class="rounded d-none" style="max-height:70px;max-width:100%;" id="footer-logo-preview">
                                    @endif
                                </div>
                                <input type="file" name="footer_logo" class="form-control form-control-sm mt-2" accept="image/*"
                                       onchange="previewImg(this,'footer-logo-preview','footer-logo-placeholder')">
                            </div>

                            {{-- Favicon --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-muted small text-uppercase">Favicon</label>
                                <div class="border rounded-3 p-3 text-center position-relative bg-light" style="min-height:120px;" id="favicon-zone">
                                    @if($setting->favicon)
                                        <img src="{{ asset('storage/' . $setting->favicon) }}" alt="Favicon" class="rounded" style="max-height:48px;" id="favicon-preview">
                                    @else
                                        <div class="py-2" id="favicon-placeholder">
                                            <i class="mdi mdi-star-four-points text-muted" style="font-size:2rem;"></i>
                                            <div class="text-muted small mt-1">Upload favicon</div>
                                        </div>
                                        <img src="" alt="" class="rounded d-none" style="max-height:48px;" id="favicon-preview">
                                    @endif
                                </div>
                                <input type="file" name="favicon" class="form-control form-control-sm mt-2" accept="image/png,image/x-icon">
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ SEO CARD ============ --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#11998e,#38ef7d);">
                        <h6 class="text-white mb-0">
                            <i class="mdi mdi-magnify me-2"></i>SEO Defaults
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- Site Title --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-format-title me-1 text-muted"></i> Site Title
                                </label>
                                <input type="text" name="seo_title" class="form-control"
                                       value="{{ old('seo_title', $setting->seo_title) }}"
                                       placeholder="ZIZI ABUSALLA">
                                <div class="form-text">Appears in browser tab & search results</div>
                            </div>

                            {{-- Meta Description --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-text me-1 text-muted"></i> Meta Description
                                </label>
                                <textarea name="seo_description" class="form-control" rows="3"
                                          placeholder="A brief description of your site for search engines..."
                                          id="seo-desc">{{ old('seo_description', $setting->seo_description) }}</textarea>
                                <div class="d-flex justify-content-between">
                                    <div class="form-text">Recommended: 150-160 characters</div>
                                    <small class="text-muted" id="desc-counter">0 / 160</small>
                                </div>
                            </div>

                            {{-- Meta Keywords --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-tag-multiple me-1 text-muted"></i> Meta Keywords
                                </label>
                                <input type="text" name="seo_keywords" class="form-control"
                                       value="{{ old('seo_keywords', $setting->seo_keywords) }}"
                                       placeholder="fashion, clothing, zizi, abaya, modest wear">
                                <div class="form-text">Comma-separated keywords</div>
                            </div>

                            {{-- OG Image --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-share-variant me-1 text-muted"></i> OG Image
                                    <span class="text-muted fw-normal">(Social Share)</span>
                                </label>
                                <div class="border rounded-3 p-3 text-center bg-light" style="min-height:100px;">
                                    @if($setting->og_image)
                                        <img src="{{ asset('storage/' . $setting->og_image) }}" alt="OG Image" class="rounded" style="max-height:80px;max-width:100%;" id="og-preview">
                                    @else
                                        <div class="py-2" id="og-placeholder">
                                            <i class="mdi mdi-image-area text-muted" style="font-size:2rem;"></i>
                                            <div class="text-muted small mt-1">1200 x 630 recommended</div>
                                        </div>
                                        <img src="" alt="" class="rounded d-none" style="max-height:80px;max-width:100%;" id="og-preview">
                                    @endif
                                </div>
                                <input type="file" name="og_image" class="form-control form-control-sm mt-2" accept="image/*"
                                       onchange="previewImg(this,'og-preview','og-placeholder')">
                            </div>

                            {{-- SEO Preview --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-google me-1 text-muted"></i> Search Preview
                                </label>
                                <div class="border rounded-3 p-3 bg-white">
                                    <div class="text-primary small text-truncate" style="font-size:11px;">{{ url('/') }}</div>
                                    <div class="fw-semibold text-truncate" style="color:#1a0dab;font-size:15px;" id="preview-title">
                                        {{ $setting->seo_title ?? 'ZIZI ABUSALLA' }}
                                    </div>
                                    <div class="text-muted small mt-1" style="font-size:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" id="preview-desc">
                                        {{ $setting->seo_description ?? 'Your site description will appear here...' }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ OPEN GRAPH CARD ============ --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                        <h6 class="text-white mb-0">
                            <i class="mdi mdi-facebook me-2"></i>Open Graph (Social Media)
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-web me-1 text-muted"></i> Site Name
                                </label>
                                <input type="text" name="og_site_name" class="form-control"
                                       value="{{ old('og_site_name', $setting->og_site_name) }}"
                                       placeholder="ZIZI ABUSALLA">
                                <div class="form-text">Displayed on Facebook/LinkedIn shares</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-shape me-1 text-muted"></i> OG Type
                                </label>
                                <select name="og_type" class="form-select">
                                    <option value="website" {{ old('og_type', $setting->og_type) === 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="article" {{ old('og_type', $setting->og_type) === 'article' ? 'selected' : '' }}>Article</option>
                                    <option value="product" {{ old('og_type', $setting->og_type) === 'product' ? 'selected' : '' }}>Product</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ TWITTER CARD ============ --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#0f2027,#2c5364);">
                        <h6 class="text-white mb-0">
                            <i class="mdi mdi-twitter me-2"></i>Twitter Card
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-card-text me-1 text-muted"></i> Card Type
                                </label>
                                <select name="twitter_card" class="form-select">
                                    <option value="summary_large_image" {{ old('twitter_card', $setting->twitter_card) === 'summary_large_image' ? 'selected' : '' }}>Summary Large Image</option>
                                    <option value="summary" {{ old('twitter_card', $setting->twitter_card) === 'summary' ? 'selected' : '' }}>Summary</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-at me-1 text-muted"></i> Twitter Handle
                                </label>
                                <input type="text" name="twitter_handle" class="form-control"
                                       value="{{ old('twitter_handle', $setting->twitter_handle) }}"
                                       placeholder="@yourhandle">
                                <div class="form-text">Your Twitter/X username</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ TRACKING CARD ============ --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#f7971e,#ffd200);">
                        <h6 class="text-white mb-0">
                            <i class="mdi mdi-chart-line me-2"></i>Tracking & Verification
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-google-analytics me-1 text-muted"></i> Google Analytics
                                </label>
                                <input type="text" name="google_analytics" class="form-control"
                                       value="{{ old('google_analytics', $setting->google_analytics) }}"
                                       placeholder="G-XXXXXXXXXX">
                                <div class="form-text">Measurement ID</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-shield-check me-1 text-muted"></i> Google Verification
                                </label>
                                <input type="text" name="google_verification" class="form-control"
                                       value="{{ old('google_verification', $setting->google_verification) }}"
                                       placeholder="verification code">
                                <div class="form-text">Search Console verification</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-facebook me-1 text-muted"></i> Facebook Pixel
                                </label>
                                <input type="text" name="facebook_pixel" class="form-control"
                                       value="{{ old('facebook_pixel', $setting->facebook_pixel) }}"
                                       placeholder="XXXXXXXXXXXXXXX">
                                <div class="form-text">Pixel ID for tracking</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ SUBMIT ============ --}}
            <div class="col-12">
                <button type="submit" class="btn text-white px-5 py-2 shadow-sm border-0"
                        style="background:linear-gradient(135deg,#667eea,#764ba2);border-radius:10px;">
                    <i class="mdi mdi-content-save me-1"></i> Save Settings
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImg(input, previewId, placeholderId) {
        var preview = document.getElementById(previewId);
        var placeholder = document.getElementById(placeholderId);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Character counter for description
    var descEl = document.getElementById('seo-desc');
    var counterEl = document.getElementById('desc-counter');
    var previewTitle = document.getElementById('preview-title');
    var previewDesc = document.getElementById('preview-desc');

    function updateCounter() {
        var len = descEl.value.length;
        counterEl.textContent = len + ' / 160';
        counterEl.style.color = len > 160 ? '#dc3545' : '#6c757d';
        previewDesc.textContent = descEl.value || 'Your site description will appear here...';
    }
    descEl.addEventListener('input', updateCounter);
    updateCounter();

    // Live title preview
    var titleEl = document.querySelector('input[name="seo_title"]');
    titleEl.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'ZIZI ABUSALLA';
    });
</script>
@endpush
