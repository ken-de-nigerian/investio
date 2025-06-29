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
                        <li class="breadcrumb-item active bi" aria-current="page">Interbank Transfers</li>
                    </ol>
                </nav>
                <h5>Interbank Transfers</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                <div class="row">
                    <!-- Interbank Transfer History Card -->
                    <div class="col-12 col-lg-12 mb-4">
                        <div class="card adminuiux-card rounded-4 border h-100">
                            <div class="card-header border-bottom">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6>Recent Interbank Transfers</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover transfers-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Reference ID</th>
                                                <th scope="col">Sender</th>
                                                <th scope="col">Recipient</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transfers as $transfer)
                                                <tr>
                                                    <td data-label="Reference ID">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->transfer_id }}</p>
                                                        <p class="small text-theme-1">
                                                            Date {{ $transfer->created_at->format('j M Y') }}
                                                        </p>
                                                    </td>

                                                    <td data-label="Sender">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->user ? $transfer->user->first_name . ' ' . $transfer->user->last_name : 'N/A' }}</p>
                                                        <p class="small text-theme-1">
                                                            {{ $transfer->user->profile->account_number }}
                                                        </p>
                                                    </td>

                                                    <td data-label="Recipient">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_name }}</p>
                                                        <p class="small text-theme-1">
                                                            {{ $transfer->account_number }}
                                                        </p>
                                                    </td>

                                                    <td data-label="Amount">
                                                        <p class="mb-0 small" style="font-size: 12px;">$ {{ number_format($transfer->amount, 2) }}</p>
                                                    </td>

                                                    <td data-label="Status">
                                                        <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                            {{ ucfirst($transfer->trans_status) }}
                                                        </span>
                                                    </td>

                                                    <td data-label="Action">
                                                        <a href="{{ route('admin.interbank.show', $transfer->id) }}" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                                        <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteInterBankTransfer({{ $transfer->id }})">Delete</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No transfers found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    {{ $transfers->links('vendor.pagination.bootstrap-5') }}
                                </div>
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

        // Helper function to handle form submission
        function handleDeleteForm(form, submitButton, actionUrl, successMessage) {
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            submitButton.disabled = true;

            const formData = new FormData(form);

            fetch(actionUrl, {
                method: "DELETE",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        showSuccess(data.message || successMessage);
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        const errors = Array.isArray(data.message) ? data.message : [data.message];
                        errors.forEach(showError);
                    }
                })
                .catch(error => {
                    showError(error || "Something went wrong. Please try again.");
                })
                .finally(() => {
                    submitButton.innerHTML = 'Delete';
                    submitButton.disabled = false;
                });
        }

        // Delete Interbank Transfer
        function deleteInterBankTransfer(transfer_id) {
            const modalElement = document.getElementById('deleteInterbankTransferModal');
            const form = document.getElementById('deleteInterbankTransferForm');
            const submitButton = form ? form.querySelector('button[type="submit"]') : null;

            if (!form || !submitButton) {
                return;
            }

            form.action = `/admin/interbank/${transfer_id}/delete`;
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            form.removeEventListener('submit', form._submitHandler);
            form._submitHandler = (event) => {
                event.preventDefault();
                handleDeleteForm(form, submitButton, form.action, "Interbank transfer deleted successfully");
            };
            form.addEventListener('submit', form._submitHandler);
        }
    </script>
@endpush
