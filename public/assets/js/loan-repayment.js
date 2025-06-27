// Loan Repayment
document.addEventListener('DOMContentLoaded', function () {
    const repayLoanBtn = document.getElementById('repayLoanBtn');
    const repayLoanForm = document.getElementById('repay-loan-form');
    const fields = ['repayment_amount', 'loan_id'];
    const loan_id = document.getElementById('loan_id').value.trim();
    const amountInput = document.getElementById('repayment_amount');
    const displayAmount = document.getElementById('display_amount');

    if (!repayLoanBtn || !repayLoanForm) return;

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
    if (amountInput && displayAmount) {
        amountInput.addEventListener('input', function () {
            displayAmount.textContent = formatAmount(this.value);
        });
    }

    // Initiate loan repayment
    repayLoanBtn.addEventListener('click', function (event) {
        event.preventDefault();

        repayLoanBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
        repayLoanBtn.disabled = true;

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
            repayLoanBtn.innerHTML = 'Repay Loan';
            repayLoanBtn.disabled = false;
            return;
        }

        const formData = new FormData(repayLoanForm);

        fetch(`/loan/${loan_id}/update`, {
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
                    showSuccess(data.message || "Loan repayment successfully");
                    setTimeout(() => location.reload(), 3000);
                } else {
                    const errors = Array.isArray(data.message) ? data.message : [data.message];
                    errors.forEach(showError);
                }
            })
            .catch(error => {
                console.error("Loan repayment error:", error);
                showError("Something went wrong. Please try again.");
            })
            .finally(() => {
                repayLoanBtn.innerHTML = 'Repay Loan';
                repayLoanBtn.disabled = false;
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
});
