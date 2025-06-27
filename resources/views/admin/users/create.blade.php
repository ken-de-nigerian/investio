@extends('layouts.auth')
@section('content')
    <div class="container mt-4 mb-5 position-relative">
        <!-- Scattered Star Icons -->
        <div class="position-absolute top-0 start-0 opacity-15" style="z-index: 0;">
            <i class="bi bi-star-fill text-primary" style="font-size: 1.2rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="top: 10%; right: 15%; z-index: 0;">
            <i class="bi bi-stars text-warning" style="font-size: 1.8rem;"></i>
        </div>

        <div class="position-absolute opacity-20" style="top: 20%; left: 5%; z-index: 0;">
            <i class="bi bi-star text-info" style="font-size: 1rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 35%; right: 5%; z-index: 0;">
            <i class="bi bi-star-fill text-success" style="font-size: 1.5rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="top: 50%; left: 2%; z-index: 0;">
            <i class="bi bi-stars text-purple" style="font-size: 1.3rem;"></i>
        </div>

        <div class="position-absolute opacity-20" style="top: 65%; right: 8%; z-index: 0;">
            <i class="bi bi-star text-primary" style="font-size: 1.1rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 80%; left: 10%; z-index: 0;">
            <i class="bi bi-star-fill text-warning" style="font-size: 1.4rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="bottom: 20%; right: 12%; z-index: 0;">
            <i class="bi bi-stars text-info" style="font-size: 1.6rem;"></i>
        </div>

        <div class="position-absolute opacity-25" style="bottom: 10%; left: 8%; z-index: 0;">
            <i class="bi bi-star text-success" style="font-size: 1rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 45%; right: 20%; z-index: 0;">
            <i class="bi bi-star-fill text-danger" style="font-size: 1.2rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="top: 60%; left: 15%; z-index: 0;">
            <i class="bi bi-stars text-primary" style="font-size: 1.7rem;"></i>
        </div>

        <div class="position-absolute opacity-20" style="bottom: 35%; right: 3%; z-index: 0;">
            <i class="bi bi-star text-warning" style="font-size: 1.3rem;"></i>
        </div>

        <div class="position-absolute opacity-15" style="top: 25%; left: 20%; z-index: 0;">
            <i class="bi bi-star-fill text-info" style="font-size: 1.1rem;"></i>
        </div>

        <div class="position-absolute opacity-10" style="bottom: 50%; left: 3%; z-index: 0;">
            <i class="bi bi-stars text-success" style="font-size: 1.5rem;"></i>
        </div>

        <div class="position-absolute opacity-25" style="top: 15%; right: 25%; z-index: 0;">
            <i class="bi bi-star text-purple" style="font-size: 1rem;"></i>
        </div>

        <div class="row justify-content-center min-vh-100">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 position-relative" style="z-index: 1;">
                <div class="text-center mb-4 mt-4">
                    <h2 class="text-primary fw-bold">Create User</h2>
                    <p class="text-muted">Complete this user's profile to get started</p>
                    <a class="d-inline-flex w-auto mx-auto style-none" href="{{ route('admin.users') }}">
                        <img data-bs-img="light" src="{{ asset('assets/img/logo-light.svg') }}" alt="" class="mx-3">
                        <img data-bs-img="dark" src="{{ asset('assets/img/logo.svg') }}" alt="" class="mx-3">
                        <div>
                            <p class="h5 mb-0">Investment<b>UX</b></p>
                            <p class="text-secondary small">HTML template</p>
                        </div>
                    </a>
                </div>

                <!-- Step Indicator -->
                <div class="row mb-4">
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-center position-relative">
                            <div class="progress position-absolute w-100" style="height: 2px; z-index: 1;">
                                <div class="progress-bar bg-primary" id="progressBar" role="progressbar" style="width: 20%"></div>
                            </div>

                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center position-relative"
                                 style="width: 40px; height: 40px; z-index: 2;" id="step1">
                                <i class="bi bi-person"></i>
                            </div>

                            <div class="bg-primary border border-2 rounded-circle d-flex align-items-center justify-content-center position-relative"
                                 style="width: 40px; height: 40px; z-index: 2;" id="step2">
                                <i class="bi bi-card-text text-muted"></i>
                            </div>

                            <div class="bg-primary border border-2 rounded-circle d-flex align-items-center justify-content-center position-relative"
                                 style="width: 40px; height: 40px; z-index: 2;" id="step3">
                                <i class="bi bi-geo-alt text-muted"></i>
                            </div>

                            <div class="bg-primary border border-2 rounded-circle d-flex align-items-center justify-content-center position-relative"
                                 style="width: 40px; height: 40px; z-index: 2;" id="step4">
                                <i class="bi bi-briefcase text-muted"></i>
                            </div>

                            <div class="bg-primary border border-2 rounded-circle d-flex align-items-center justify-content-center position-relative"
                                 style="width: 40px; height: 40px; z-index: 2;" id="step5">
                                <i class="bi bi-eye text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="card adminuiux-card border rounded-4 position-relative" style="z-index: 2;">
                    <div class="card-body z-index-1 p-4">
                        <form id="onboardingForm" method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="current_step" id="currentStepInput" value="1">

                            <!-- Step 1: Basic Information -->
                            <div class="step-content" id="stepContent1">
                                <div class="text-center mb-4">
                                    <h4 class="text-primary fw-bold">Basic Information</h4>
                                    <p class="text-muted">Let's start with your basic details</p>
                                </div>

                                <!-- Avatar Upload -->
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block" style="width: 120px; height: 120px;">
                                        <div class="rounded-circle overflow-hidden w-100 h-100 border border-2 border-light shadow-sm" style="cursor: pointer;" onclick="document.getElementById('avatar').click()">
                                            <img id="avatarPreview" class="w-100 h-100" style="object-fit: cover;" alt="Profile" src="{{ asset('assets/img/default.png')  }}">
                                        </div>

                                        <button type="button" class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle p-2" onclick="document.getElementById('avatar').click()" style="transform: translate(10%, 10%);">
                                            <i class="bi bi-camera"></i>
                                        </button>

                                        <input type="file" id="avatar" name="avatar" accept="image/*" class="d-none">
                                        @error('avatar')
                                        <div class="invalid-feedback d-block text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mt-2">
                                        <small class="text-muted">Upload profile picture (optional, max 2MB)</small>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-4 @error('first_name') is-invalid @enderror" id="firstName" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" autofocus required>
                                            <label for="firstName">First Name</label>
                                            <div class="invalid-feedback">Please provide your first name.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-4 @error('last_name') is-invalid @enderror" id="lastName" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" required>
                                            <label for="lastName">Last Name</label>
                                            <div class="invalid-feedback">Please provide your last name.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="email" class="form-control rounded-4 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                                            <label for="email">Email Address</label>
                                            <div class="invalid-feedback">Please provide a valid email address.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="tel" class="form-control rounded-4 @error('phone_number') is-invalid @enderror" id="phoneNumber" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}" required>
                                            <label for="phoneNumber">Phone Number</label>
                                            <div class="invalid-feedback">Please provide a valid phone number.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Account & Personal Details -->
                            <div class="step-content d-none" id="stepContent2">
                                <div class="text-center mb-4">
                                    <h4 class="text-primary fw-bold">Account & Personal Details</h4>
                                    <p class="text-muted">Tell us more about yourself</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select rounded-4 @error('account_type') is-invalid @enderror" id="accountType" name="account_type" required>
                                                <option value="">Select Account Type</option>
                                                <option value="Savings" {{ old('account_type', $user->profile->account_type ?? '') == 'Savings' ? 'selected' : '' }}>Savings Account</option>
                                                <option value="Current" {{ old('account_type', $user->profile->account_type ?? '') == 'Current' ? 'selected' : '' }}>Current Account</option>
                                                <option value="Checking" {{ old('account_type', $user->profile->account_type ?? '') == 'Checking' ? 'selected' : '' }}>Checking Account</option>
                                                <option value="Fixed Deposit" {{ old('account_type', $user->profile->account_type ?? '') == 'Fixed Deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                                <option value="Non Resident" {{ old('account_type', $user->profile->account_type ?? '') == 'Non Resident' ? 'selected' : '' }}>Non Resident Account</option>
                                                <option value="Online Banking" {{ old('account_type', $user->profile->account_type ?? '') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                                <option value="Domiciliary Account" {{ old('account_type', $user->profile->account_type ?? '') == 'Domiciliary Account' ? 'selected' : '' }}>Domiciliary Account</option>
                                                <option value="Joint Account" {{ old('account_type', $user->profile->account_type ?? '') == 'Joint Account' ? 'selected' : '' }}>Joint Account</option>
                                            </select>
                                            <label for="accountType">Account Type</label>
                                            <div class="invalid-feedback">Please select an account type.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="date" class="form-control rounded-4 @error('date_of_birth') is-invalid @enderror" id="dateOfBirth" name="date_of_birth" value="{{ old('date_of_birth', $user->profile->date_of_birth ?? '') }}" max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                            <label for="dateOfBirth">Date of Birth</label>
                                            <div class="invalid-feedback">Please provide your date of birth (must be 18+).</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select rounded-4 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender', $user->profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $user->profile->account_type ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            <label for="gender">Gender</label>
                                            <div class="invalid-feedback">Please select your gender.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select rounded-4 @error('marital_status') is-invalid @enderror" id="maritalStatus" name="marital_status">
                                                <option value="">Select Marital Status</option>
                                                <option value="single" {{ old('marital_status', $user->profile->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                                <option value="married" {{ old('marital_status', $user->profile->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                            </select>
                                            <label for="maritalStatus">Marital Status</label>
                                            <div class="invalid-feedback">Please select your marital status.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Location Information -->
                            <div class="step-content d-none" id="stepContent3">
                                <div class="text-center mb-4">
                                    <h4 class="text-primary fw-bold">Location Information</h4>
                                    <p class="text-muted">Where are you located?</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select rounded-4 @error('country') is-invalid @enderror" id="country" name="country">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ Str::snake($country['name']) }}"
                                                        {{ old('country', $user->profile->country ?? '') == Str::snake($country['name']) ? 'selected' : '' }}>
                                                        {{ $country['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="country">Country</label>
                                            <div class="invalid-feedback">Please select your country.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-4 @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $user->profile->state ?? '') }}">
                                            <label for="state">State/Province</label>
                                            <div class="invalid-feedback">Please provide your state or province.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-4 @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $user->profile->city ?? '') }}">
                                            <label for="city">City</label>
                                            <div class="invalid-feedback">Please provide your city.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control rounded-4 @error('address') is-invalid @enderror" id="address" name="address" style="height: 100px">{{ old('address', $user->profile->address ?? '') }}</textarea>
                                            <label for="address">Street Address</label>
                                            <div class="invalid-feedback">Please provide your address.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Professional & Security -->
                            <div class="step-content d-none" id="stepContent4">
                                <div class="text-center mb-4">
                                    <h4 class="text-primary fw-bold">Professional & Security</h4>
                                    <p class="text-muted">Final details for your profile</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-4 @error('occupation') is-invalid @enderror" id="occupation" name="occupation" value="{{ old('occupation', $user->profile->occupation ?? '') }}">
                                            <label for="occupation">Occupation</label>
                                            <div class="invalid-feedback">Please provide your occupation.</div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-4 @error('social_security_number') is-invalid @enderror" id="socialSecurityNumber" name="social_security_number" value="{{ old('social_security_number', $user->profile->social_security_number ?? '') }}">
                                            <label for="socialSecurityNumber">Social Security Number</label>
                                            <div class="invalid-feedback">Please provide your social security number.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control rounded-4 @error('password') is-invalid @enderror" id="password" name="password" required>
                                            <label for="password">Password</label>
                                            <div class="form-text">Enter a secure password (minimum 8 characters)</div>
                                            <div class="invalid-feedback">Password must be at least 8 characters long.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 5: Preview -->
                            <div class="step-content d-none" id="stepContent5">
                                <div class="text-center mb-4">
                                    <h4 class="text-primary fw-bold">Review Your Information</h4>
                                    <p class="text-muted">Please verify all details before submitting</p>
                                </div>

                                <div class="card border rounded-4">
                                    <div class="card-body">
                                        <!-- Profile Picture Preview -->
                                        <div class="text-center mb-4">
                                            <div class="d-inline-block">
                                                <img id="previewAvatar" class="rounded-circle border border-2 border-light shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" alt="Profile Preview" src="{{ asset('assets/img/default.png')  }}">
                                            </div>

                                            <div class="mt-2">
                                                <small class="text-muted">Profile Picture</small>
                                            </div>
                                        </div>

                                        <!-- Basic Information -->
                                        <h5 class="text-primary fw-medium mb-3">Basic Information</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <p><strong>First Name:</strong> <span id="previewFirstName"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Last Name:</strong> <span id="previewLastName"></span></p>
                                            </div>
                                            <div class="col-12">
                                                <p><strong>Email:</strong> <span id="previewEmail"></span></p>
                                            </div>
                                            <div class="col-12">
                                                <p><strong>Phone Number:</strong> <span id="previewPhoneNumber"></span></p>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Account & Personal Details -->
                                        <h5 class="text-primary fw-medium mb-3">Account & Personal Details</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <p><strong>Account Type:</strong> <span id="previewAccountType"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Date of Birth:</strong> <span id="previewDateOfBirth"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Gender:</strong> <span id="previewGender"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Marital Status:</strong> <span id="previewMaritalStatus"></span></p>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Location Information -->
                                        <h5 class="text-primary fw-medium mb-3">Location Information</h5>
                                        <div class="row g-3 mb-4">
                                            <div class="col-12">
                                                <p><strong>Country:</strong> <span id="previewCountry"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>State/Province:</strong> <span id="previewState"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>City:</strong> <span id="previewCity"></span></p>
                                            </div>
                                            <div class="col-12">
                                                <p><strong>Address:</strong> <span id="previewAddress"></span></p>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Professional & Security -->
                                        <h5 class="text-primary fw-medium mb-3">Professional & Security</h5>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <p><strong>Occupation:</strong> <span id="previewOccupation"></span></p>
                                            </div>

                                            <div class="col-12">
                                                <p><strong>Social Security Number:</strong> <span id="previewSocialSecurityNumber"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top" id="navigationButtons">
                            <button type="button" class="btn btn-outline-secondary d-none rounded-4" id="prevBtn">
                                <i class="bi bi-arrow-left me-2"></i>Previous
                            </button>

                            <div class="flex-grow-1"></div>

                            <button type="button" class="btn btn-primary rounded-4" id="nextBtn">
                                Next<i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        class OnboardingForm {
            constructor() {
                this.currentStep = 1;
                this.totalSteps = 5;
                this.formData = {};
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.updateUI();
            }

            setupEventListeners() {
                // Navigation buttons
                document.getElementById('nextBtn').addEventListener('click', () => this.nextStep());
                document.getElementById('prevBtn').addEventListener('click', () => this.prevStep());

                // Avatar upload
                document.getElementById('avatar').addEventListener('change', (e) => this.handleAvatarUpload(e));

                // Form validation on blur
                document.querySelectorAll('input, select, textarea').forEach(field => {
                    field.addEventListener('blur', () => this.validateField(field));
                    field.addEventListener('input', () => {
                        if (field.classList.contains('is-invalid')) {
                            this.validateField(field);
                        }
                    });
                });
            }

            handleAvatarUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    // Validate file size (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const preview = document.getElementById('avatarPreview');
                        const previewAvatar = document.getElementById('previewAvatar');

                        preview.src = e.target.result;
                        previewAvatar.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }

            validateField(field) {
                const value = field.value.trim();
                let isValid = true;

                // Remove existing validation classes
                field.classList.remove('is-valid', 'is-invalid');

                // Skip validation if field is not and empty
                if (!field.hasAttribute('required') && !value) {
                    return true;
                }

                // field validation
                if (field.hasAttribute('required')) {
                    if (!value) {
                        isValid = false;
                    }
                }

                // Email validation
                if (field.type === 'email' && value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                    }
                }

                // Phone validation
                if (field.id === 'phoneNumber' && value) {
                    // Remove all non-digit characters
                    const digitsOnly = value.replace(/\D/g, '');
                    if (digitsOnly.length < 10) {
                        isValid = false;
                    }
                }

                // Date of birth validation (must be 18+)
                if (field.id === 'dateOfBirth' && value) {
                    const today = new Date();
                    const birthDate = new Date(value);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    if (age < 18) {
                        isValid = false;
                    }
                }

                // Select validation
                if (field.tagName === 'SELECT' && field.hasAttribute('required')) {
                    if (!value) {
                        isValid = false;
                    }
                }

                // Textarea validation
                if (field.tagName === 'TEXTAREA' && field.hasAttribute('required')) {
                    if (!value) {
                        isValid = false;
                    }
                }

                // Apply validation classes
                if (isValid) {
                    if (value) field.classList.add('is-valid');
                } else {
                    field.classList.add('is-invalid');
                }

                return isValid;
            }

            validateCurrentStep() {
                const currentStepElement = document.getElementById(`stepContent${this.currentStep}`);
                const fields = currentStepElement.querySelectorAll('input, select, textarea');
                let isValid = true;

                fields.forEach(field => {
                    if (!this.validateField(field)) {
                        isValid = false;
                    }
                });

                return isValid;
            }

            collectStepData() {
                const currentStepElement = document.getElementById(`stepContent${this.currentStep}`);
                const fields = currentStepElement.querySelectorAll('input, select, textarea');

                fields.forEach(field => {
                    if (field.type === 'file') {
                        this.formData[field.name] = field.files[0] || null;
                    } else {
                        this.formData[field.name] = field.value;
                    }
                });
            }

            populatePreview() {
                // Basic Information
                document.getElementById('previewFirstName').textContent = this.formData.first_name || 'N/A';
                document.getElementById('previewLastName').textContent = this.formData.last_name || 'N/A';
                document.getElementById('previewEmail').textContent = this.formData.email || 'N/A';
                document.getElementById('previewPhoneNumber').textContent = this.formData.phone_number || 'N/A';

                // Account & Personal Details
                document.getElementById('previewAccountType').textContent = this.formData.account_type || 'N/A';
                document.getElementById('previewDateOfBirth').textContent = this.formData.date_of_birth || 'N/A';
                document.getElementById('previewGender').textContent = this.formData.gender ? this.formData.gender.charAt(0).toUpperCase() + this.formData.gender.slice(1) : 'N/A';
                document.getElementById('previewMaritalStatus').textContent = this.formData.marital_status ? this.formData.marital_status.charAt(0).toUpperCase() + this.formData.marital_status.slice(1) : 'N/A';

                // Location Information
                document.getElementById('previewCountry').textContent = this.formData.country ? this.formData.country.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : 'N/A';
                document.getElementById('previewState').textContent = this.formData.state || 'N/A';
                document.getElementById('previewCity').textContent = this.formData.city || 'N/A';
                document.getElementById('previewAddress').textContent = this.formData.address || 'N/A';

                // Professional & Security
                document.getElementById('previewOccupation').textContent = this.formData.occupation || 'N/A';
                document.getElementById('previewSocialSecurityNumber').textContent = this.formData.social_security_number || 'N/A';
            }

            nextStep() {
                if (this.currentStep < this.totalSteps) {
                    if (!this.validateCurrentStep()) {
                        // Focus on first invalid field
                        const firstInvalid = document.querySelector(`#stepContent${this.currentStep} .is-invalid`);
                        if (firstInvalid) {
                            firstInvalid.focus();
                        }
                        return;
                    }

                    this.collectStepData();
                    this.currentStep++;

                    if (this.currentStep === this.totalSteps) {
                        this.populatePreview();
                    }
                    this.updateUI();
                } else if (this.currentStep === this.totalSteps) {
                    this.submitForm();
                }
            }

            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    this.updateUI();
                }
            }

            updateUI() {
                // Update step content visibility
                document.querySelectorAll('.step-content').forEach((content, index) => {
                    content.classList.toggle('d-none', index + 1 !== this.currentStep);
                });

                // Update step indicators
                this.updateStepIndicators();

                // Update progress bar
                const progress = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
                document.getElementById('progressBar').style.width = progress + '%';

                // Update navigation buttons
                this.updateNavigationButtons();
            }

            updateStepIndicators() {
                for (let i = 1; i <= this.totalSteps; i++) {
                    const stepElement = document.getElementById(`step${i}`);
                    const icon = stepElement.querySelector('i');

                    // Reset classes
                    stepElement.className = 'rounded-circle d-flex align-items-center justify-content-center position-relative';
                    stepElement.style.cssText = 'width: 40px; height: 40px; z-index: 2;';

                    if (i < this.currentStep) {
                        // Completed step
                        stepElement.classList.add('bg-success', 'text-white');
                        icon.className = 'bi bi-check-lg';
                    } else if (i === this.currentStep) {
                        // Current step
                        stepElement.classList.add('bg-primary', 'text-white');
                        // Keep original icon
                        switch(i) {
                            case 1: icon.className = 'bi bi-person'; break;
                            case 2: icon.className = 'bi bi-card-text'; break;
                            case 3: icon.className = 'bi bi-geo-alt'; break;
                            case 4: icon.className = 'bi bi-briefcase'; break;
                            case 5: icon.className = 'bi bi-eye'; break;
                        }
                    } else {
                        // Future step
                        stepElement.classList.add('bg-primary', 'border', 'border-2');
                        icon.classList.add('text-white');
                        // Keep original icon
                        switch(i) {
                            case 1: icon.className = 'bi bi-person text-white'; break;
                            case 2: icon.className = 'bi bi-card-text text-white'; break;
                            case 3: icon.className = 'bi bi-geo-alt text-white'; break;
                            case 4: icon.className = 'bi bi-briefcase text-white'; break;
                            case 5: icon.className = 'bi bi-eye text-white'; break;
                        }
                    }
                }
            }

            updateNavigationButtons() {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const navButtons = document.getElementById('navigationButtons');

                // Show/hide previous button
                prevBtn.classList.toggle('d-none', this.currentStep === 1);

                // Update next button text
                navButtons.classList.remove('d-none');
                if (this.currentStep === this.totalSteps) {
                    nextBtn.innerHTML = '<i class="bi bi-check me-2"></i>Submit';
                } else if (this.currentStep === this.totalSteps - 1) {
                    nextBtn.innerHTML = '<i class="bi bi-eye me-2"></i>Preview';
                } else {
                    nextBtn.innerHTML = 'Next<i class="bi bi-arrow-right ms-2"></i>';
                }
            }

            submitForm() {
                const form = document.getElementById('onboardingForm');
                const formData = new FormData(form);

                // Add any additional data from this.formData that might not be in the form
                for (const [key, value] of Object.entries(this.formData)) {
                    if (value && key !== 'avatar') {
                        formData.set(key, value);
                    }
                }

                const showError = (message) => {
                    iziToast.error({...iziToastSettings, message});
                };

                const showSuccess = (message) => {
                    iziToast.success({...iziToastSettings, message});
                };

                // Show loading state
                const submitBtn = document.getElementById('nextBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

                // Actual form submission
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess(data.message || "Form submitted successfully");
                            setTimeout(() => window.location.href = data.redirect, 3000);
                        } else {
                            const errors = Array.isArray(data.message) ? data.message : [data.message];
                            errors.forEach(showError);
                        }
                    })
                    .catch(error => {
                        console.error("Form submit error:", error);
                        showError(error || "There was an error submitting your information. Please try again.");
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-check me-2"></i>Submit';
                    });
            }
        }

        // Initialize the form when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new OnboardingForm();

            const phoneNumberInput = document.getElementById('phoneNumber');
            if (phoneNumberInput && typeof Cleave !== 'undefined') {
                new Cleave('#phoneNumber', {
                    numericOnly: true,
                    blocks: [0, 3, 0, 4, 4],
                    delimiters: ['(', ')', ' ', '-', ' '],
                    maxLength: 16
                });
            }
        });
    </script>
@endpush
