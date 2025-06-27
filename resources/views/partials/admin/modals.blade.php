@if(Route::is('admin.users') || Route::is('admin.users.show'))
    <!-- Delete Account Modal -->
    <div class="modal adminuiux-modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="delete-account-form" action="{{ route('admin.users.delete', $user->id ?? '0') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-danger fw-bold">This action is permanent and cannot be undone.</p>
                        <div class="mb-3">
                            <label for="deleteReason" class="form-label">Reason for Deletion</label>
                            <textarea class="form-control form-control-lg rounded-4" id="deleteReason" name="reason" rows="4" placeholder="Why are you deleting this account?"></textarea>
                            <div class="invalid-feedback">Reason for Deletion is required.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if(Route::is('admin.deposits') || Route::is('admin.deposits.show') || Route::is('admin.users.show'))
    <!-- Approve Deposit Modal -->
    <div class="modal adminuiux-modal fade" id="approveDepositModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveDepositModalLabel">Confirm Deposit Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this deposit? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="approveDepositForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Approve</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Deposit Modal -->
    <div class="modal adminuiux-modal fade" id="rejectDepositModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectDepositModalLabel">Confirm Deposit Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject this deposit? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="rejectDepositForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Deposit Modal -->
    <div class="modal adminuiux-modal fade" id="deleteDepositModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDepositModalLabel">Confirm Deposit Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this deposit? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteDepositForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if(Route::is('admin.users.show'))
    <!-- Manage Funds Modal -->
    <div class="modal adminuiux-modal fade" id="fundsModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="fundsModalLabel" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fundsModalLabel">Manage Funds</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="manage-funds-form" action="{{ route('admin.users.funds', $user->id) }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fundsAmount" class="form-label">Amount <small class="text-muted">(e.g. 100.00)</small></label>
                            <input type="number" class="form-control form-control-lg rounded-4" id="fundsAmount" name="amount" step="0.01" placeholder="Enter amount to deposit or withdraw">
                            <div class="invalid-feedback">Amount is required.</div>
                        </div>

                        <div class="mb-3">
                            <label for="fundsType" class="form-label">Action <small class="text-muted">(Choose the fund type)</small></label>
                            <select class="form-select form-select-lg rounded-4" id="fundsType" name="type">
                                <option value="">Select Action</option>
                                <option value="deposit">Deposit</option>
                                <option value="withdraw">Withdraw</option>
                            </select>
                            <div class="invalid-feedback">Type is required.</div>
                        </div>

                        <div class="mb-3">
                            <label for="fundsNote" class="form-label">Note <small class="text-muted">(Optional – Reason for transaction)</small></label>
                            <textarea class="form-control form-control-lg rounded-4" id="fundsNote" name="note" rows="4" placeholder="Optional note about this fund transaction..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Email Modal -->
    <div class="modal adminuiux-modal fade" id="emailUserModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="emailUserModalLabel" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="emailUserModalLabel">Send Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="send-email-form" action="{{ route('admin.users.email', $user->id) }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="emailSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control form-control-lg rounded-4" id="emailSubject" name="subject" placeholder="Enter email subject">
                            <div class="invalid-feedback">Email subject is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="emailMessage" class="form-label">Message</label>
                            <textarea class="form-control form-control-lg rounded-4" id="emailMessage" name="message" rows="5" placeholder="Write your message to the user..."></textarea>
                            <div class="invalid-feedback">Message is required.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset User Password Modal -->
    <div class="modal adminuiux-modal fade" id="userPasswordModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="userPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userPasswordModalLabel">Reset User Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="reset-password-form" action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control form-control-lg rounded-4" id="newPassword" name="password" placeholder="Enter new password">
                            <div class="invalid-feedback">New Password is required.</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-lg rounded-4" id="confirmPassword" name="password_confirmation" placeholder="Re-enter new password">
                            <div class="invalid-feedback">Confirm Password is required.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Block Account Modal -->
    <div class="modal adminuiux-modal fade" id="blockModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockModalLabel">Block Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="block-account-form" action="{{ route('admin.users.block', $user->id) }}" method="POST">
                    @csrf

                    <div class="modal-body">
                        <p class="text-danger fw-bold">This action will block the user from logging into their account.</p>
                        <div class="mb-3">
                            <label for="blockReason" class="form-label">Reason for Blocking</label>
                            <textarea class="form-control form-control-lg rounded-4" id="blockReason" name="reason" rows="4" placeholder="Explain why this account is being blocked..."></textarea>
                            <div class="invalid-feedback">Reason for Blocking is required.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Block Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Interbank Transfer Modal -->
    <div class="modal adminuiux-modal fade" id="deleteInterbankTransferModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteInterbankTransferModalLabel">Confirm Interbank Transfer Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this interbank transfer? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteInterbankTransferForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Domestic Transfer Modal -->
    <div class="modal adminuiux-modal fade" id="deleteDomesticTransferModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDomesticTransferModalLabel">Confirm Domestic Transfer Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this domestic transfer? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteDomesticTransferForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Wire Transfer Modal -->
    <div class="modal adminuiux-modal fade" id="deleteWireTransferModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteWireTransferModalLabel">Confirm Wire Transfer Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this wire transfer? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteWireTransferForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Goal Modal -->
    <div class="modal adminuiux-modal fade" id="deleteGoalModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGoalModalLabel">Confirm Goal Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this goal? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteGoalForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Loan Modal -->
    <div class="modal adminuiux-modal fade" id="deleteLoanModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLoanModalLabel">Confirm Loan Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this loan? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteLoanForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Investment Modal -->
    <div class="modal adminuiux-modal fade" id="deleteInvestmentModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteInvestmentModalLabel">Confirm Investment Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this investment? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteInvestmentForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@if(Route::is('admin.users.edit'))
    <!-- add credit card  -->
    <div class="modal adminuiux-modal fade" id="addCreditCard" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <form action="{{ route('admin.users.card.store', $user->id) }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <p class="h6">Add Virtual Card</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pb-0">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <div class="form-floating">
                            <input type="text" name="card_holder" placeholder="Card Holder Name" value="{{ $user->first_name }} {{ $user->last_name }}" class="form-control" readonly>
                            <label>Card Holder Name</label>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                        <div class="form-floating">
                            @php
                                // Function to generate valid card numbers based on type
                                function generateCardNumber(): string
                                {
                                    $types = [
                                        'visa' => '4',
                                        'mastercard' => ['51', '52', '53', '54', '55', '2221', '2222', '2223', '2224', '2225', '2226', '2227', '2228', '2229', '223', '224', '225', '226', '227', '228', '229', '23', '24', '25', '26', '270', '271', '2720'],
                                        'amex' => ['34', '37'],
                                        'discover' => ['6011', '644', '645', '646', '647', '648', '649', '65'],
                                    ];

                                    // Randomly select card type
                                    $type = array_rand($types);
                                    $prefixes = is_array($types[$type]) ? $types[$type] : [$types[$type]];
                                    $prefix = $prefixes[array_rand($prefixes)];

                                    // Generate remaining digits
                                    $length = ($type == 'amex') ? 15 : 16;
                                    $remaining = $length - strlen($prefix);
                                    $number = $prefix;

                                    for ($i = 0; $i < $remaining - 1; $i++) {
                                        $number .= mt_rand(0, 9);
                                    }

                                    // Add Luhn check digit
                                    return $number . luhnCheckDigit($number);
                                }

                                // Luhn algorithm to generate valid check digit
                                function luhnCheckDigit($partialNumber): int
                                {
                                    $sum = 0;
                                    $alt = false;

                                    for ($i = strlen($partialNumber) - 1; $i >= 0; $i--) {
                                        $digit = $partialNumber[$i];
                                        if ($alt) {
                                            $digit *= 2;
                                            if ($digit > 9) {
                                                $digit -= 9;
                                            }
                                        }
                                        $sum += $digit;
                                        $alt = !$alt;
                                    }

                                    return (10 - ($sum % 10)) % 10;
                                }

                                $cardNumber = generateCardNumber();
                                $formattedCardNumber = implode(' ', str_split($cardNumber, 4));
                            @endphp
                            <input type="text" name="card_number" placeholder="Card Number" value="{{ $formattedCardNumber }}" class="form-control" readonly>
                            <label>Card Number</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <div class="form-floating">
                                    @php
                                        $currentMonth = date('m');
                                        $selectedMonth = str_pad(rand($currentMonth, 12), 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <input type="text" name="expiry_month" class="form-control" placeholder="MM" value="{{ $selectedMonth }}" pattern="(0[1-9]|1[0-2])" title="Two-digit month (01-12)" readonly>
                                    <label>Month</label>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                <div class="form-floating">
                                    @php
                                        $currentYear = date('Y');
                                        $selectedYear = $currentYear + rand(1, 5);
                                    @endphp
                                    <input type="text" name="expiry_year" class="form-control" placeholder="YYYY" value="{{ $selectedYear }}" pattern="\d{4}" title="Four-digit year" readonly>
                                    <label>Year</label>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-asterisk"></i></span>
                                <div class="form-floating">
                                    <input type="number" name="cvv" placeholder="CVV" value="{{ rand(100, 999) }}" class="form-control" readonly>
                                    <label>CVV</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-12">
                        <button type="submit" class="btn btn-theme w-100">Add Card</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
