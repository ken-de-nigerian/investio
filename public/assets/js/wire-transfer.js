// Wire Transfer
document.addEventListener('DOMContentLoaded', function () {
    const wireBtn = document.getElementById('wireBtn');
    const wireForm = document.getElementById('wire-transfer');
    const storeWireTransferForm = document.getElementById('storeWireTransfer');
    const wireTransferStoreBtn  = document.getElementById('wireTransferStoreBtn');
    const fields = ['acct_name', 'account_number', 'bank_name', 'wire_transfer_amount', 'acct_type', 'acct_remarks', 'acct_country', 'acct_swift', 'acct_routing'];
    const confirmAmount = document.getElementById('confirm-amount');
    const confirmName = document.getElementById('confirm-name');
    const amountInput = document.getElementById('wire_transfer_amount');
    const nameInput = document.getElementById('acct_name');

    if (!wireBtn || !wireForm) return;

    const showError = (message) => {
        iziToast.error({ ...iziToastSettings, message });
    };

    const showSuccess = (message) => {
        iziToast.success({ ...iziToastSettings, message });
    };

    const formatAmount = (amount) => {
        const num = parseFloat(amount);
        if (isNaN(num)) return '$0.00';
        return `$${num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    };

    // Live amount update
    if (amountInput && confirmAmount) {
        amountInput.addEventListener('input', function () {
            confirmAmount.textContent = formatAmount(this.value);
        });
    }

    // Live name update
    if (nameInput && confirmName) {
        nameInput.addEventListener('input', function () {
            confirmName.textContent = this.value || "Recipient";
        });
    }

    // Initiate wire transfer
    wireBtn.addEventListener('click', function (event) {
        event.preventDefault();

        wireBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        wireBtn.disabled = true;

        let isValid = true;

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (!input) return;
            const value = input.value.trim();

            if (value === '') {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            wireBtn.innerHTML = 'Transfer';
            wireBtn.disabled = false;
            return;
        }

        const formData = new FormData(wireForm);

        fetch('/wire/transfer/create', {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showSuccess(data.message || "Transfer initiated successfully");

                    // Trigger modal if needed
                    const modalEl = document.getElementById('wireOtpValidationModal');
                    if (modalEl) {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                } else {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(showError);
                }
            })
            .catch(error => {
                console.error("Transfer error:", error);
                showError("Something went wrong. Please try again.");
            })
            .finally(() => {
                wireBtn.innerHTML = 'Transfer';
                wireBtn.disabled = false;
            });
    });

    // Clear validation styles on input
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('input', function () {
                input.classList.remove('is-invalid');
            });
        }
    });

    // Submit & store wire transfer
    wireTransferStoreBtn.addEventListener("click", function (e) {
        e.preventDefault();

        const code = document.getElementById('wire_code').value.trim();

        if (!code || code.length < 4) {
            showError("Enter a valid otp code");
            return;
        }

        wireTransferStoreBtn.disabled = true;
        wireTransferStoreBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...`;

        const formData = new FormData(storeWireTransferForm);

        fetch(storeWireTransferForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    showSuccess(data.message || "Transfer successful");
                    setTimeout(() => window.location.href = data.redirect, 3000);
                } else {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(showError);
                }
            })
            .catch(error => {
                console.error("Transfer error:", error);
                showError("Something went wrong. Please try again.");
            })
            .finally(() => {
                wireTransferStoreBtn.disabled = false;
                wireTransferStoreBtn.textContent = "Complete Transfer";
            });
    });
});

// Verify OTP
document.addEventListener('DOMContentLoaded', function () {
    const codeInput = document.getElementById('wire_code');
    const otpBoxes = document.querySelectorAll('#wireOtpDisplay .otp-box');
    const keypadKeys = document.querySelectorAll('.keypad-key');

    if (!codeInput || !otpBoxes.length || !keypadKeys.length) return;

    function updateDisplay() {
        const code = codeInput.value;

        otpBoxes.forEach((box, i) => {
            box.textContent = code[i] || '_';
        });

        if (code.length < 4 || code === '') {
            otpBoxes.forEach(box => {
                // Add fade-out effect
                box.classList.add('fade-out');

                setTimeout(() => {
                    box.classList.remove('border-success', 'border-danger', 'shake', 'fade-out');
                    box.style.opacity = '';
                }, 300);
            });
        }
    }

    function setOtpBoxStatus(status) {
        otpBoxes.forEach(box => {
            box.classList.remove('border-success', 'border-danger', 'shake');

            if (status === 'success') {
                box.classList.add('border-success', 'shake');
            } else if (status === 'error') {
                box.classList.add('border-danger', 'shake');
            }

            setTimeout(() => {
                box.classList.remove('shake');
            }, 400);
        });
    }

    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    const verifyOtp = debounce(function () {
        const code = codeInput.value;

        if (code.length !== 4) return;

        fetch('/verify/otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ otp: code })
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    setOtpBoxStatus('success');
                } else {
                    setOtpBoxStatus('error');
                }
            })
            .catch(() => {
                setOtpBoxStatus('error');
            })
    }, 600);

    keypadKeys.forEach(key => {
        key.addEventListener('click', () => {
            const keyValue = key.textContent.trim();
            let currentValue = codeInput.value;

            if (key.id === 'wireClearKey') {
                codeInput.value = '';
            } else if (key.id === 'wireBackspaceKey') {
                codeInput.value = currentValue.slice(0, -1);
            } else if (!isNaN(keyValue) && currentValue.length < 4) {
                codeInput.value += keyValue;
            }

            updateDisplay();
            verifyOtp(); // Triggers debounce
        });
    });

    updateDisplay();
});

// Resend OTP
document.addEventListener('DOMContentLoaded', function () {
    const resendOtp = document.getElementById('wireResendOtp');

    if (!resendOtp) {
        return;
    }

    let url = `/resend/otp`;

    const element = resendOtp; // Use the available element
    const originalText = element.innerHTML;

    function startCountdown(expiryTime) {
        const interval = setInterval(() => {
            const now = new Date().getTime();
            const expiry = new Date(expiryTime).getTime();
            const remainingTime = expiry - now;

            if (remainingTime > 0) {
                const minutes = Math.floor((remainingTime / 1000 / 60) % 60);
                const seconds = Math.floor((remainingTime / 1000) % 60);
                element.innerHTML = `Resend in ${minutes}m ${seconds}s`;
                element.style.pointerEvents = 'none';
                element.style.opacity = '0.6';
            } else {
                clearInterval(interval);
                element.innerHTML = originalText;
                element.style.pointerEvents = 'auto';
                element.style.opacity = '1';
                localStorage.removeItem('otpExpiry');
            }
        }, 1000);
    }

    const storedExpiry = localStorage.getItem('otpExpiry');
    if (storedExpiry) {
        startCountdown(storedExpiry);
    }

    element.addEventListener('click', function (event) {
        event.preventDefault();

        element.innerHTML = 'Resending...';
        element.style.pointerEvents = 'none';
        element.style.opacity = '0.6';

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    iziToast.success({ ...iziToastSettings, message: data.message });
                    localStorage.setItem('otpExpiry', data.otpExpiry);
                    startCountdown(data.otpExpiry);
                } else {
                    iziToast.error({ ...iziToastSettings, message: data.message || 'Something went wrong' });
                    element.innerHTML = originalText;
                    element.style.pointerEvents = 'auto';
                    element.style.opacity = '1';
                }
            })
            .catch(error => {
                iziToast.error({
                    ...iziToastSettings,
                    message: error || 'An error occurred while resending OTP. Please try again.'
                });
                element.innerHTML = originalText;
                element.style.pointerEvents = 'auto';
                element.style.opacity = '1';
            });
    });
});

