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
                        <img src="{{ $avatar }}" alt="Avatar" id="profile-image-preview" class="object-fit-cover" loading="lazy">
                    </figure>

                    @if (!$auth['user']->avatar)
                        <!-- Upload button -->
                        <div class="position-absolute bottom-0 end-0 z-index-1 h-auto">
                            <form id="update-profile-picture-form" action="{{ route('admin.picture.update') }}" method="POST" enctype="multipart/form-data" style="display: inline;">
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
                            <form id="remove-profile-picture-form" action="{{ route('admin.picture.remove') }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" id="delete-profile-picture" class="btn btn-lg btn-danger btn-square rounded-pill">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <h4>{{ $auth['user']['first_name'] }} {{ $auth['user']['last_name'] }}</h4>
                <p class="opacity-75 mb-3">{{ $auth['user']['email'] }}</p>
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
                                    <a class="nav-link {{ request()->query('tab', 'personal') == 'personal' ? 'active' : '' }}" href="{{ route('admin.profile', ['tab' => 'personal']) }}">
                                        <div class="avatar avatar-28 icon"><i class="bi bi-people fs-4"></i></div>
                                        <div class="col">
                                            <p class="h6 mb-0">Personal Profile</p>
                                            <p class="small opacity-75">Fill out your personal & contact information.</p>
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->query('tab', 'personal') == 'security' ? 'active' : '' }}" href="{{ route('admin.profile', ['tab' => 'security']) }}">
                                        <div class="avatar avatar-28 icon"><i class="bi bi-lock fs-4"></i></div>
                                        <div class="col">
                                            <p class="h6 mb-0">Security Details</p>
                                            <p class="small opacity-75">Fill out your security information.</p>
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
                        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="card-body">
                                <h6 class="mb-3">Personal Details</h6>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="first_name" type="text" class="form-control rounded-4 @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $auth['user'] ? $auth['user']->first_name : '') }}" autocomplete="given-name" autofocus>
                                            @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="first_name">First Name</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="last_name" type="text" class="form-control rounded-4 @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $auth['user'] ? $auth['user']->last_name : '') }}" autocomplete="family-name">
                                            @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="last_name">Last Name</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="email" type="email" class="form-control rounded-4" value="{{ $auth['user'] ? $auth['user']->email : '' }}" autocomplete="email" readonly>
                                            <label for="email">Email Address</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="phone_number" type="tel" class="form-control rounded-4 @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $auth['user'] ? $auth['user']->phone_number ?? '' : '') }}" autocomplete="tel">
                                            @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="phone_number">Phone Number</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="date_of_birth" type="date" class="form-control rounded-4 @error('date_of_birth') is-invalid @enderror" name="date_of_birth" value="{{ old('date_of_birth', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->date_of_birth : '') }}" autocomplete="bday">
                                            @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="date_of_birth">Date of Birth</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <select class="form-select rounded-4 @error('gender') is-invalid @enderror" id="gender" name="gender">
                                                <option value="" disabled {{ old('gender', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->gender : '') == '' ? 'selected' : '' }}>Select Gender</option>
                                                <option value="male" {{ old('gender', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->gender : '') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->gender : '') == 'female' ? 'selected' : '' }}>Female</option>
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
                                                <option value="" disabled {{ old('country', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->country : '') == '' ? 'selected' : '' }}>Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ Str::snake($country['name']) }}"
                                                        {{ old('country', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->country : '') == Str::snake($country['name']) ? 'selected' : '' }}>
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
                                            <input id="state" type="text" class="form-control rounded-4 @error('state') is-invalid @enderror" name="state" value="{{ old('state', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->state : '') }}" autocomplete="address-level1">
                                            @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="state">State</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="form-floating mb-3">
                                            <input id="city" type="text" class="form-control rounded-4 @error('city') is-invalid @enderror" name="city" value="{{ old('city', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->city : '') }}" autocomplete="address-level2">
                                            @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="city">City</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-12">
                                        <textarea id="address" rows="4" class="form-control rounded-4 @error('address') is-invalid @enderror" name="address" autocomplete="street-address" placeholder="Address">{{ old('address', $auth['user'] && $auth['user']->profile ? $auth['user']->profile->address : '') }}</textarea>
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
                    @elseif (request()->query('tab', 'personal') == 'security')
                        <form method="POST" action="{{ route('admin.password.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="card-body">
                                <h6 class="mb-3">Security Details</h6>
                                <div class="row mb-2">
                                    <div class="col-12 col-md-6 col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input id="current-password" type="password" class="form-control rounded-4 @error('current_password') is-invalid @enderror" name="current_password"  autocomplete="current-password">
                                            @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="current-password">Current Password</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input id="password" type="password" class="form-control rounded-4 @error('password') is-invalid @enderror" name="password" autocomplete="password">
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="password">New Password</label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input id="password_confirmation" type="password" class="form-control rounded-4 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="confirm-password">
                                            @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <label for="password_confirmation">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-outline-primary">Save Changes</button>
                                </div>

                                <!-- Delete account -->
                                <h2 class="h6 pt-5 mt-xl-2 pb-1 mb-2">Delete account</h2>
                                <p class="fs-sm">When you delete your account, your public profile will be deactivated immediately. If you change your mind before the 14 days are up, sign in with your email and password, and we'll send a link to reactivate account.</p>
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="confirm-deletion">
                                    <label for="confirm-deletion" class="form-check-label">Yes, I want to delete my account</label>
                                </div>
                                <a class="fs-sm fw-medium text-danger" href="#">Delete account</a>
                            </div>
                        </form>
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
