@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <!-- Search & filter -->
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-12 col-xxl-12">
                <div class="col-12 col-md-10 col-lg-8 mb-4">
                    <h3 class="fw-normal mb-0 text-secondary">Users Management</h3>
                    <h1>View, filter, and manage all registered users on your platform.</h1>
                </div>

                <div class="row align-items-center">
                    <!-- Search Box -->
                    <div class="col-12 col-md-12 col-xxl-12 mb-4">
                        <form method="GET" action="{{ route('admin.users') }}" id="search-form" class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small">Search</label>
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or email...">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">-- All --</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">From</label>
                                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small">To</label>
                                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                            </div>

                            <div class="col-md-1">
                                <button type="submit" class="btn btn-theme w-100">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Benefits Highlights -->
                    <div class="col-12"></div>

                    <!-- Professional Support -->
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto theme-green mb-4">
                                <span class="avatar avatar-40 rounded border-theme-1 border text-theme-1">
                                    <i class="bi bi-people-fill h5"></i>
                                </span>
                            </div>

                            <div class="col-auto theme-green mb-4">
                                <p class="text-theme-1 small">Efficient User Support<br>and Admin Tools</p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Assurance -->
                    <div class="col-auto">
                        <div class="row">
                            <div class="col-auto theme-purple mb-4">
                                <span class="avatar avatar-40 rounded border-theme-1 border text-theme-1">
                                    <i class="bi bi-shield-lock h5"></i>
                                </span>
                            </div>

                            <div class="col-auto theme-purple mb-4">
                                <p class="text-theme-1 small">Secure & Reliable<br>User Data Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="row gx-3">
            @if($users->count())
                @foreach($users as $user)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card adminuiux-card border mb-3 rounded-4 border">
                            <div class="card-body">
                                <div class="row gx-3">
                                    <div class="col"></div>
                                    <div class="col-auto">
                                        <div class="dropdown d-inline-block">
                                            <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('admin.users.show', $user->id) }}">View</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">Edit</a></li>
                                                <li><a class="dropdown-item theme-red" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <div class="avatar avatar-80 rounded-circle coverimg mb-3 mx-auto" style="background-image: url('{{ $user->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}');">
                                        <img src="{{ $user->avatar ?: 'https://placehold.co/124x124/222934/ffffff?text=' . strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}" alt="{{ $user->first_name ?? 'User' }}" style="display: none;" loading="lazy">
                                    </div>

                                    <h6 class="mb-0">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-decoration-none">{{ $user->first_name }} {{ $user->last_name }}</a>
                                    </h6>

                                    <p class="text-secondary small">Ref by:
                                        @if($user->referrer)
                                            {{ $user->referrer->first_name }} {{ $user->referrer->last_name }}
                                        @else
                                            None
                                        @endif
                                    </p>
                                    <span class="badge badge-light rounded-pill text-bg-theme-1 mb-2">
                                    <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 col-lg-12 col-xxl-12 mb-4">
                    <div class="card adminuiux-card rounded-6 border position-relative border">
                        <div class="text-center">
                            <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                <div class="empty-notification-elem">
                                    <div class="w-25 w-sm-50 pt-3 mx-auto">
                                        <img src="{{ asset('assets/img/svg/bell.svg') }}" class="img-fluid" alt="not-found-pic" loading="lazy" />
                                    </div>
                                    <div class="text-center pb-5 mt-2">
                                        <h6 class="fs-18 fw-semibold lh-base">No users found.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            {{ $users->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Copy Referral & Wallet and Handle Forms -->
    <script>
        const showError = (message) => {
            iziToast.error({ ...iziToastSettings, message });
        };

        const showSuccess = (message) => {
            iziToast.success({ ...iziToastSettings, message });
        };

        const formFieldMap = {
            'delete-account-form': ['deleteReason']
        };

        Object.entries(formFieldMap).forEach(([formId, fields]) => {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const submitBtn = this.querySelector('[type="submit"]');
                const originalBtnHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...`;

                let isValid = true;

                fields.forEach(fieldId => {
                    const input = document.getElementById(fieldId);
                    if (!input) return;
                    const value = input.value.trim();

                    if (value === '') {
                        input.classList.add('is-invalid');
                        input.classList.remove('is-valid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                });

                if (!isValid) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                    return;
                }

                const formData = new FormData(this);
                const action = this.getAttribute('action');
                const method = this.getAttribute('method') || 'POST';

                formData.append('_token', '{{ csrf_token() }}');
                if (method.toUpperCase() === 'DELETE') {
                    formData.append('_method', 'DELETE');
                }

                fetch(action, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;

                        if (data.success) {
                            showSuccess(data.message || 'Action completed successfully!');
                            setTimeout(() => location.reload(), 3000);
                        } else {
                            showError(data.message || 'An error occurred. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHTML;
                        showError('An error occurred. Please try again.');
                    });
            });

            // Remove validation feedback on input
            fields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('input', function () {
                        input.classList.remove('is-invalid');
                        input.classList.remove('is-valid');
                    });
                }
            });
        });
    </script>
@endpush
