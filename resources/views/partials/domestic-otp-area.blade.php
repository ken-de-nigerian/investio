<div class="d-flex justify-content-center align-items-center mb-3">
    <span class="form-label fw-semibold text-center">Enter Transfer Otp</span>
</div>

<!-- Display for the OTP digits -->
<div id="domOtpDisplay" class="d-flex gap-2 mb-3">
    <div class="otp-box">_</div>
    <div class="otp-box">_</div>
    <div class="otp-box">_</div>
    <div class="otp-box">_</div>
</div>

<!-- Hidden actual input -->
<input type="hidden" name="code" id="dom_code" maxlength="4" />

<!-- Keypad -->
<div class="keypad-grid">
    <button type="button" class="keypad-key">1</button>
    <button type="button" class="keypad-key">2</button>
    <button type="button" class="keypad-key">3</button>
    <button type="button" class="keypad-key">4</button>
    <button type="button" class="keypad-key">5</button>
    <button type="button" class="keypad-key">6</button>
    <button type="button" class="keypad-key">7</button>
    <button type="button" class="keypad-key">8</button>
    <button type="button" class="keypad-key">9</button>
    <button type="button" class="keypad-key" id="domClearKey">C</button>
    <button type="button" class="keypad-key">0</button>
    <button type="button" class="keypad-key" id="domBackspaceKey"><i class="bi bi-backspace"></i></button>
</div>

<div class="d-flex justify-content-center align-items-center mt-4">
    <a id="domResendOtp" href="javascript:void(0)" class="text-decoration-underline small text-center">Click to resend</a>
</div>
