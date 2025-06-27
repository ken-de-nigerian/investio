// Goal Withdrawal
function confirmWithdraw(goal_id) {
    const modalElement = document.getElementById('withdrawModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    const withdrawBtn = document.getElementById('withdrawBtn');
    const withdrawForm = document.getElementById('withdrawForm');
    const fields = ['withdrawal_amount'];
    const amountInput = document.getElementById('withdrawal_amount');
    const displayAmount = document.getElementById('withdraw_display_amount');

    if (!withdrawBtn || !withdrawForm) return;

    const showError = (message) => {
        iziToast.error({...iziToastSettings, message});
    };

    const showSuccess = (message) => {
        iziToast.success({...iziToastSettings, message});
    };

    const formatAmount = (amount) => {
        const num = parseFloat(amount);
        if (isNaN(num)) return '$0.00';
        return `$${num.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    };

    // Function to handle amount input update
    const handleAmountInput = () => {
        if (amountInput && displayAmount) {
            displayAmount.textContent = formatAmount(amountInput.value);
        }
    };

    // Function to handle withdrawal
    const handleWithdraw = (event) => {
        event.preventDefault();

        withdrawBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        withdrawBtn.disabled = true;

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
            withdrawBtn.innerHTML = 'Confirm Withdrawal';
            withdrawBtn.disabled = false;
            return;
        }

        const formData = new FormData(withdrawForm);

        fetch(`/goal/${goal_id}/withdraw`, {
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
                    showSuccess(data.message || "Withdrawal processed successfully");
                    setTimeout(() => location.reload(), 3000);
                } else {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(showError);
                }
            })
            .catch(error => {
                console.error("Withdrawal error:", error);
                showError("Something went wrong. Please try again.");
            })
            .finally(() => {
                withdrawBtn.innerHTML = 'Confirm Withdrawal';
                withdrawBtn.disabled = false;
            });
    };

    // Function to handle input validation
    const handleValidation = (input) => {
        input.classList.remove('is-invalid');
    };

    // Remove any existing event listeners to prevent duplicates
    withdrawBtn.removeEventListener('click', handleWithdraw);
    if (amountInput) {
        amountInput.removeEventListener('input', handleAmountInput);
    }
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.removeEventListener('input', () => handleValidation(input));
        }
    });

    // Add new event listeners
    if (amountInput && displayAmount) {
        amountInput.addEventListener('input', handleAmountInput);
    }
    withdrawBtn.addEventListener('click', handleWithdraw);

    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('input', () => handleValidation(input));
        }
    });

    // Clean up event listeners when modal is hidden
    const cleanup = () => {
        if (amountInput && displayAmount) {
            amountInput.removeEventListener('input', handleAmountInput);
        }
        withdrawBtn.removeEventListener('click', handleWithdraw);
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.removeEventListener('input', () => handleValidation(input));
            }
        });
    };

    // Add cleanup on modal hide, ensuring it runs only once
    modalElement.removeEventListener('hidden.bs.modal', cleanup);
    modalElement.addEventListener('hidden.bs.modal', cleanup, { once: true });
}

// Goal Top Up
function confirmTopUp(goal_id) {
    const modalElement = document.getElementById('topUpModal');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    const topUpBtn = document.getElementById('topUpBtn');
    const topUpForm = document.getElementById('topUpForm');
    const fields = ['top_up_amount'];
    const amountInput = document.getElementById('top_up_amount');
    const displayAmount = document.getElementById('top_up_display_amount');

    if (!topUpBtn || !topUpForm) return;

    const showError = (message) => {
        iziToast.error({...iziToastSettings, message});
    };

    const showSuccess = (message) => {
        iziToast.success({...iziToastSettings, message});
    };

    const formatAmount = (amount) => {
        const num = parseFloat(amount);
        if (isNaN(num)) return '$0.00';
        return `$${num.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
    };

    // Function to handle amount input update
    const handleAmountInput = () => {
        if (amountInput && displayAmount) {
            displayAmount.textContent = formatAmount(amountInput.value);
        }
    };

    // Function to handle Top Up
    const handleTopUp = (event) => {
        event.preventDefault();

        topUpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        topUpBtn.disabled = true;

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
            topUpBtn.innerHTML = 'Confirm Top-Up';
            topUpBtn.disabled = false;
            return;
        }

        const formData = new FormData(topUpForm);

        fetch(`/goal/${goal_id}/fund`, {
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
                    showSuccess(data.message || "Top up processed successfully");
                    setTimeout(() => location.reload(), 3000);
                } else {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(showError);
                }
            })
            .catch(error => {
                console.error("Top up error:", error);
                showError("Something went wrong. Please try again.");
            })
            .finally(() => {
                topUpBtn.innerHTML = 'Confirm Top-Up';
                topUpBtn.disabled = false;
            });
    };

    // Function to handle input validation
    const handleValidation = (input) => {
        input.classList.remove('is-invalid');
    };

    // Remove any existing event listeners to prevent duplicates
    topUpBtn.removeEventListener('click', handleTopUp);
    if (amountInput) {
        amountInput.removeEventListener('input', handleAmountInput);
    }
    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.removeEventListener('input', () => handleValidation(input));
        }
    });

    // Add new event listeners
    if (amountInput && displayAmount) {
        amountInput.addEventListener('input', handleAmountInput);
    }
    topUpBtn.addEventListener('click', handleTopUp);

    fields.forEach(field => {
        const input = document.getElementById(field);
        if (input) {
            input.addEventListener('input', () => handleValidation(input));
        }
    });

    // Clean up event listeners when modal is hidden
    const cleanup = () => {
        if (amountInput && displayAmount) {
            amountInput.removeEventListener('input', handleAmountInput);
        }
        topUpBtn.removeEventListener('click', handleTopUp);
        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.removeEventListener('input', () => handleValidation(input));
            }
        });
    };

    // Add cleanup on modal hide, ensuring it runs only once
    modalElement.removeEventListener('hidden.bs.modal', cleanup);
    modalElement.addEventListener('hidden.bs.modal', cleanup, { once: true });
}
