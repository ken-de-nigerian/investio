<!-- send money modal -->
<div class="modal adminuiux-modal fade" id="sendmoneymodal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h5">Send Money</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="interbank-transfer" action="{{ route('user.perform.interbank.transfer') }}" method="post">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-4" name="account_number" id="account_no" autocomplete="off">
                                <div class="invalid-feedback">Account Number is required.</div>
                                <label for="account_no">Send money to</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-4" name="amount" id="transfer_amount" autocomplete="off" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')">
                                <div class="invalid-feedback">Amount is required.</div>
                                <label for="transfer_amount">Enter amount</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h5 class="fw-normal"><b class="fw-bold">Great!</b> You are about to send</h5>
                        <h1 class="mb-0 text-theme-1 display_amount">$0.00</h1>
                        <p class="text-secondary small recipient">to John Doe</p>
                    </div>

                    <div class="col-md-12 mt-4" id="otp_area">
                        @include('partials.otp-area')
                    </div>

                    @if($auth['user']->balance < 500)
                        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                            <strong><i class="bi bi-exclamation-triangle me-1"></i> Insufficient Balance!</strong><br>
                            <small>
                                Your wallet balance is too low to complete this transaction. You can deposit or
                                <a href="{{ route('user.loan') }}" class="text-decoration-underline text-danger fw-bold">apply for a loan</a> to boost your account and proceed seamlessly.
                            </small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-theme w-100 mb-4" id="interbankBtn">Send Money</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- add money modal -->
<div class="modal adminuiux-modal fade" id="addmoneymodal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h5">Add Funds To Wallet</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="depositForm" action="{{ route('user.wallet.deposit') }}" method="POST">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-4" id="deposit-amount" name="amount" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')">
                                <div class="invalid-feedback">Amount is required.</div>
                                <label for="deposit-amount">Enter amount</label>
                            </div>
                        </div>

                        <div class="col-auto">
                            <div class="form-floating mb-4">
                                <select class="form-select rounded-4" id="method" name="method">
                                    <option value="" selected>Choose Wallet</option>
                                    @foreach($gateways as $gateway)
                                        <option value="{{ $gateway['method_code'] }}"
                                                data-wallet="{{ $gateway['gateway_parameter'] }}"
                                                data-abbreviation="{{ $gateway['abbreviation'] }}"
                                        >{{ $gateway["name"] }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Payment Wallet is required.</div>
                                <label for="method">Payment Wallet</label>
                            </div>
                        </div>
                    </div>

                    <div id="wallet-area">
                        <div class="text-center mb-4">
                            <h5 class="fw-normal"><b class="fw-bold">Please</b> make payment of</h5>
                            <h1 class="mb-0 text-theme-1 converted" id="converted">0.00</h1>
                            <p class="text-secondary small" id="abbreviation">to our company address below</p>
                        </div>

                        <div class="text-center my-4">
                            <div class="right-qr-code avatar avatar-150 mb-4">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?data=1234567890" alt="" class="mw-100 mx-auto" loading="lazy">
                            </div>

                            <h5 class="mb-0">Scan QR code on your mobile</h5>
                            <p class="text-secondary">To add funds to your wallet</p>
                        </div>

                        <div class="input-group">
                            <input id="walletAddress" type="text" class="form-control form-control-lg border-theme-1" aria-describedby="button-addon2" readonly>
                            <button class="btn btn-lg btn-outline-theme" type="button" id="button-addon2" onclick="copyToClipboard(document.getElementById('walletAddress'))"><i class="bi bi-copy"></i></button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-theme w-100 mb-4" id="depositBtn">Deposit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- domestic transfer otp validation modal -->
<div class="modal adminuiux-modal fade" id="otpValidationModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="storeDomesticTransfer" action="{{ route('user.domestic.transfer.store') }}" method="post">
                @csrf

                <div class="modal-body">
                    <div class="col-md-12 mt-4">
                        @include('partials.domestic-otp-area')
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-theme w-100 mb-4" id="domTransferStoreBtn">Complete Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- wire transfer otp validation modal -->
<div class="modal adminuiux-modal fade" id="wireOtpValidationModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="storeWireTransfer" action="{{ route('user.wire.transfer.store') }}" method="post">
                @csrf

                <div class="modal-body">
                    <div class="col-md-12 mt-4">
                        @include('partials.wire-otp-area')
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-theme w-100 mb-4" id="wireTransferStoreBtn">Complete Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(Route::is('user.loan.show'))
    <!-- repay loan modal -->
    <div class="modal adminuiux-modal fade" id="repayLoanmodal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog rounded-6">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title h5">Repay Loan</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="repay-loan-form" action="{{ route('user.loan.update', $loan->id) }}" method="post">
                    @csrf
                    <input type="hidden" id="loan_id" name="loan_id" value="{{ $loan->id }}">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating mb-4">
                                    <input type="text" class="form-control rounded-4" name="repayment_amount" id="repayment_amount" autocomplete="off" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')">
                                    <div class="invalid-feedback">Amount is required.</div>
                                    <label for="repayment_amount">Enter amount</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <h5 class="fw-normal"><b class="fw-bold">Great!</b> You are about to repay</h5>
                            <h1 class="mb-0 text-theme-1" id="display_amount">$0.00</h1>
                            <p class="text-secondary small">for your loan</p>
                        </div>

                        @if($auth['user']->balance < $loan->monthly_emi)
                            <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                                <strong><i class="bi bi-exclamation-triangle me-1"></i> Insufficient Balance!</strong><br>
                                <small>
                                    Your wallet balance is too low to complete this transaction. You can deposit or
                                    <a href="{{ route('user.loan') }}" class="text-decoration-underline text-danger fw-bold">apply for a loan</a> to boost your account and proceed seamlessly.
                                </small>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button class="btn btn-theme w-100 mb-4" id="repayLoanBtn">Repay Loan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- delete confirmation modal -->
<div class="modal adminuiux-modal fade" id="deleteModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete this resource? This action cannot be undone.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- top up goal modal -->
<div class="modal adminuiux-modal fade" id="topUpModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h5">Add Funds to Savings</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="topUpForm" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-4" name="top_up_amount" id="top_up_amount" autocomplete="off" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')">
                                <div class="invalid-feedback">Please enter a valid amount.</div>
                                <label for="top_up_amount">Top-Up Amount</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h5 class="fw-normal"><b class="fw-bold">Boost Your Savings!</b> You'll add</h5>
                        <h1 class="mb-0 text-theme-1" id="top_up_display_amount">$0.00</h1>
                        <p class="text-secondary small">to your savings goal</p>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-theme w-100 mb-4" id="topUpBtn">Confirm Top-Up</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- withdraw confirmation modal -->
<div class="modal adminuiux-modal fade" id="withdrawModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h5">Withdraw Funds</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="withdrawForm" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-4" name="withdrawal_amount" id="withdrawal_amount" autocomplete="off" onkeyup="this.value = this.value.replace (/^\.|[^\d.]/g, '')">
                                <div class="invalid-feedback">Please enter a valid amount.</div>
                                <label for="withdrawal_amount">Withdrawal Amount</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h5 class="fw-normal"><b class="fw-bold">Ready to Withdraw?</b> You'll transfer</h5>
                        <h1 class="mb-0 text-theme-1" id="withdraw_display_amount">$0.00</h1>
                        <p class="text-secondary small">to your account balance</p>
                    </div>

                    <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                        <strong><i class="bi bi-exclamation-triangle me-1"></i> Important Notice</strong><br>
                        <small>
                            Withdrawing funds will mark this savings goal as completed. This action cannot be undone.
                        </small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button class="btn btn-theme w-100 mb-4" id="withdrawBtn">Confirm Withdrawal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- add credit card  -->
<div class="modal adminuiux-modal fade" id="addCreditCard" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <form action="{{ route('user.card.store') }}" method="POST" class="modal-content">
            @csrf

            <div class="modal-header">
                <p class="h6">Add Virtual Card</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pb-0">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <div class="form-floating">
                        <input type="text" name="card_holder" placeholder="Card Holder Name" value="{{ $auth['user']->first_name }} {{ $auth['user']->last_name }}" class="form-control" readonly>
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

<!-- process investment modal -->
<div class="modal adminuiux-modal fade" id="processInvestmentModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h5">Confirm Investment</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="investmentForm" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-secondary border rounded-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill text-theme-1 me-3 h4"></i>
                                    <div>
                                        <h6 class="mb-1">Investment Summary</h6>
                                        <p class="small mb-0" id="investmentSummaryText">
                                            <!-- Will be populated by JavaScript -->
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control rounded-4" name="investment_amount" id="investment_amount" autocomplete="off">
                                <label for="investment_amount">Investment Amount ($)</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border rounded-4">
                                <div class="card-body text-center">
                                    <h5 class="fw-normal">Projected Return at Maturity</h5>
                                    <h1 class="mb-0 text-theme-1" id="projected_return_amount">$0.00</h1>
                                    <p class="text-secondary small" id="maturity_date_text">
                                        <!-- Will show maturity date -->
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" required>
                        <label class="form-check-label small" for="agree_terms">
                            I agree to the <a href="#" target="_blank">Terms of Service</a> and
                            <a href="#" target="_blank">Investment Agreement</a>
                        </label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="lock_funds" id="lock_funds" required>
                        <label class="form-check-label small" for="lock_funds">
                            I understand my funds will be locked until investment maturity on
                            <span id="maturity_date_display"></span> and cannot be withdrawn early
                        </label>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-theme rounded-4" id="confirmInvestmentBtn" disabled>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Confirm Investment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- liquidate investment modal -->
<div class="modal adminuiux-modal fade" id="liquidateInvestmentModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog rounded-6">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h5">Liquidate Investment</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="liquidateForm" method="POST">
                @csrf

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="investment_id" id="investment_id_input">
                <input type="hidden" name="liquidation_amount" id="liquidation_amount_input">
                <input type="hidden" name="is_early_liquidation" id="is_early_liquidation_input">

                <div class="modal-body">
                    <!-- Early liquidation warning alert -->
                    <div class="alert alert-warning border rounded-4 d-none" id="early_liquidation_alert">
                        <!-- Will be populated by JavaScript -->
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border rounded-4">
                                <div class="card-body text-center">
                                    <h5 class="fw-normal">Projected Return</h5>
                                    <h1 class="mb-0 text-theme-1" id="investment_projected_return_amount">$0.00</h1>
                                    <p class="text-secondary small">
                                        Maturity Date: <span id="investment_maturity_date_display"></span>
                                    </p>
                                    <!-- Hidden input for investment amount -->
                                    <input type="hidden" id="investment_liquidation_amount">
                                    <p class="text-secondary small mb-0" id="liquidationSummaryText">
                                        <!-- Investment summary will be populated here -->
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation checkboxes for early liquidation -->
                    <div class="form-check mb-3 d-none" id="confirm_liquidation_container">
                        <input class="form-check-input" type="checkbox" name="confirm_liquidation" id="confirm_liquidation_checkbox">
                        <label class="form-check-label small" for="confirm_liquidation_checkbox">
                            I confirm I want to liquidate this investment before maturity
                        </label>
                    </div>

                    <div class="form-check mb-4 d-none" id="acknowledge_loss_container">
                        <input class="form-check-input" type="checkbox" name="acknowledge_loss" id="acknowledge_loss_checkbox">
                        <label class="form-check-label small" for="acknowledge_loss_checkbox">
                            I understand I will lose all accumulated profit and only receive my initial capital
                        </label>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-theme rounded-4" id="confirmliquidationBtn" disabled>
                        Confirm Liquidation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
