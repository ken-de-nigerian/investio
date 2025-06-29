@extends('layouts.admin')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-6 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Wire Transfer</li>
                    </ol>
                </nav>
                <h5>Wire Transfer</h5>
            </div>

            <div class="col-6 col-sm text-end">
                <a href="{{ route('admin.wire.create') }}" class="btn btn-theme">
                    <i class="bi bi-plus-lg"></i> Wire Transfer
                </a>
            </div>
        </div>

        <div class="row">
            <!-- approved transfers -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card adminuiux-card theme-teal mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($metrics['approved_transfers'], 2) }}</h4>
                        <p><span class="text-secondary">Approved transfers</span></p>
                    </div>
                </div>
            </div>

            <!-- pending transfers -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card adminuiux-card theme-orange mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($metrics['pending_transfers'], 2) }}</h4>
                        <p><span class="text-secondary">Pending transfers</span></p>
                    </div>
                </div>
            </div>

            <!-- rejected transfers -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card adminuiux-card theme-red mb-4 rounded-6 border">
                    <div class="card-body z-index-1">
                        <h4 class="fw-medium">$ {{ number_format($metrics['rejected_transfers'], 2) }}</h4>
                        <p><span class="text-secondary">Rejected transfers</span></p>
                    </div>
                </div>
            </div>

            <!-- Deposit history -->
            <div class="col-12 col-md-12 col-xl-12 col-xxl-12 mb-4">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6>Recent Wire Transfers</h6>
                            </div>

                            <div class="col-auto">
                                <select class="form-select form-select-sm rounded-pill border" id="sortSelectWire" onchange="sortTransfers('wire', this.value)">
                                    <option value="" {{ request()->query('sort') == '' ? 'selected' : '' }}>Sort By</option>
                                    <option value="approved" {{ request()->query('sort') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ request()->query('sort') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="rejected" {{ request()->query('sort') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
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
                                        <th scope="col">Bank</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Acc. Type</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($transfers as $transfer)
                                    <tr>

                                        <td data-label="Reference ID">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->reference_id }}</p>
                                            <p class="small text-theme-1">
                                                Date {{ $transfer->created_at->format('j M Y') }}
                                            </p>
                                        </td>

                                        <td data-label="Sender">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->user->first_name }} {{ $transfer->user->last_name }}</p>
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

                                        <td data-label="Bank">
                                            <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->bank_name }}</p>
                                        </td>

                                        <td data-label="Amount">
                                            <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($transfer->amount, 2) }}</small>
                                        </td>

                                        <td data-label="Account Type">
                                            <span class="badge badge-sm badge-light text-bg-secondary small" style="font-size: 12px;">
                                                {{ ucfirst($transfer->acct_type) }}
                                            </span>
                                        </td>

                                        <td data-label="Status">
                                            <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                {{ ucfirst($transfer->trans_status) }}
                                            </span>
                                        </td>

                                        <td data-label="Action">
                                            <a href="{{ route('admin.wire.show', $transfer->id) }}" class="btn btn-sm btn-outline-info rounded-4">View</a>
                                            <a href="{{ route('admin.wire.edit', $transfer->id) }}" class="btn btn-sm btn-outline-primary rounded-4">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger rounded-4" onclick="deleteWireTransfer({{ $transfer->id }})">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No transfers found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $transfers->links('vendor.pagination.bootstrap-5') }}
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

        function sortTransfers(type, status) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', status);
            window.location.href = url.toString();
        }

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

        // Delete Wire Transfer
        function deleteWireTransfer(transfer_id) {
            const modalElement = document.getElementById('deleteWireTransferModal');
            const form = document.getElementById('deleteWireTransferForm');
            const submitButton = form ? form.querySelector('button[type="submit"]') : null;

            if (!form || !submitButton) {
                return;
            }

            form.action = `/admin/wire/${transfer_id}/delete`;
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            form.removeEventListener('submit', form._submitHandler);
            form._submitHandler = (event) => {
                event.preventDefault();
                handleDeleteForm(form, submitButton, form.action, "Wire transfer deleted successfully");
            };
            form.addEventListener('submit', form._submitHandler);
        }
    </script>
@endpush
