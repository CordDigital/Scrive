@extends('frontend.layouts.app')

@section('content')

    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Contact Us</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="{{ route(app()->getLocale() === 'ar' ? 'home' : 'en.home') }}">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Contact</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Contact Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">Contact For Any Queries</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col-lg-7 mb-5">
                <div class="contact-form">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route(app()->getLocale() === 'ar' ? 'contact.send' : 'en.contact.send') }}" method="POST">
                        @csrf
                        <div class="control-group">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Your Name"
                                value="{{ old('name') }}" required />
                            @error('name')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="control-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Your Email"
                                value="{{ old('email') }}" required />
                            @error('email')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="control-group">
                            <textarea class="form-control @error('message') is-invalid @enderror" rows="6" name="message" placeholder="Message"
                                required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <button class="btn btn-primary py-2 px-4" type="submit">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 mb-5">
                <h5 class="font-weight-semi-bold mb-3">Get In Touch</h5>

                @if(isset($contact) && $contact)
                    {{-- Dynamic contact info --}}
                    @if($contact->address)
                        <div class="d-flex flex-column mb-3">
                            <h5 class="font-weight-semi-bold mb-3">Our Address</h5>
                            <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>{{ $contact->address }}</p>
                        </div>
                    @endif

                    @if($contact->email)
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>{{ $contact->email }}</p>
                    @endif

                    @if($contact->phone)
                        <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>{{ $contact->phone }}</p>
                    @endif

                    {{-- Working Hours --}}
                    @if($contact->mon_fri || $contact->saturday || $contact->sunday)
                    <div class="mt-4">
                        <h5 class="font-weight-semi-bold mb-3">Working Hours</h5>
                        @if($contact->mon_fri)
                            <p class="mb-2"><i class="fa fa-clock text-primary mr-3"></i><strong>Mon - Fri:</strong> {{ $contact->mon_fri }}</p>
                        @endif
                        @if($contact->saturday)
                            <p class="mb-2"><i class="fa fa-clock text-primary mr-3"></i><strong>Saturday:</strong> {{ $contact->saturday }}</p>
                        @endif
                        @if($contact->sunday)
                            <p class="mb-0"><i class="fa fa-clock text-primary mr-3"></i><strong>Sunday:</strong> {{ $contact->sunday }}</p>
                        @endif
                    </div>
                    @endif

                    {{-- Map --}}
                    @if($contact->map_url)
                    <div class="mt-4">
                        <iframe src="{{ $contact->map_url }}" width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    @endif
                @else
                    {{-- Fallback --}}
                    <p>We'd love to hear from you. Please fill out the form and we'll get back to you as soon as possible.</p>
                    <div class="d-flex flex-column mb-3">
                        <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Contact End -->

@endsection
