@extends('layouts.app')

@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item bi">
                            <a href="{{ route('user.dashboard') }}"><i class="bi bi-house-door me-1 fs-14"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item bi"><a href="{{ route('user.wallet') }}">My Wallet</a></li>
                        <li class="breadcrumb-item active bi" aria-current="page">Wire Transfer</li>
                    </ol>
                </nav>
                <h5>Wire Transfer</h5>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                @if($auth['user']->balance < 500)
                    <div class="alert alert-danger alert-dismissible fade show mt-4 mb-4" role="alert">
                        <strong><i class="bi bi-exclamation-triangle me-1"></i> Insufficient Balance!</strong><br>
                        <small>
                            Your wallet balance is too low to complete this transaction. You can deposit or
                            <a href="{{ route('user.loan') }}" class="text-decoration-underline text-danger fw-bold">apply for a loan</a> to boost your account and proceed seamlessly.
                        </small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Wire Transfer Form Card -->
                    <div class="col-12 col-lg-12 mb-4">
                        <div class="card adminuiux-card h-100 rounded-4 border">
                            <div class="card-header">
                                <h6>Wire Transfer Details</h6>
                            </div>

                            <div class="card-body">
                                <form id="wire-transfer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('acct_name') is-invalid @enderror" id="acct_name" name="acct_name" autofocus value="{{ old('acct_name') }}">
                                                <div class="invalid-feedback">Account Name is required.</div>
                                                <label for="acct_name">Account name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('account_number') is-invalid @enderror" name="account_number" id="account_number" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')" value="{{ old('account_number') }}">
                                                <div class="invalid-feedback">Account Number is required.</div>
                                                <label for="account_number">Account Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-5">
                                        <div class="col">
                                            <div class="form-floating mb-1">
                                                <input type="text" class="form-control rounded-4" id="balance" value="$ {{ number_format($auth['user']->balance, 2) }}" readonly>
                                                <label for="balance">Your balance</label>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-square btn-theme mt-2"><i class="bi bi-arrow-left-right"></i></button>
                                        </div>
                                        <div class="col">
                                            <div class="form-floating mb-1">
                                                <input type="text" class="form-control rounded-4 @error('amount') is-invalid @enderror" name="amount" id="wire_transfer_amount" value="{{ old('amount') }}" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')">
                                                <div class="invalid-feedback">Amount is required.</div>
                                                <label for="wire_transfer_amount">Amount</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center mb-5">
                                        <h5 class="fw-normal">
                                            <b class="fw-bold">Great!</b> You are about to send
                                        </h5>
                                        <h1 class="mb-0 text-theme-1" id="confirm-amount">$0.00</h1>
                                        <p class="text-secondary small">to <span id="confirm-name">John Doe</span></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('bank_name') is-invalid @enderror" name="bank_name" id="bank_name" value="{{ old('bank_name') }}">
                                                <div class="invalid-feedback">Bank Name is required.</div>
                                                <label for="bank_name">Bank Name</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('acct_type') is-invalid @enderror" id="acct_type" name="acct_type">
                                                    <option value="" disabled {{ old('account_type') ? '' : 'selected' }}>Select Account Type</option>
                                                    <option value="Savings" {{ old('account_type') == 'Savings' ? 'selected' : '' }}>Savings Account</option>
                                                    <option value="Current" {{ old('account_type') == 'Current' ? 'selected' : '' }}>Current Account</option>
                                                    <option value="Checking" {{ old('account_type') == 'Checking' ? 'selected' : '' }}>Checking Account</option>
                                                    <option value="Fixed Deposit" {{ old('account_type') == 'Fixed Deposit' ? 'selected' : '' }}>Fixed Deposit</option>
                                                    <option value="Non Resident" {{ old('account_type') == 'Non Resident' ? 'selected' : '' }}>Non Resident Account</option>
                                                    <option value="Online Banking" {{ old('account_type') == 'Online Banking' ? 'selected' : '' }}>Online Banking</option>
                                                    <option value="Domiciliary Account" {{ old('account_type') == 'Domiciliary Account' ? 'selected' : '' }}>Domiciliary Account</option>
                                                    <option value="Joint Account" {{ old('account_type') == 'Joint Account' ? 'selected' : '' }}>Joint Account</option>
                                                </select>
                                                <div class="invalid-feedback">Account Type is required.</div>
                                                <label for="acct_type">Account Type</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <select class="form-select rounded-4 @error('acct_country') is-invalid @enderror" id="acct_country" name="acct_country">
                                                    <option value="" disabled {{ old('acct_country') ? '' : 'selected' }}>Select Country</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ Str::snake($country['name']) }}"
                                                            {{ old('country') == Str::snake($country['name']) ? 'selected' : '' }}>
                                                            {{ $country['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">Country is required.</div>
                                                <label for="acct_country">Country</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('acct_swift') is-invalid @enderror" name="acct_swift" id="acct_swift" value="{{ old('acct_swift') }}">
                                                <div class="invalid-feedback">Swift Code is required.</div>
                                                <label for="acct_swift">Swift Code</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-floating mb-4">
                                                <input type="text" class="form-control rounded-4 @error('acct_routing') is-invalid @enderror" id="acct_routing" name="acct_routing" value="{{ old('acct_routing') }}">
                                                <div class="invalid-feedback">Routing Number is required.</div>
                                                <label for="acct_routing">Routing Number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <textarea class="form-control rounded-4 @error('acct_remarks') is-invalid @enderror" name="acct_remarks" id="acct_remarks" rows="3" placeholder="Transfer Description">{{ old('acct_remarks') }}</textarea>
                                            <div class="invalid-feedback">Transfer Description is required.</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <button type="submit" id="wireBtn" class="btn btn-theme w-100 rounded-4">Transfer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Wire Transfers History Card -->
                    <div class="col-12 col-lg-12 mb-4">
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
                                                <th scope="col">Date</th>
                                                <th scope="col">Recipient</th>
                                                <th scope="col">Bank</th>
                                                <th scope="col">Acc. No</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Acc. Type</th>
                                                <th scope="col">Country</th>
                                                <th scope="col">SWIFT</th>
                                                <th scope="col">Routing</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($transfers as $transfer)
                                                <tr>
                                                    <td data-label="Reference ID">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->reference_id }}</p>
                                                    </td>

                                                    <td data-label="Date">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->created_at->format('j M Y') }}</p>
                                                    </td>

                                                    <td data-label="Recipient">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_name }}</p>
                                                    </td>

                                                    <td data-label="Bank">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->bank_name }}</p>
                                                    </td>

                                                    <td data-label="Account Number">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->account_number }}</p>
                                                    </td>

                                                    <td data-label="Amount">
                                                        <small class="fw-normal small" style="font-size: 12px;">$ {{ number_format($transfer->amount, 2) }}</small>
                                                    </td>

                                                    <td data-label="Account Type">
                                                        <span class="badge badge-sm badge-light text-bg-secondary small" style="font-size: 12px;">
                                                            {{ ucfirst($transfer->acct_type) }}
                                                        </span>
                                                    </td>

                                                    <td data-label="Country">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_country }}</p>
                                                    </td>

                                                    <td data-label="SWIFT">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_swift }}</p>
                                                    </td>

                                                    <td data-label="Routing">
                                                        <p class="mb-0 small" style="font-size: 12px;">{{ $transfer->acct_routing }}</p>
                                                    </td>

                                                    <td data-label="Status">
                                                        <span class="badge badge-sm badge-light text-bg-{{ $transfer->trans_status == 'approved' ? 'success' : ($transfer->trans_status == 'pending' ? 'warning' : 'danger') }} small" style="font-size: 12px;">
                                                            {{ ucfirst($transfer->trans_status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="13" class="text-center">No transfers found.</td>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const amountInput = document.getElementById("wire_transfer_amount");
            const nameInput = document.getElementById("acct_name");
            const confirmAmount = document.getElementById("confirm-amount");
            const confirmName = document.getElementById("confirm-name");

            function formatAmount(value) {
                let num = parseFloat(value.replace(/,/g, ''));
                return isNaN(num) ? "0.00" : num.toFixed(2);
            }

            amountInput.addEventListener("input", () => {
                confirmAmount.textContent = `$${formatAmount(amountInput.value)}`;
            });

            nameInput.addEventListener("input", () => {
                confirmName.textContent = nameInput.value || "John Doe";
            });
        });

        function sortTransfers(type, status) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', status);
            window.location.href = url.toString();
        }
    </script>
@endpush
