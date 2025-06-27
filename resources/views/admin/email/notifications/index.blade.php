@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Notifications</li>
                    </ol>
                </nav>
                <h5>Notifications</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <!-- Notifications Card -->
                    <div class="col-12 col-lg-12 mb-4">
                        <div class="card adminuiux-card h-100 rounded-4 border">
                            <div class="card-header">
                                <h6>Notification To All Users</h6>
                            </div>

                            <div class="card-body">
                                <form id="send-email" action="{{ route('admin.email.broadcast') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('subject') is-invalid @enderror" id="subject" name="subject" autofocus value="{{ old('subject') }}">
                                                <div class="invalid-feedback">Email subject is required.</div>
                                                <label for="subject">Email Subject</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-4">
                                            <div id="editor">{!! old('message') !!}</div>
                                            <input type="hidden" name="message" id="details" value="{{ old('message') }}">
                                            <div class="invalid-feedback">Email content is required.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <button type="submit" id="sendEmailBtn" class="btn btn-theme w-100 rounded-4">Send Email to All Users</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Notification functions
        const showError = (message) => {
            iziToast.error({...iziToastSettings, message});
        };

        const showSuccess = (message) => {
            iziToast.success({...iziToastSettings, message});
        };

        // Form submission
        const form = document.getElementById('send-email');
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('sendEmailBtn');
            const originalBtnHTML = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...`;

            // Validate inputs
            let isValid = true;
            const fields = ['email_subject', 'details'];

            fields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const value = input.value.trim();
                const feedback = input.nextElementSibling;

                if (value === '') {
                    input.classList.add('is-invalid');
                    input.classList.remove('is-valid');
                    feedback.style.display = 'block';
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                    feedback.style.display = 'none';
                }
            });

            if (!isValid) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
                return;
            }

            // Prepare form data
            const formData = new FormData(form);
            const action = form.getAttribute('action');

            fetch(action, {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;

                    if (data.success) {
                        showSuccess(data.message || 'Email sent successfully!');
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        showError(data.message || 'Failed to send email. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHTML;
                    showError('An error occurred: ' + error.message);
                });
        });

        // Remove validation feedback on input
        ['subject', 'details'].forEach(fieldId => {
            const input = document.getElementById(fieldId);
            if (input) {
                input.addEventListener('input', function () {
                    input.classList.remove('is-invalid');
                    input.classList.remove('is-valid');
                    const feedback = input.nextElementSibling;
                    if (feedback) {
                        feedback.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endpush
