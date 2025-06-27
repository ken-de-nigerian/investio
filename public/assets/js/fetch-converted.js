// Convert Amount Functionality
document.addEventListener("DOMContentLoaded", function () {
    // ==================== Wallet Conversion Functionality ====================
    const walletArea = document.getElementById("wallet-area");
    const methodSelect = document.getElementById("method");
    const amountInput = document.getElementById("deposit-amount");
    const convertedAmountDisplay = document.getElementById("converted");
    const abbreviationDisplay = document.getElementById("abbreviation");
    const walletAddressInput = document.getElementById("walletAddress");
    const qrCodeImage = document.querySelector(".right-qr-code img");
    const depositBtn = document.getElementById("depositBtn");
    const depositForm = document.getElementById("depositForm");

    // Check if required elements exist
    if (!walletArea || !methodSelect || !amountInput || !convertedAmountDisplay ||
        !abbreviationDisplay || !walletAddressInput || !qrCodeImage || !depositForm) {
        return;
    }

    // Initialize state
    walletArea.style.display = "none";
    let controller = null;

    // Utility functions
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

    const iziToastSettings = {
        position: "topRight",
        timeout: 5000,
        resetOnHover: true,
        transitionIn: "flipInX",
        transitionOut: "flipOutX"
    };

    const showError = (message) => {
        iziToast.error({ ...iziToastSettings, message });
    };

    const showSuccess = (message) => {
        iziToast.success({ ...iziToastSettings, message });
    };

    // Fetch converted amount from server
    function fetchConvertedAmount() {
        const amount = parseFloat(amountInput.value.trim());
        const selectedOption = methodSelect.options[methodSelect.selectedIndex];
        const abbreviation = selectedOption?.getAttribute("data-abbreviation");
        const method = methodSelect.value;

        // Validate input
        if (isNaN(amount)) {
            convertedAmountDisplay.textContent = "0.00";
            return;
        }

        if (!abbreviation || !method) {
            return;
        }

        showLoader(convertedAmountDisplay);

        // Abort previous request if exists
        if (controller) controller.abort();
        controller = new AbortController();

        fetch('/wallet/fetch/converted', {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                amount: amount,
                abbreviation: abbreviation,
                method: method
            }),
            signal: controller.signal
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === "error") {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(errorMessage => showError(errorMessage));
                    convertedAmountDisplay.textContent = "0.00";
                    return;
                }

                convertedAmountDisplay.textContent = data.converted || "0.00";
                abbreviationDisplay.textContent = abbreviation;
            })
            .catch(error => {
                if (error.name !== "AbortError") {
                    console.error("Error fetching conversion:", error);
                    showError("An error occurred. Please try again.");
                    convertedAmountDisplay.textContent = "0.00";
                }
            });
    }

    const debouncedFetchConvertedAmount = debounce(fetchConvertedAmount, 800);

    // Handle method selection change
    methodSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const walletAddress = selectedOption.getAttribute("data-wallet");
        const abbreviation = selectedOption.getAttribute("data-abbreviation");

        if (walletAddress && abbreviation) {
            walletAddressInput.value = walletAddress;
            abbreviationDisplay.textContent = abbreviation;
            qrCodeImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(walletAddress)}`;
            walletArea.style.display = "block";

            // Only fetch conversion if amount is valid
            if (amountInput.value.trim() && !isNaN(amountInput.value)) {
                fetchConvertedAmount();
            }
        } else {
            walletArea.style.display = "none";
            convertedAmountDisplay.textContent = "0.00";
        }
    });

    // Handle amount input
    amountInput.addEventListener("input", function() {
        // Validate input - allow only numbers and decimal point
        this.value = this.value.replace(/[^0-9.]/g, '');

        // Remove multiple decimal points
        if ((this.value.match(/\./g) || []).length > 1) {
            this.value = this.value.substring(0, this.value.lastIndexOf('.'));
        }

        debouncedFetchConvertedAmount();
    });

    // ==================== Deposit Form Submission ====================
    depositForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const amount = parseFloat(amountInput.value.trim());
        const method = methodSelect.value;

        // Validate inputs
        if (isNaN(amount)) {
            amountInput.classList.add('is-invalid');
            return;
        }

        if (!method) {
            methodSelect.classList.add('is-invalid');
            return;
        }

        // Disable button and show loading state
        depositBtn.disabled = true;
        depositBtn.innerHTML = `<span class="spinner spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span> Processing...`;

        // Prepare form data
        const formData = new FormData(depositForm);
        formData.append('convertedAmount', convertedAmountDisplay.textContent.trim());

        // Submit form via AJAX
        fetch(this.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.status === "success") {
                    showSuccess(data.message);

                    // Close modal after success
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addmoneymodal'));
                    if (modal) modal.hide();

                    // Optional: Update wallet balance on page if needed
                    if (data.balance) {
                        const balanceElement = document.querySelector(".wallet-balance");
                        if (balanceElement) balanceElement.textContent = data.balance;
                    }

                    // Reset form
                    depositForm.reset();
                    walletArea.style.display = "none";
                    convertedAmountDisplay.textContent = "0.00";
                } else {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(errorMessage => showError(errorMessage));
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showError("An error occurred. Please try again.");
            })
            .finally(() => {
                depositBtn.disabled = false;
                depositBtn.textContent = "Deposit";
            });
    });

    // Remove validation errors on input
    amountInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });

    methodSelect.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });
});
