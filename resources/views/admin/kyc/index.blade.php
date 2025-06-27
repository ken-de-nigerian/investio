@php use Carbon\Carbon; @endphp
@extends('layouts.admin')
@section('content')
    <!-- Content -->
    <div class="container mt-4 mb-5" id="main-content">
        <div class="row align-items-center">
            <!-- Welcome box -->
            <div class="col-12 col-md-10 col-lg-8 mb-4">
                <h3 class="fw-normal mb-0 text-secondary">Kyc Submissions</h3>
                <h1>Effortlessly manage users kyc status.</h1>
            </div>

            <div class="col-12 py-2"></div>

            <!-- Kyc unverified -->
            <div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body">
                        <h2>{{ $metrics['kyc_unverified'] ?? 0 }}</h2>
                        <p class="text-secondary small">Total Unverified Kyc</p>
                    </div>
                </div>
            </div>

            <!-- Kyc rejected -->
            <div class="col-12 col-sm-6 col-lg-6 mb-4">
                <div class="card adminuiux-card rounded-4 border">
                    <div class="card-body">
                        <h2>{{ $metrics['kyc_rejected'] ?? 0 }}</h2>
                        <p class="text-secondary small">Total Rejected Kyc</p>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="row gx-3">
            <div class="col-12 mb-4 mt-4">
                <div class="card adminuiux-card rounded-4 border h-100">
                    <div class="card-header border-bottom">
                        <h6>Pending KYC Verifications</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover transfers-table">
                                <thead>
                                    <tr>
                                        <th scope="col">User</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Submitted</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kycs as $kyc)
                                        <tr>
                                            <td data-label="Fullname">{{ $kyc->user->first_name }} {{ $kyc->user->last_name }}</td>
                                            <td data-label="Email">{{ $kyc->user->email }}</td>
                                            <td data-label="Submitted">{{ Carbon::parse($kyc->created_at)->format('M j, Y') }}</td>
                                            <td data-label="Status">
                                                <span class="badge bg-warning text-dark">{{ ucfirst($kyc->status) }}</span>
                                            </td>
                                            <td data-label="Review">
                                                <button class="btn btn-sm btn-primary review-kyc-btn" data-id="{{ $kyc->id }}">
                                                    Review
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No pending KYC submissions.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            {{ $kycs->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kycModalContainer"></div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.review-kyc-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const kycId = this.dataset.id;

                    fetch(`/admin/kyc/modal/${kycId}`)
                        .then(response => response.text())
                        .then(html => {
                            const container = document.getElementById('kycModalContainer');
                            container.innerHTML = html;
                            const modal = new bootstrap.Modal(document.getElementById('kycModalDynamic'));
                            modal.show();
                        })
                        .catch(error => {
                            console.error('Error loading modal:', error);
                            alert('Failed to load KYC details.');
                        });
                });
            });
        });
    </script>
@endpush
