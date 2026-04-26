@extends('admin.layouts.app')

@section('content')
<div class="row mx-3 my-4">

    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bold mb-1">Contact Page</h4>
            <p class="text-muted mb-0">Manage contact page info</p>
        </div>
        <a href="{{ route('admin.contact.messages') }}"
           class="btn btn-outline-secondary btn-icon-text">
            <i class="mdi mdi-email btn-icon-prepend"></i>
            View Messages
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

    @if($errors->any())
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="col-12">
        <form action="{{ route('admin.contact.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">

                {{-- Left --}}
                <div class="col-lg-8">

                    {{-- Store Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-store me-2"></i>Store Info
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-map-marker me-1 text-muted"></i>Address (Arabic) <span class="text-danger">*</span></label>
                                    <textarea name="address_ar" rows="3"
                                              class="form-control @error('address_ar') is-invalid @enderror"
                                              required>{{ old('address_ar', $contact->address_ar) }}</textarea>
                                    @error('address_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-map-marker me-1 text-muted"></i>Address (English) <span class="text-danger">*</span></label>
                                    <textarea name="address_en" rows="3"
                                              class="form-control @error('address_en') is-invalid @enderror"
                                              required>{{ old('address_en', $contact->address_en) }}</textarea>
                                    @error('address_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-phone me-1 text-muted"></i>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone"
                                           value="{{ old('phone', $contact->phone) }}"
                                           class="form-control @error('phone') is-invalid @enderror" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-email me-1 text-muted"></i>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                           value="{{ old('email', $contact->email) }}"
                                           class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-google-maps me-1 text-muted"></i>Google Maps Embed URL <span class="text-danger">*</span></label>
                                    <input type="text" name="map_url"
                                           value="{{ old('map_url', $contact->map_url) }}"
                                           class="form-control @error('map_url') is-invalid @enderror"
                                           placeholder="https://www.google.com/maps/embed?..." required>
                                    @error('map_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Open Hours --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-clock me-2"></i>Open Hours
                            </h6>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>Mon-Fri (Arabic)</label>
                                    <input type="text" name="mon_fri_ar"
                                           value="{{ old('mon_fri_ar', $contact->mon_fri_ar) }}"
                                           class="form-control" placeholder="٧:٣٠ص - ٨:٠٠م">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>Mon-Fri (English)</label>
                                    <input type="text" name="mon_fri_en"
                                           value="{{ old('mon_fri_en', $contact->mon_fri_en) }}"
                                           class="form-control" placeholder="7:30am - 8:00pm">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>Saturday (Arabic)</label>
                                    <input type="text" name="saturday_ar"
                                           value="{{ old('saturday_ar', $contact->saturday_ar) }}"
                                           class="form-control" placeholder="٨:٠٠ص - ٦:٠٠م">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>Saturday (English)</label>
                                    <input type="text" name="saturday_en"
                                           value="{{ old('saturday_en', $contact->saturday_en) }}"
                                           class="form-control" placeholder="8:00am - 6:00pm">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>Sunday (Arabic)</label>
                                    <input type="text" name="sunday_ar"
                                           value="{{ old('sunday_ar', $contact->sunday_ar) }}"
                                           class="form-control" placeholder="٩:٠٠ص - ٥:٠٠م">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-medium"><i class="mdi mdi-clock-outline me-1 text-muted"></i>Sunday (English)</label>
                                    <input type="text" name="sunday_en"
                                           value="{{ old('sunday_en', $contact->sunday_en) }}"
                                           class="form-control" placeholder="9:00am - 5:00pm">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right --}}
                <div class="col-lg-4">

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header border-0"
                             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 4px 4px 0 0;">
                            <h6 class="text-white mb-0 py-1">
                                <i class="mdi mdi-information me-2"></i>Info
                            </h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <small class="text-muted">Last Updated</small>
                                <small class="font-weight-medium">
                                    {{ $contact->updated_at ? $contact->updated_at->format('d M Y') : 'Never' }}
                                </small>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <small class="text-muted">Messages</small>
                                <a href="{{ route('admin.contact.messages') }}" class="small font-weight-medium">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <button type="submit"
                                    class="btn btn-block btn-lg text-white border-0 mb-3"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <i class="mdi mdi-content-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.dashboard') }}"
                               class="btn btn-block btn-lg btn-outline-secondary">
                                <i class="mdi mdi-close me-2"></i>Cancel
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
</div>
@endsection