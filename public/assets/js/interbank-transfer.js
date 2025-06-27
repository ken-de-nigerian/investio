// Interbank Transfer Functionality
document.addEventListener("DOMContentLoaded", function () {
    const otpArea = document.getElementById("otp_area");
    const accountNumber = document.getElementById("account_no");
    const transferAmount = document.getElementById("transfer_amount");
    const displayTransferAmount = document.querySelector(".display_amount");
    const recipientDisplay = document.querySelector(".recipient");
    const transferBtn = document.getElementById("interbankBtn");
    const transferForm = document.getElementById("interbank-transfer");

    if (!accountNumber || !transferAmount || !displayTransferAmount || !recipientDisplay || !transferBtn || !transferForm || !otpArea) return;

    let controller = null;
    otpArea.style.display = "none";

    // Debounce helper
    const debounce = (fn, delay) => {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    };

    const showLoader = (element) => {
        element.innerHTML = `<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>`;
    };

    const showError = (message) => {
        iziToast.error({ ...iziToastSettings, message });
    };

    const showSuccess = (message) => {
        iziToast.success({ ...iziToastSettings, message });
    };

    // Fetch account details
    function fetchAccountDetails() {
        const acctNum = accountNumber.value.trim();
        if (!acctNum || acctNum.length < 12) return;

        showLoader(recipientDisplay);

        if (controller) controller.abort();
        controller = new AbortController();

        fetch('/fetch/account-details', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            },
            body: JSON.stringify({ account_number: acctNum }),
            signal: controller.signal
        })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.status === "error") {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(showError);
                    recipientDisplay.textContent = "Invalid recipient";
                    return;
                }

                recipientDisplay.textContent = `to ${data.fullname || "Unknown"}`;
                otpArea.style.display = "block";
            })
            .catch(error => {
                if (error.name !== "AbortError") {
                    console.error("Fetch error:", error);
                    showError("Could not fetch account details. Please try again.");
                    recipientDisplay.textContent = "Recipient not found";
                    otpArea.style.display = "none";
                }
            });
    }

    const debouncedFetchAccountDetails = debounce(fetchAccountDetails, 600);

    // Event: on account number input
    accountNumber.addEventListener("input", debouncedFetchAccountDetails);

    // Event: update display amount live
    transferAmount.addEventListener("input", function () {
        // Clean input
        this.value = this.value.replace(/[^0-9.]/g, '');
        const value = parseFloat(this.value) || 0;
        displayTransferAmount.textContent = `$${value.toFixed(2)}`;
    });

    // Submit interbank transfer
    transferForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const amount = parseFloat(transferAmount.value.trim());
        const acctNum = accountNumber.value.trim();

        let hasError = false;

        // Validate Account Number
        if (!acctNum || acctNum.length < 8) {
            accountNumber.classList.add('is-invalid');
            hasError = true;
        } else {
            accountNumber.classList.remove('is-invalid');
        }

        // Validate Amount
        if (isNaN(amount) || amount <= 0) {
            transferAmount.classList.add('is-invalid');
            hasError = true;
        } else {
            transferAmount.classList.remove('is-invalid');
        }

        // Stop further execution if validation fails
        if (hasError) return;

        transferBtn.disabled = true;
        transferBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...`;

        const formData = new FormData(transferForm);

        fetch(transferForm.action, {
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

                    // Optionally hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('sendmoneymodal'));
                    if (modal) modal.hide();

                    // Optionally update balance
                    if (data.balance) {
                        const balanceElement = document.querySelector(".wallet-balance");
                        if (balanceElement) balanceElement.textContent = data.balance;
                    }

                    transferForm.reset();
                    displayTransferAmount.textContent = "$0.00";
                    recipientDisplay.textContent = "";
                    otpArea.style.display = "none";
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
                transferBtn.disabled = false;
                transferBtn.textContent = "Send Money";
            });
    });
});
