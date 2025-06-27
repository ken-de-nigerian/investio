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
                        <li class="breadcrumb-item active bi" aria-current="page">Profile Kyc</li>
                    </ol>
                </nav>
                <h5>Profile Kyc</h5>
            </div>
        </div>

        <div id="submit-kyc-form">
            <div class="card adminuiux-card overflow-hidden mb-4 border rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Basic Details</h5>
                    <p class="mb-4 text-secondary">
                        Please enter your details carefully and ensure the information matches exactly with your identity proof.
                        <br>You won't be able to edit these details after submission.
                    </p>

                    <div class="row mb-2">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="first_name" type="text" class="form-control rounded-4 @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $auth['user'] ? $auth['user']->first_name : '') }}" autocomplete="given-name" autofocus>
                                <div class="invalid-feedback">Firstname is required.</div>
                                <label for="first_name">First Name</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="last_name" type="text" class="form-control rounded-4 @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $auth['user'] ? $auth['user']->last_name : '') }}" autocomplete="family-name">
                                <div class="invalid-feedback">Latname is required.</div>
                                <label for="last_name">Last Name</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="email" type="email" class="form-control rounded-4 @error('last_name') is-invalid @enderror" value="{{ old('email', $auth['user'] ? $auth['user']->email : '') }}" autocomplete="email">
                                <div class="invalid-feedback">Email address is required.</div>
                                <label for="email">Email Address</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="phone_number" type="tel" class="form-control rounded-4 @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $auth['user'] ? $auth['user']->phone_number ?? '' : '') }}" autocomplete="tel">
                                <div class="invalid-feedback">Phone number is required.</div>
                                <label for="phone_number">Phone Number</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="date_of_birth" type="date" class="form-control rounded-4 @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->date_of_birth : '') }}" autocomplete="bday">
                                <div class="invalid-feedback">Date of birth is required.</div>
                                <label for="date_of_birth">Date of Birth</label>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-4">
                    <h6 class="mb-2">Upload Supporting Documents</h6>
                    <p class="mb-4 text-secondary small">
                        To avoid delays, please ensure you upload valid documents that are not expired, clearly visible, and free from glare.
                        <br>Select your proof type and upload the document.
                    </p>

                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-4 mb-4">
                            <label class="card h-100 selectable-doc-type border rounded-4">
                                <input type="radio" name="id_proof_type" value="passport" class="d-none" {{ old('id_proof_type') == 'passport' ? 'checked' : '' }}>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 rounded bg-theme-1-subtle text-theme-1">
                                                <i class="bi bi-person-vcard h5"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-theme-1 mb-1">Passport</h6>
                                            <p class="opacity-50 small">Upload passport photos</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="col-12 col-md-4 col-lg-4 mb-4">
                            <label class="card h-100 selectable-doc-type border rounded-4">
                                <input type="radio" name="id_proof_type" value="national_id" class="d-none" {{ old('id_proof_type') == 'national_id' ? 'checked' : '' }}>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 rounded bg-theme-1-subtle text-theme-1">
                                                <i class="bi bi-person-badge h5"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-theme-1 mb-1">National ID</h6>
                                            <p class="opacity-50 small">Upload ID photos</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="col-12 col-md-4 col-lg-4 mb-4">
                            <label class="card h-100 selectable-doc-type border rounded-4">
                                <input type="radio" name="id_proof_type" value="driving_license" class="d-none" {{ old('id_proof_type') == 'driving_license' ? 'checked' : '' }}>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 rounded bg-theme-1-subtle text-theme-1">
                                                <i class="bi bi-car-front h5"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-theme-1 mb-1">Driving License</h6>
                                            <p class="opacity-50 small">Upload DL photos</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <p class="mb-3">Front-side of proof</p>
                            <form action="{{ route('user.kyc.process.image') }}" method="post" enctype="multipart/form-data" class="dropzone rounded-4 mb-2" id="myDropzone1">
                                @csrf
                                <input type="hidden" name="upload_type" value="id_front_proof">
                                <div class="dz-default dz-message my-5">
                                    <i class="h1 bi bi-cloud-upload"></i><br>
                                    <button class="dz-button" type="button">Drag and Drop or Click here to upload</button>
                                </div>
                            </form>
                            <p class="text-secondary small">Upload only .jpeg, .jpg, .png format max. file size 2MB</p>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6">
                            <p class="mb-3">Back-side of proof</p>
                            <form action="{{ route('user.kyc.process.image') }}" method="post" enctype="multipart/form-data" class="dropzone rounded-4 mb-2" id="myDropzone2">
                                @csrf
                                <input type="hidden" name="upload_type" value="id_back_proof">
                                <div class="dz-default dz-message my-5">
                                    <i class="h1 bi bi-cloud-upload"></i><br>
                                    <button class="dz-button" type="button">Drag and Drop or Click here to upload</button>
                                </div>
                            </form>
                            <p class="text-secondary small">Upload only .jpeg, .jpg, .png format max. file size 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card adminuiux-card overflow-hidden mb-4 border rounded-4">
                <div class="card-body">
                    <h5 class="mb-3">Address Details</h5>
                    <p class="mb-4 text-secondary">
                        Please enter your address details carefully and ensure they match exactly with your address proof.
                        <br>You won't be able to edit these details after submission.
                    </p>

                    <div class="row mb-2">
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <select class="form-select rounded-4 @error('country') is-invalid @enderror" id="country" name="country">
                                    <option value="" disabled {{ old('country', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->country : '') == '' ? 'selected' : '' }}>Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ Str::snake($country['name']) }}"
                                            {{ old('country', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->country : '') == Str::snake($country['name']) ? 'selected' : '' }}>
                                            {{ $country['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Country is required.</div>
                                <label for="country">Country</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="state" type="text" class="form-control rounded-4 @error('state') is-invalid @enderror" name="state" value="{{ old('state', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->state : '') }}" autocomplete="address-level1">
                                <div class="invalid-feedback">State is required.</div>
                                <label for="state">State</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="form-floating mb-3">
                                <input id="city" type="text" class="form-control rounded-4 @error('city') is-invalid @enderror" name="city" value="{{ old('city', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->city : '') }}" autocomplete="address-level2">
                                <div class="invalid-feedback">City is required.</div>
                                <label for="city">City</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-12">
                            <textarea id="address" rows="4" class="form-control rounded-4 @error('address') is-invalid @enderror" name="address" autocomplete="street-address" placeholder="Address">{{ old('address', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->address : '') }}</textarea>
                            <div class="invalid-feedback">Address field is required.</div>
                        </div>
                    </div>

                    <hr class="mb-4">
                    <h6 class="mb-2">Upload Supportive Document Type</h6>
                    <p class="mb-4 text-secondary small">To avoid delay, make sure you upload valid document which is not expired, clearly visible and not with light glare.
                        <br>Select proof type and upload document.
                    </p>

                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-4 mb-4">
                            <label class="card h-100 selectable-doc-type border rounded-4">
                                <input type="radio" name="address_proof_type" value="passport" class="d-none" {{ old('address_proof_type') == 'passport' ? 'checked' : '' }}>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 rounded bg-theme-1-subtle text-theme-1">
                                                <i class="bi bi-person-vcard h5"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-theme-1 mb-1">Passport</h6>
                                            <p class="opacity-50 small">Upload passport photos</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="col-12 col-md-4 col-lg-4 mb-4">
                            <label class="card h-100 selectable-doc-type border rounded-4">
                                <input type="radio" name="address_proof_type" value="electricity_bill" class="d-none" {{ old('address_proof_type') == 'electricity_bill' ? 'checked' : '' }}>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 rounded bg-theme-1-subtle text-theme-1">
                                                <i class="bi bi-lightning-charge h5"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-theme-1 mb-1">Electricity Bill</h6>
                                            <p class="opacity-50 small">Upload bill copy</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="col-12 col-md-4 col-lg-4 mb-4">
                            <label class="card h-100 selectable-doc-type border rounded-4">
                                <input type="radio" name="address_proof_type" value="gas_bill" class="d-none" {{ old('address_proof_type') == 'gas_bill' ? 'checked' : '' }}>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar avatar-50 rounded bg-theme-1-subtle text-theme-1">
                                                <i class="bi bi-fuel-pump h5"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="text-theme-1 mb-1">Gas Bill</h6>
                                            <p class="opacity-50 small">Upload bill copy</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <p class="mb-3">Front-side of proof</p>
                            <form action="{{ route('user.kyc.process.image') }}" method="post" enctype="multipart/form-data" class="dropzone rounded-4 mb-2" id="myDropzone3">
                                @csrf
                                <input type="hidden" name="upload_type" value="address_front_proof">
                                <div class="dz-default dz-message my-5">
                                    <i class="h1 bi bi-cloud-upload"></i><br>
                                    <button class="dz-button" type="button">Drag and Drop or Click here to upload</button>
                                </div>
                            </form>
                            <p class="text-secondary small">Upload only .jpeg, .jpg, .png format max. file size 2MB</p>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6">
                            <p class="mb-3">Back-side of proof</p>
                            <form action="{{ route('user.kyc.process.image') }}" method="post" enctype="multipart/form-data" class="dropzone rounded-4 mb-2" id="myDropzone4">
                                @csrf
                                <input type="hidden" name="upload_type" value="address_back_proof">
                                <div class="dz-default dz-message my-5">
                                    <i class="h1 bi bi-cloud-upload"></i><br>
                                    <button class="dz-button" type="button">Drag and Drop or Click here to upload</button>
                                </div>
                            </form>
                            <p class="text-secondary small">Upload only .jpeg, .jpg, .png format max. file size 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked="">
                <label class="form-check-label" for="flexCheckChecked">
                    I confirm that all the personal and address details I have provided are correct.
                </label>
            </div>

            <div class="row mb-4">
                <div class="col-auto">
                    <button class="btn btn-theme" id="submitKyc">Submit KYC</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const showError = (message) => {
                iziToast.error({ ...iziToastSettings, message });
            };

            const showSuccess = (message) => {
                iziToast.success({ ...iziToastSettings, message });
            };

            // Document type card selection
            document.querySelectorAll('.selectable-doc-type').forEach(item => {
                item.addEventListener('click', function () {
                    this.closest('.row').querySelectorAll('.selectable-doc-type').forEach(el => {
                        el.querySelector('.card-body').classList.remove('border', 'rounded-4');
                    });

                    this.querySelector('.card-body').classList.add('border', 'rounded-4');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });

            const submitKyc = document.getElementById('submitKyc');
            if (submitKyc) {
                submitKyc.addEventListener('click', function (e) {
                    e.preventDefault();

                    submitKyc.innerHTML = '<span class="spinner spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...';
                    submitKyc.disabled = true;

                    const firstName = document.getElementById('first_name').value.trim();
                    const lastName = document.getElementById('last_name').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const phoneNumber = document.getElementById('phone_number').value.trim();
                    const dateOfBirth = document.getElementById('date_of_birth').value.trim();
                    const idProofType = document.querySelector('input[name="id_proof_type"]:checked');
                    const addressProofType = document.querySelector('input[name="address_proof_type"]:checked');
                    const country = document.getElementById('country').value.trim();
                    const state = document.getElementById('state').value.trim();
                    const city = document.getElementById('city').value.trim();
                    const address = document.getElementById('address').value.trim();

                    let isValid = true;
                    const fields = [
                        { id: 'first_name', value: firstName, name: 'First Name' },
                        { id: 'last_name', value: lastName, name: 'Last Name' },
                        { id: 'email', value: email, name: 'Email' },
                        { id: 'phone_number', value: phoneNumber, name: 'Phone Number' },
                        { id: 'date_of_birth', value: dateOfBirth, name: 'Date of Birth' },
                        { id: 'country', value: country, name: 'Country' },
                        { id: 'state', value: state, name: 'State' },
                        { id: 'city', value: city, name: 'City' },
                        { id: 'address', value: address, name: 'Address' }
                    ];

                    fields.forEach(field => {
                        const element = document.getElementById(field.id);
                        if (field.value === '') {
                            element.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            element.classList.remove('is-invalid');
                        }
                    });

                    if (!idProofType) {
                        isValid = false;
                        showError('Please select an ID proof type');
                    }

                    if (!addressProofType) {
                        isValid = false;
                        showError('Please select an address proof type');
                    }

                    if (!isValid) {
                        submitKyc.innerHTML = 'Submit KYC';
                        submitKyc.disabled = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('first_name', firstName);
                    formData.append('last_name', lastName);
                    formData.append('email', email);
                    formData.append('phone_number', phoneNumber);
                    formData.append('date_of_birth', dateOfBirth);
                    formData.append('id_proof_type', idProofType.value);
                    formData.append('address_proof_type', addressProofType.value);
                    formData.append('country', country);
                    formData.append('state', state);
                    formData.append('city', city);
                    formData.append('address', address);

                    fetch('{{ route("user.kyc.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showSuccess(data.message || 'KYC submitted successfully!');
                                setTimeout(() => window.location.href = data.redirect, 3000);
                            } else {
                                showError(data.message || 'Error submitting KYC');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('An error occurred while submitting KYC');
                        })
                        .finally(() => {
                            submitKyc.innerHTML = 'Submit KYC';
                            submitKyc.disabled = false;
                        });
                });

                // UX: clear invalid on typing
                document.querySelectorAll('input, select').forEach(input => {
                    input.addEventListener('input', function () {
                        this.classList.remove('is-invalid');
                    });
                });
            }

            const phoneNumberInput = document.getElementById('phone_number');
            if (phoneNumberInput && typeof Cleave !== 'undefined') {
                new Cleave('#phone_number', {
                    numericOnly: true,
                    blocks: [0, 3, 0, 4, 4],
                    delimiters: ['(', ')', ' ', '-', ' '],
                    maxLength: 16
                });
            }
        });
    </script>
@endpush
