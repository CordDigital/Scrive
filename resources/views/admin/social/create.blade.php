@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Add Social Link</h4>
        </div>
        <a href="{{ route('admin.social.index') }}" class="btn btn-outline-secondary">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="col-12">

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>There were some problems with your input:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Display Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.social.store') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-share-variant me-2"></i>Social Link Details
                            </h6>
                        </div>
                        <div class="card-body pt-4">

                            {{-- Platform --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">Platform <span class="text-danger">*</span></label>
                                <select name="platform" class="form-control @error('platform') is-invalid @enderror" required
                                        onchange="setIcon(this.value)">
                                    <option value="">-- Select Platform --</option>
                                    <option value="facebook"  {{ old('platform') === 'facebook'  ? 'selected' : '' }}>Facebook</option>
                                    <option value="instagram" {{ old('platform') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="twitter"   {{ old('platform') === 'twitter'   ? 'selected' : '' }}>Twitter / X</option>
                                    <option value="youtube"   {{ old('platform') === 'youtube'   ? 'selected' : '' }}>YouTube</option>
                                    <option value="tiktok"    {{ old('platform') === 'tiktok'    ? 'selected' : '' }}>TikTok</option>
                                    <option value="linkedin"  {{ old('platform') === 'linkedin'  ? 'selected' : '' }}>LinkedIn</option>
                                    <option value="pinterest" {{ old('platform') === 'pinterest' ? 'selected' : '' }}>Pinterest</option>
                                    <option value="snapchat"  {{ old('platform') === 'snapchat'  ? 'selected' : '' }}>Snapchat</option>
                                </select>
                                @error('platform')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Icon --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">Icon Class <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="text" name="icon" id="iconInput"
                                           value="{{ old('icon') }}"
                                           class="form-control @error('icon') is-invalid @enderror"
                                           placeholder="fa-brands fa-facebook-f" required
                                           oninput="updatePreview()">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="background: linear-gradient(135deg, #667eea, #764ba2); width:45px; height:45px;">
                                        <i id="iconPreview" class="{{ old('icon', 'mdi mdi-share-variant') }} text-white" style="font-size:1.3rem;"></i>
                                    </div>
                                </div>
                                @error('icon')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            {{-- URL --}}
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">URL <span class="text-danger">*</span></label>
                                <input type="url" name="url"
                                       value="{{ old('url') }}"
                                       class="form-control @error('url') is-invalid @enderror"
                                       placeholder="https://www.facebook.com/yourpage" required>
                                @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-cog me-2"></i>Settings
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="mb-3">
                                <label class="form-label font-weight-medium">Sort Order</label>
                                <input type="number" name="sort_order"
                                       value="{{ old('sort_order', 0) }}"
                                       class="form-control" min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-medium d-block">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_active" id="is_active" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i> Save
                            </button>
                            <a href="{{ route('admin.social.index') }}"
                               class="btn btn-block btn-lg btn-outline-secondary">
                                <i class="mdi mdi-close me-2"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const iconMap = {
        facebook:  'fa-brands fa-facebook-f',
        instagram: 'fa-brands fa-instagram',
        twitter:   'fa-brands fa-x-twitter',
        youtube:   'fa-brands fa-youtube',
        tiktok:    'fa-brands fa-tiktok',
        linkedin:  'fa-brands fa-linkedin-in',
        pinterest: 'fa-brands fa-pinterest-p',
        snapchat:  'fa-brands fa-snapchat',
    };

    function setIcon(platform) {
        const input = document.getElementById('iconInput');
        if (iconMap[platform]) {
            input.value = iconMap[platform];
            updatePreview();
        }
    }

    function updatePreview() {
        const input = document.getElementById('iconInput');
        const preview = document.getElementById('iconPreview');
        preview.className = input.value + ' text-white';
    }
</script>
@endpush
