@extends('layouts.admin')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <!-- cover -->
        <div class="card adminuiux-card overflow-hidden mb-4 pt-5 rounded-4">
            <figure class="start-0 top-0 w-100 h-100 z-index-0 position-absolute overlay-gradiant"></figure>
            <div class="card-body text-center text-white z-index-1">
                <div class="d-inline-block position-relative w-auto mx-auto my-3">
                    <figure class="avatar avatar-150 rounded-circle hover">
                        <img src="{{ $user->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}" alt="Avatar" id="profile-image-preview" class="object-fit-cover" loading="lazy">
                    </figure>

                    @if (!$user->avatar)
                        <!-- Upload button -->
                        <div class="position-absolute bottom-0 end-0 z-index-1 h-auto">
                            <form id="update-profile-picture-form" action="{{ route('admin.users.picture.update', $user->id) }}" method="POST" enctype="multipart/form-data" style="display: inline;">
                                @csrf
                                <button type="button" class="btn btn-lg btn-theme btn-square rounded-pill" onclick="document.getElementById('profile-image-input').click()">
                                    <i class="bi bi-camera"></i>
                                </button>
                                <input type="file" name="profile_image" id="profile-image-input" accept="image/png, image/jpeg" style="display: none;">
                            </form>
                        </div>
                    @else
                        <!-- Delete button -->
                        <div class="position-absolute bottom-0 end-0 z-index-1 h-auto">
                            <form id="remove-profile-picture-form" action="{{ route('admin.users.picture.remove', $user->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="delete-profile-picture" class="btn btn-lg btn-danger btn-square rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="opacity-75 mb-3">{{ $user->email }}</p>
            </div>
        </div>

        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="file-error" style="display: none;">
            <span id="file-error-message"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- settings -->
        <div class="row">
            <div class="col-12 col-md-4 col-lg-4 col-xl-3">
                <div class="position-sticky" style="top:5.5rem">
                    <div class="card adminuiux-card mb-4 border rounded-4">
                        <div class="card-body">
                            <ul class="nav nav-pills adminuiux-nav-pills flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->query('tab', 'personal') == 'personal' ? 'active' : '' }}"
                                       href="{{ route('admin.users.edit', ['user' => $user->id, 'tab' => 'personal']) }}">
                                        <div class="avatar avatar-28 icon"><i class="bi bi-people fs-4"></i></div>
                                        <div class="col">
                                            <p class="h6 mb-0">Personal Profile</p>
                                            <p class="small opacity-75">Fill out your personal & contact information.</p>
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->query('tab', 'personal') == 'account' ? 'active' : '' }}"
                                       href="{{ route('admin.users.edit', ['user' => $user->id, 'tab' => 'account']) }}">
                                        <div class="avatar avatar-28 icon"><i class="bi bi-file-person fs-4"></i></div>
                                        <div class="col">
                                            <p class="h6 mb-0">Account Details</p>
                                            <p class="small opacity-75">Fill out your account information.</p>
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->query('tab', 'personal') == 'cards' ? 'active' : '' }}"
                                       href="{{ route('admin.users.edit', ['user' => $user->id, 'tab' => 'cards']) }}">
                                        <div class="avatar avatar-28 icon"><i class="bi bi-credit-card fs-4"></i></div>
                                        <div class="col">
                                            <p class="h6 mb-0">Virtual Cards</p>
                                            <p class="small opacity-75">Manage your credit/debit cards</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 col-lg-8 col-xl-9">
                <div class="card adminuiux-card overflow-hidden mb-4 rounded-4 border">
                    @if (request()->query('tab', 'personal') == 'personal')
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="card-body">
                                <h6 class="mb-3">Personal Details</h6>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="first_name" type="text" class="form-control rounded-4 @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user ? $user->first_name : '') }}" autocomplete="given-name" autofocus>
                                            @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="first_name">First Name</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="last_name" type="text" class="form-control rounded-4 @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user ? $user->last_name : '') }}" autocomplete="family-name">
                                            @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="last_name">Last Name</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="email" type="email" class="form-control rounded-4" value="{{ $user ? $user->email : '' }}" autocomplete="email" readonly>
                                            <label for="email">Email Address</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="phone_number" type="tel" class="form-control rounded-4 @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user ? $user->phone_number ?? '' : '') }}" autocomplete="tel">
                                            @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="phone_number">Phone Number</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="date_of_birth" type="date" class="form-control rounded-4 @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', $user && $user->profile ? $user->profile->date_of_birth : '') }}" autocomplete="bday">
                                            @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="date_of_birth">Date of Birth</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select rounded-4 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                <option value="" disabled {{ old('gender', $user && $user->profile ? $user->profile->gender : '') == '' ? 'selected' : '' }}>Select Gender</option>
                                                <option value="male" {{ old('gender', $user && $user->profile ? $user->profile->gender : '') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $user && $user->profile ? $user->profile->gender : '') == 'female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                            @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="gender">Gender</label>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mb-3">Contact Details</h6>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select rounded-4 @error('country') is-invalid @enderror" id="country" name="country">
                                                <option value="" disabled {{ old('country', $user && $user->profile ? $user->profile->country : '') == '' ? 'selected' : '' }}>Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ Str::snake($country['name']) }}"
                                                        {{ old('country', $user && $user->profile ? $user->profile->country : '') == Str::snake($country['name']) ? 'selected' : '' }}>
                                                        {{ $country['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="country">Country</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="state" type="text" class="form-control rounded-4 @error('state') is-invalid @enderror" name="state" value="{{ old('state', $user && $user->profile ? $user->profile->state : '') }}" autocomplete="address-level1">
                                            @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="state">State</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="city" type="text" class="form-control rounded-4 @error('city') is-invalid @enderror" name="city" value="{{ old('city', $user && $user->profile ? $user->profile->city : '') }}" autocomplete="address-level2">
                                            @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="city">City</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-12">
                                        <textarea id="address" rows="4" class="form-control rounded-4 @error('address') is-invalid @enderror" name="address" autocomplete="street-address" placeholder="Address">{{ old('address', $user && $user->profile ? $user->profile->address : '') }}</textarea>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-auto mt-4">
                                    <button type="submit" class="btn btn-outline-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    @elseif (request()->query('tab', 'personal') == 'account')
                        <form method="POST" action="{{ route('admin.users.account.details.update', $user->id) }}">
                            @csrf

                            <div class="card-body">
                                <h6 class="mb-3">Account Details</h6>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select rounded-4 @error('marital_status') is-invalid @enderror" id="marital_status" name="marital_status">
                                                <option value="" disabled {{ old('marital_status', $user && $user->profile ? $user->profile->marital_status : '') == '' ? 'selected' : '' }}>Select Marital Status</option>
                                                <option value="married" {{ old('marital_status', $user && $user->profile ? $user->profile->marital_status : '') == 'married' ? 'selected' : '' }}>Married</option>
                                                <option value="single" {{ old('marital_status', $user && $user->profile ? $user->profile->marital_status : '') == 'single' ? 'selected' : '' }}>Single</option>
                                            </select>
                                            @error('marital_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="marital_status">Marital Status</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input id="occupation" type="text" class="form-control rounded-4 @error('occupation') is-invalid @enderror" name="occupation" value="{{ old('occupation', $user && $user->profile ? $user->profile->occupation : '') }}" autocomplete="organization-title">
                                            @error('occupation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="occupation">Occupation</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input id="social_security_number" type="text" class="form-control rounded-4 @error('social_security_number') is-invalid @enderror" name="social_security_number" value="{{ old('social_security_number', $user && $user->profile ? $user->profile->social_security_number : '') }}" autocomplete="off">
                                            @error('social_security_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="social_security_number">Social Security Number</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            <select class="form-select rounded-4 @error('account_type') is-invalid @enderror" id="account_type" name="account_type">
                                                <option value="" disabled {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == '' ? 'selected' : '' }}>Select Account Type</option>
                                                <option value="Savings" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Savings' ? 'selected' : '' }}>Savings Account</option>
                                                <option value="Current" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Current' ? 'selected' : '' }}>Current Account</option>
                                                <option value="Checking" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Checking' ? 'selected' : '' }}>Checking Account</option>
                                                <option value="Fixed Deposit" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Fixed Deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                                <option value="Non Resident" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Non Resident' ? 'selected' : '' }}>Non Resident Account</option>
                                                <option value="Online Banking" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                                <option value="Domiciliary Account" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Domiciliary Account' ? 'selected' : '' }}>Domiciliary Account</option>
                                                <option value="Joint Account" {{ old('account_type', $user && $user->profile ? $user->profile->account_type : '') == 'Joint Account' ? 'selected' : '' }}>Joint Account</option>
                                            </select>
                                            @error('account_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="account_type">Account Type</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-primary">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    @elseif (request()->query('tab', 'personal') == 'cards')
                        <div class="card-body pb-0">
                            <h6 class="mb-3">My virtual cards</h6>
                            <div class="swiper swipernav mb-3">
                                <div class="swiper-wrapper">
                                    @foreach($cards as $card)
                                        <div class="swiper-slide width-280">
                                            <div class="card bg-theme-l-gradient adminuiux-card border w-100 mb-2 rounded-4">
                                                <div class="card-body z-index-1">
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-auto align-self-center">
                                                            <i class="bi bi-credit-card fs-4"></i>
                                                        </div>

                                                        <div class="col text-end">
                                                            <p>
                                                                <span class="small opacity-75">{{ config('app.name') }}</span><br>
                                                                <span class="">{{ ucfirst($card->card_type) }} Card</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <p class="h5 mb-4">
                                                        {{ $card->card_number }}
                                                    </p>

                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <p class="mb-0 small opacity-75">Expiry</p>
                                                            <p>{{ $card->card_expiration }}</p>
                                                        </div>

                                                        <div class="col text-end">
                                                            <p class="mb-0 small opacity-75">Card Holder</p>
                                                            <p>{{ $card->card_name }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="swiper-slide width-280">
                                        <a href="javascript:void(0)" data-bs-target="#addCreditCard" data-bs-toggle="modal" class="bg-theme-1-subtle text-theme-accent-1 border rounded-4 height-190 w-100 text-center d-flex align-items-center justify-content-center style-none">
                                            <div class="py-4">
                                                <i class="bi bi-plus-circle h4 mb-4"></i>
                                                <p class="h5 mb-0">Create New</p>
                                                <p class="opacity-75 small">Virtual card</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteBtn = document.getElementById('delete-profile-picture');
            const deleteForm = document.getElementById('remove-profile-picture-form');

            if (deleteBtn && deleteForm) {
                deleteBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    iziToast.question({
                        timeout: false,
                        close: false,
                        overlay: true,
                        displayMode: 'once',
                        id: 'profile-delete-confirmation',
                        title: 'Are you sure?',
                        message: 'Do you want to remove your profile picture?',
                        position: 'topRight',
                        transitionIn: "flipInX",
                        transitionOut: "flipOutX",
                        buttons: [
                            ['<button><b>Yes, Delete</b></button>', function (instance, toast) {
                                deleteForm.submit();
                                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            }, true],
                            ['<button>No</button>', function (instance, toast) {
                                instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                            }]
                        ]
                    });
                });
            }
        });
    </script>
@endpush
