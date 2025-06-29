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
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.deposits.methods') }}">Payment Methods</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Add Payment Method</li>
                    </ol>
                </nav>
                <h5>Add Payment Method</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <!-- Add Payment Method Form Card -->
                    <div class="col-12 col-lg-12 mb-4">
                        <div class="card adminuiux-card h-100 rounded-4 border">
                            <div class="card-header">
                                <h6>Add Payment Method</h6>
                            </div>

                            <div class="card-body">
                                <form id="payment-method-form" action="{{ route('admin.deposits.methods.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('name') is-invalid @enderror" id="name" name="name" autofocus value="{{ old('name') }}">
                                                <div class="invalid-feedback" id="name_error">Wallet Name is required.</div>
                                                <label for="name">Wallet Name</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('abbreviation') is-invalid @enderror" name="abbreviation" id="abbreviation" value="{{ old('abbreviation') }}">
                                                <div class="invalid-feedback" id="abbreviation_error">Wallet Abbreviation is required.</div>
                                                <label for="abbreviation">Wallet Abbreviation</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('status') is-invalid @enderror" id="status" name="status">
                                                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select Wallet Status</option>
                                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                                <div class="invalid-feedback" id="status_error">Wallet Status is required.</div>
                                                <label for="status">Wallet Status</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-4">
                                            <textarea class="form-control rounded-4 @error('gateway_parameter') is-invalid @enderror" name="gateway_parameter" id="gateway_parameter" rows="3" placeholder="Gateway Parameters">{{ old('gateway_parameter') }}</textarea>
                                            <div class="invalid-feedback" id="gateway_parameter_error">Gateway Parameters are required.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <button type="submit" id="domesticBtn" class="btn btn-theme w-100 rounded-4">
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                <span class="button-text">Add Wallet</span>
                                            </button>
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
        const showError = (message) => {
            iziToast.error({...iziToastSettings, message});
        };

        const showSuccess = (message) => {
            iziToast.success({...iziToastSettings, message});
        };

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("payment-method-form");
            const nameInput = document.getElementById("name");
            const abbreviationInput = document.getElementById("abbreviation");
            const statusSelect = document.getElementById("status");
            const gatewayParameterTextarea = document.getElementById("gateway_parameter");
            const submitButton = document.getElementById("domesticBtn");
            const spinner = submitButton.querySelector(".spinner-border");
            const buttonText = submitButton.querySelector(".button-text");

            // Form validation
            function validateForm() {
                let isValid = true;
                const errors = {
                    name: "Please enter the wallet name (at least 2 characters)",
                    abbreviation: "Please enter the wallet abbreviation (at least 2 characters)",
                    status: "Please select a wallet status",
                    gateway_parameter: "Please enter the gateway parameters"
                };

                // Reset invalid states
                [nameInput, abbreviationInput, statusSelect, gatewayParameterTextarea].forEach(input => {
                    input.classList.remove("is-invalid");
                    const errorElement = document.getElementById(`${input.id}_error`);
                    if (errorElement) errorElement.textContent = "";
                });

                // Validate name
                if (!nameInput.value.trim() || nameInput.value.length < 2) {
                    nameInput.classList.add("is-invalid");
                    document.getElementById("name_error").textContent = errors.name;
                    isValid = false;
                }

                // Validate abbreviation
                if (!abbreviationInput.value.trim() || abbreviationInput.value.length < 2) {
                    abbreviationInput.classList.add("is-invalid");
                    document.getElementById("abbreviation_error").textContent = errors.abbreviation;
                    isValid = false;
                }

                // Validate status
                if (!statusSelect.value) {
                    statusSelect.classList.add("is-invalid");
                    document.getElementById("status_error").textContent = errors.status;
                    isValid = false;
                }

                // Validate gateway parameters
                if (!gatewayParameterTextarea.value.trim()) {
                    gatewayParameterTextarea.classList.add("is-invalid");
                    document.getElementById("gateway_parameter_error").textContent = errors.gateway_parameter;
                    isValid = false;
                }

                return isValid;
            }

            // Form submission
            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                // Show spinner, hide button text
                spinner.classList.remove("d-none");
                buttonText.classList.add("d-none");
                submitButton.disabled = true;

                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    const response = await fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(data)
                    });

                    if (response.ok) {
                        showSuccess("Payment method added successfully!");
                        form.reset();
                        setTimeout(() => window.location.href = "{{ route('admin.deposits.methods') }}", 3000);
                    } else {
                        const errorData = await response.json();
                        let errorMessage = "An error occurred while adding the payment method.";
                        if (errorData.errors) {
                            errorMessage = Object.values(errorData.errors).flat().join("<br>");
                        } else if (errorData.message) {
                            errorMessage = errorData.message;
                        }
                        showError(errorMessage);
                    }
                } catch (error) {
                    showError("An error occurred while adding the payment method.");
                } finally {
                    // Hide spinner, show button text
                    spinner.classList.add("d-none");
                    buttonText.classList.remove("d-none");
                    submitButton.disabled = false;
                }
            });
        });
    </script>
@endpush
