const showError = (message) => {
    iziToast.error({...iziToastSettings, message});
};

const showSuccess = (message) => {
    iziToast.success({...iziToastSettings, message});
};

// Helper function to handle form submission
function handleDeleteForm(form, submitButton, actionUrl, successMessage) {
    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
    submitButton.disabled = true;

    const formData = new FormData(form);

    fetch(actionUrl, {
        method: "DELETE",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
        }
    })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                showSuccess(data.message || successMessage);
                setTimeout(() => location.reload(), 3000);
            } else {
                const errors = Array.isArray(data.message) ? data.message : [data.message];
                errors.forEach(showError);
            }
        })
        .catch(error => {
            showError(error || "Something went wrong. Please try again.");
        })
        .finally(() => {
            submitButton.innerHTML = 'Delete';
            submitButton.disabled = false;
        });
}

// Delete Deposit
function deleteDeposit(deposit_id) {
    const modalElement = document.getElementById('deleteDepositModal');
    const form = document.getElementById('deleteDepositForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/deposits/${deposit_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    // Remove any existing submit listeners
    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Deposit deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}

// Delete Interbank Transfer
function deleteInterBankTransfer(transfer_id) {
    const modalElement = document.getElementById('deleteInterbankTransferModal');
    const form = document.getElementById('deleteInterbankTransferForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/interbank/${transfer_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Interbank transfer deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}

// Delete Domestic Transfer
function deleteDomesticTransfer(transfer_id) {
    const modalElement = document.getElementById('deleteDomesticTransferModal');
    const form = document.getElementById('deleteDomesticTransferForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/domestic/${transfer_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Domestic transfer deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}

// Delete Wire Transfer
function deleteWireTransfer(transfer_id) {
    const modalElement = document.getElementById('deleteWireTransferModal');
    const form = document.getElementById('deleteWireTransferForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/wire/${transfer_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Wire transfer deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}

// Delete Goal
function deleteGoal(goal_id) {
    const modalElement = document.getElementById('deleteGoalModal');
    const form = document.getElementById('deleteGoalForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/goals/${goal_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Goal deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}

// Delete Loan
function deleteLoan(loan_id) {
    const modalElement = document.getElementById('deleteLoanModal');
    const form = document.getElementById('deleteLoanForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/loans/${loan_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Loan deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}

// Delete Investment
function deleteInvestment(investment_id) {
    const modalElement = document.getElementById('deleteInvestmentModal');
    const form = document.getElementById('deleteInvestmentForm');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !submitButton) {
        return;
    }

    form.action = `/admin/investments/${investment_id}/delete`;
    const modal = new bootstrap.Modal(modalElement);
    modal.show();

    form.removeEventListener('submit', form._submitHandler);
    form._submitHandler = (event) => {
        event.preventDefault();
        handleDeleteForm(form, submitButton, form.action, "Investment deleted successfully");
    };
    form.addEventListener('submit', form._submitHandler);
}
