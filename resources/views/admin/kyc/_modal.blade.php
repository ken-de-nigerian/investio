@php use Carbon\Carbon; @endphp
<div class="modal fade" id="kycModalDynamic" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">KYC Details - {{ $kyc->first_name }} {{ $kyc->last_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Email:</strong> {{ $kyc->email }}</p>
                        <p class="mb-1"><strong>Phone:</strong> {{ $kyc->phone_number }}</p>
                        <p class="mb-1"><strong>Date of Birth:</strong> {{ Carbon::parse($kyc->date_of_birth)->format('M j, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Country:</strong> {{ $kyc->country }}</p>
                        <p class="mb-1"><strong>State:</strong> {{ $kyc->state }}</p>
                        <p class="mb-1"><strong>City:</strong> {{ $kyc->city }}</p>
                        <p class="mb-1"><strong>Address:</strong> {{ $kyc->address }}</p>
                    </div>
                </div>

                <hr>

                <h6 class="fw-semibold">ID Proof <small class="text-muted">(Type: {{ ucfirst($kyc->id_proof_type) }})</small></h6>
                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <p class="mb-1">Front:</p>
                        <a href="{{ asset($kyc->id_front_proof_url) }}" download class="btn btn-sm btn-outline-primary w-100 rounded-4" target="_blank">
                            Download Front ID
                        </a>
                    </div>
                    @if($kyc->id_back_proof_url)
                        <div class="col-md-6">
                            <p class="mb-1">Back:</p>
                            <a href="{{ asset($kyc->id_back_proof_url) }}" download class="btn btn-sm btn-outline-primary w-100 rounded-4" target="_blank">
                                Download Back ID
                            </a>
                        </div>
                    @endif
                </div>

                <hr>

                <h6 class="fw-semibold">Address Proof <small class="text-muted">(Type: {{ ucfirst($kyc->address_proof_type) }})</small></h6>
                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <p class="mb-1">Front:</p>
                        <a href="{{ asset($kyc->address_front_proof_url) }}" download class="btn btn-sm btn-outline-secondary w-100 rounded-4" target="_blank">
                            Download Front Address Proof
                        </a>
                    </div>
                    @if($kyc->address_back_proof_url)
                        <div class="col-md-6">
                            <p class="mb-1">Back:</p>
                            <a href="{{ asset($kyc->address_back_proof_url) }}" download class="btn btn-sm btn-outline-secondary w-100 rounded-4" target="_blank">
                                Download Back Address Proof
                            </a>
                        </div>
                    @endif
                </div>

                @if($kyc->status === 'rejected')
                    <div class="alert alert-danger">
                        <strong>Rejection Reason:</strong> {{ $kyc->rejection_reason }}
                    </div>
                @endif
            </div>

            <div class="modal-footer d-flex justify-content-between flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.kyc.reject', $kyc->id) }}" class="d-flex flex-grow-1 gap-2">
                    @csrf
                    <input type="text" name="rejection_reason" class="form-control form-control-sm" placeholder="Reason for rejection" required>
                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                </form>

                <form method="POST" action="{{ route('admin.kyc.approve', $kyc->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>
