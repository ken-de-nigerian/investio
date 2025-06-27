@extends('layouts.app')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Contact Us</li>
                    </ol>
                </nav>
                <h5>Contact Us</h5>
            </div>
        </div>

        <div class="card adminuiux-card border rounded-4 mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-xl-5 d-flex flex-column px-lg-4">
                        <div class="row h-100 align-items-center justify-content-center ">
                            <div class="col-12 py-3">
                                <h2 class="mb-3">Get in Touch<br>We're Here to Help!</h2>
                                <p class="text-secondary mb-4">Have questions about your investments or need assistance? Send us a message and our dedicated support team will respond within 24 hours.</p>

                                <!-- Contact Form -->
                                <form action="{{ route('user.contact.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-12 col-lg-6 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme-1"><i class="bi bi-person"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name', $auth['user']->first_name) }}" class="form-control @error('first_name') is-invalid @enderror" maxlength="50">
                                                        <label>First Name</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('first_name')
                                            <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-lg-6 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme-1"><i class="bi bi-person"></i></span>
                                                    <div class="form-floating">
                                                        <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name', $auth['user']->last_name) }}" class="form-control @error('last_name') is-invalid @enderror" maxlength="50">
                                                        <label>Last Name</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('last_name')
                                            <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme-1"><i class="bi bi-envelope"></i></span>
                                                    <div class="form-floating">
                                                        <input type="email" name="email" placeholder="Email Address" value="{{ old('email', $auth['user']->email) }}" class="form-control @error('email') is-invalid @enderror" maxlength="255">
                                                        <label>Email Address</label>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('email')
                                            <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12 col-md-12 mb-2">
                                            <div class="form-group mb-3 position-relative check-valid">
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text text-theme-1"><i class="bi bi-chat-right-text"></i></span>
                                                    <div class="form-floating">
                                                        <textarea name="message" placeholder="Tell us how we can help you..." class="form-control h-auto @error('message') is-invalid @enderror" rows="4" maxlength="1000">{{ old('message') }}</textarea>
                                                        <label>Your Message</label>
                                                    </div>
                                                </div>
                                            </div>

                                            @error('message')
                                            <div class="invalid-feedback d-block mb-3">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-lg w-100 btn-theme">
                                            <i class="bi bi-send me-2"></i>Send Message
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-xl-7">
                        <div class="card adminuiux-card border rounded-4 bg-theme-1-space position-relative overflow-hidden h-100">
                            <div class="card-body text-center position-relative z-index-1">
                                <img src="{{ asset('assets/img/investment/slider.png') }}" alt="" class="mw-100 mb-3">
                                <h4 class="text-white mb-3">Your Investment Success is Our Priority</h4>
                                <p class="text-white-50">Connect with our expert team for personalized investment guidance, account support, and answers to all your financial questions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Options -->
        <div class="row justify-content-center">
            <div class="col-12 col-md-4 col-lg-4">
                <div class="card adminuiux-card border rounded-4 mb-4">
                    <div class="card-body text-center">
                        <i class="bi bi-chat-right-dots avatar avatar-80 text-theme-1 bg-theme-1-subtle rounded h1 mb-4"></i><br>
                        <h5 class="text-theme-1">Live Chat Support</h5>
                        <p class="text-secondary mb-4">Connect instantly with our investment experts for real-time assistance with your portfolio and trading questions.</p>
                        <a href="mailto:{{ config('settings.site.email') }}" class="btn btn-theme">Start Live Chat</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <div class="card adminuiux-card border rounded-4 mb-4">
                    <div class="card-body text-center">
                        <i class="bi bi-life-preserver avatar avatar-80 text-theme-1 bg-theme-1-subtle rounded h1 mb-4"></i><br>
                        <h5 class="text-theme-1">Priority Support</h5>
                        <p class="text-secondary mb-4">Submit a detailed support ticket with documents or screenshots for complex investment inquiries and technical issues.</p>
                        <a href="mailto:{{ config('settings.site.email') }}" class="btn btn-theme">Create Support Ticket</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-4">
                <div class="card adminuiux-card border rounded-4 mb-4">
                    <div class="card-body text-center">
                        <i class="bi bi-person-video3 avatar avatar-80 text-theme-1 bg-theme-1-subtle rounded h1 mb-4"></i><br>
                        <h5 class="text-theme-1">Personal Consultation</h5>
                        <p class="text-secondary mb-4">Schedule a one-on-one video call with our investment advisors to discuss your financial goals and strategies.</p>
                        <a href="mailto:{{ config('settings.site.email') }}" class="btn btn-theme">Book Consultation</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-6 col-xl-4 text-md-end">
                <div class="mb-4">
                    <p class="h5 text-theme-1"><i class="bi bi-headset me-2"></i>Investment Support</p>
                    <p class="text-secondary">Need help with your investment portfolio, trading strategies, or account management? Our investment specialists are ready to assist you.</p>
                    <p class="mb-0"><strong>Email:</strong> <a href="mailto:{{ config('settings.site.email') }}" class="text-theme-1">{{ config('settings.site.email') }}</a></p>
                    <p><strong>Phone:</strong> <a href="tel:+1234567890" class="text-theme-1">{{ config('settings.site.phone') }}</a></p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-4">
                <div class="mb-4">
                    <p class="h5 text-theme-1"><i class="bi bi-gear me-2"></i>Technical Support</p>
                    <p class="text-secondary">Experiencing technical difficulties with the platform, mobile app, or having trouble accessing your account? We're here to help.</p>
                    <p class="mb-0"><strong>Email:</strong> <a href="mailto:{{ config('settings.site.tech.email') }}" class="text-theme-1">{{ config('settings.site.tech.email') }}</a></p>
                    <p><strong>Available:</strong> <span class="text-muted">24/7 Technical Support</span></p>
                </div>
            </div>
        </div>

        <!-- Response Time Notice -->
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="alert alert-info border-0 bg-theme-1-subtle">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock text-theme-1 me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1 text-theme-1">Response Times</h6>
                            <p class="mb-0 text-secondary small">
                                <strong>General Inquiries:</strong> Within 4-6 hours •
                                <strong>Investment Support:</strong> Within 2-4 hours •
                                <strong>Technical Issues:</strong> Within 1-2 hours
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
