let currentStep = 1;
const totalSteps = 5;
let selectedCategory = null;
let selectedImageFile = null; // Store the selected file globally

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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderCategories();
    updateStepDisplay();

    // Set minimum date to today
    const today = new Date();
    document.getElementById('targetDate').min = today.toISOString().split('T')[0];

    // Initialize calculation
    calculateSavings();

    // Set up reactive listeners
    setupReactiveListeners();

    // Set up image upload preview
    setupImageUploadPreview();
});

function setupImageUploadPreview() {
    const imageInput = document.getElementById('goalImage');
    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showError('Please select a valid image file (JPEG, PNG, GIF, or WebP)');
                    this.value = '';
                    selectedImageFile = null;
                    removeImagePreviewOnly();
                    return;
                }

                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    showError('Image file size must be less than 5MB');
                    this.value = '';
                    selectedImageFile = null;
                    removeImagePreviewOnly();
                    return;
                }

                // Store the file globally and create preview
                selectedImageFile = file;
                createImagePreview(file);
            } else {
                selectedImageFile = null;
                removeImagePreviewOnly();
            }
        });
    }
}

function createImagePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        // Remove existing preview if any (but keep the file)
        removeImagePreviewOnly();

        // Create preview container
        const previewContainer = document.createElement('div');
        previewContainer.className = 'image-preview mt-3';
        previewContainer.innerHTML = `
            <div class="position-relative d-inline-block">
                <img src="${e.target.result}" alt="Goal Image Preview"
                     class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle"
                        onclick="clearSelectedImage()" style="transform: translate(50%, -50%);">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    ${file.name} (${(file.size / 1024).toFixed(1)} KB)
                </small>
            </div>
        `;

        // Insert preview after the file input
        const imageInput = document.getElementById('goalImage');
        imageInput.parentNode.appendChild(previewContainer);
    };
    reader.readAsDataURL(file);
}

// Function to remove only the preview DOM element (keeps the file)
function removeImagePreviewOnly() {
    const existingPreview = document.querySelector('.image-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
}

// Function to completely clear the selected image (for user action)
function clearSelectedImage() {
    const existingPreview = document.querySelector('.image-preview');
    if (existingPreview) {
        existingPreview.remove();
    }

    // Clear the file input and global variable
    const imageInput = document.getElementById('goalImage');
    if (imageInput) {
        imageInput.value = '';
    }
    selectedImageFile = null;
}

// Legacy function for backward compatibility - now safer
function removeImagePreview() {
    // Only remove the preview DOM element, don't clear the file
    removeImagePreviewOnly();
}

function setupReactiveListeners() {
    let calculationTimeout;

    // Real-time calculation with debouncing for target amount, date, and current amount
    ['targetAmount', 'targetDate', 'currentAmount'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                clearTimeout(calculationTimeout);
                calculationTimeout = setTimeout(() => {
                    calculateSavings();
                    updateMonthlyTargetIfEmpty();
                }, 300);
            });
        }
    });

    // Handle manual monthly target input
    const monthlyTargetElement = document.getElementById('monthlyTarget');
    if (monthlyTargetElement) {
        monthlyTargetElement.addEventListener('input', function() {
            if (this.value) {
                updateMonthlyTargetPreview();
            }
        });
    }
}

function renderCategories() {
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.category-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            // Get data directly from attributes
            selectedCategory = {
                id: this.dataset.category,
                name: this.dataset.name,
                color: this.dataset.color,
                icon: this.dataset.icon
            };
        });
    });
}

// Navigation
document.getElementById('nextBtn').addEventListener('click', function() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            currentStep++;
            updateStepDisplay();
        }
    }
});

document.getElementById('prevBtn').addEventListener('click', function() {
    if (currentStep > 1) {
        currentStep--;
        updateStepDisplay();
    }
});

function updateStepDisplay() {

    // Hide all step contents
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.remove('active');
    });

    // Show the current step
    document.getElementById(`step-${currentStep}`).classList.add('active');

    // Update step indicators
    document.querySelectorAll('.step-item').forEach((item, index) => {
        item.classList.remove('active', 'completed');
        if (index + 1 < currentStep) {
            item.classList.add('completed');
            item.querySelector('.step-circle').innerHTML = '<i class="bi bi-check"></i>';
        } else if (index + 1 === currentStep) {
            item.classList.add('active');
            item.querySelector('.step-circle').textContent = index + 1;
        } else {
            item.querySelector('.step-circle').textContent = index + 1;
        }
    });

    // Update buttons
    document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
    document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
    document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'inline-block' : 'none';

    // Update calculations and review when navigating to specific steps
    if (currentStep === 3) {
        // When entering step 3 (calculation step), recalculate and update monthly target
        setTimeout(() => {
            calculateSavings();
            updateMonthlyTargetIfEmpty();
        }, 100);
    } else if (currentStep === 5) {
        updateReviewStep();
    }
}

function validateCurrentStep() {
    switch (currentStep) {
        case 1:
            if (!selectedCategory) {
                showError("Please select a goal category");
                return false;
            }
            break;
        case 2:
            const goalTitle = document.getElementById('goalTitle');
            if (!goalTitle.value.trim()) {
                goalTitle.classList.add('is-invalid');
                showError("Please enter a goal title");
                return false;
            } else {
                goalTitle.classList.remove('is-invalid');
            }
            break;
        case 3:
            const targetAmount = document.getElementById('targetAmount');
            const targetDate = document.getElementById('targetDate');
            let isValid = true;

            if (!targetAmount.value || parseFloat(targetAmount.value) <= 0) {
                targetAmount.classList.add('is-invalid');
                isValid = false;
            } else {
                targetAmount.classList.remove('is-invalid');
            }

            if (!targetDate.value) {
                targetDate.classList.add('is-invalid');
                isValid = false;
            } else {
                targetDate.classList.remove('is-invalid');
            }

            if (!isValid) {
                showError("Please enter valid target amount and date");
                return false;
            }
            break;
        case 4:
            // Validate image if uploaded
            if (selectedImageFile) {
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (!validTypes.includes(selectedImageFile.type)) {
                    showError('Please select a valid image file (JPEG, PNG, GIF, or WebP)');
                    return false;
                }

                if (selectedImageFile.size > maxSize) {
                    showError('Image file size must be less than 5MB');
                    return false;
                }
            }
            break;
    }
    return true;
}

function calculateSavings() {
    const targetAmount = parseFloat(document.getElementById('targetAmount').value) || 0;
    const currentAmount = parseFloat(document.getElementById('currentAmount').value) || 0;
    const targetDateValue = document.getElementById('targetDate').value;

    // Clear previous alerts
    const validationAlertsElement = document.getElementById('validationAlerts');
    if (validationAlertsElement) {
        validationAlertsElement.innerHTML = '';
    }

    // Validation
    const validationErrors = [];

    if (currentAmount > targetAmount && targetAmount > 0) {
        validationErrors.push('Current amount cannot be greater than target amount');
    }

    if (targetAmount <= 0) {
        showEmptyState();
        return null;
    }

    if (!targetDateValue) {
        showEmptyState();
        return null;
    }

    const targetDate = new Date(targetDateValue);
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time for accurate comparison

    if (targetDate <= today) {
        validationErrors.push('Target date must be in the future');
    }

    // Show validation errors
    if (validationErrors.length > 0) {
        showValidationErrors(validationErrors);
        showEmptyState();
        return null;
    }

    // Calculate savings
    const remainingAmount = Math.max(0, targetAmount - currentAmount);
    const timeDifference = targetDate - today;
    const daysRemaining = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
    const monthsRemaining = Math.max(1, Math.ceil(daysRemaining / 30));
    const weeksRemaining = Math.ceil(daysRemaining / 7);

    const monthlyRequired = remainingAmount / monthsRemaining;
    const weeklyRequired = remainingAmount / weeksRemaining;
    const dailyRequired = remainingAmount / daysRemaining;

    // Calculate progress percentage
    const progressPercentage = targetAmount > 0 ? (currentAmount / targetAmount) * 100 : 0;

    const calculationData = {
        remainingAmount,
        monthsRemaining,
        weeksRemaining,
        daysRemaining,
        monthlyRequired,
        weeklyRequired,
        dailyRequired,
        progressPercentage,
        targetAmount,
        currentAmount
    };

    // Update display
    displayCalculationResults(calculationData);

    return calculationData;
}

function updateMonthlyTargetIfEmpty() {
    const monthlyTargetInput = document.getElementById('monthlyTarget');
    if (!monthlyTargetInput) return;

    // Only auto-update if the field is empty or if the user hasn't manually set a value
    const calculationData = calculateSavings();
    if (calculationData && calculationData.monthlyRequired > 0) {
        if (!monthlyTargetInput.dataset.userModified) {
            monthlyTargetInput.value = calculationData.monthlyRequired.toFixed(2);
        }
    }
}

// Track when a user manually modifies monthly target
document.addEventListener('DOMContentLoaded', function() {
    const monthlyTargetInput = document.getElementById('monthlyTarget');
    if (monthlyTargetInput) {
        monthlyTargetInput.addEventListener('focus', function() {
            this.dataset.userModified = 'true';
        });

        // Reset a user modification flag when other inputs change significantly
        ['targetAmount', 'targetDate'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', function() {
                    // Reset the flag when major inputs change
                    if (monthlyTargetInput.dataset.userModified) {
                        delete monthlyTargetInput.dataset.userModified;
                    }
                });
            }
        });
    }
});

function displayCalculationResults(data) {
    const resultsDiv = document.getElementById('calculationResults');
    if (!resultsDiv) return;

    resultsDiv.innerHTML = `
        <div class="row g-3">
            <div class="col-md-4 col-sm-6">
                <div class="calculation-card">
                    <p class="calculation-label">Remaining to Save</p>
                    <div class="calculation-value text-warning">$${data.remainingAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="calculation-card">
                    <p class="calculation-label">Time Remaining</p>
                    <div class="calculation-value text-info">${data.daysRemaining} days</div>
                    <small class="text-light">(${data.monthsRemaining} months, ${data.weeksRemaining} weeks)</small>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="calculation-card">
                    <p class="calculation-label">Monthly Required</p>
                    <div class="calculation-value text-success">$${data.monthlyRequired.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="calculation-card">
                    <p class="calculation-label">Weekly Required</p>
                    <div class="calculation-value text-primary">$${data.weeklyRequired.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="calculation-card">
                    <p class="calculation-label">Daily Required</p>
                    <div class="calculation-value text-secondary">$${data.dailyRequired.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-light">Progress: ${data.progressPercentage.toFixed(1)}%</small>
                <small class="text-light">$${data.currentAmount.toLocaleString()} of $${data.targetAmount.toLocaleString()}</small>
            </div>
            <div class="progress" style="height: 8px; background: rgba(255,255,255,0.2);">
                <div class="progress-bar" role="progressbar"
                     style="width: ${Math.min(100, data.progressPercentage)}%; background: rgba(255,255,255,0.8);"
                     aria-valuenow="${data.progressPercentage}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    `;
}

function showEmptyState() {
    const resultsDiv = document.getElementById('calculationResults');
    if (!resultsDiv) return;

    resultsDiv.innerHTML = `
        <div class="empty-state">
            <div>📊</div>
            <p class="mb-0">Enter your target amount and date to see the calculation preview</p>
        </div>
    `;
}

function showValidationErrors(errors) {
    const alertsDiv = document.getElementById('validationAlerts');
    if (!alertsDiv) return;

    alertsDiv.innerHTML = errors.map(error => `
        <div class="alert alert-warning alert-custom mt-3" role="alert">
            <i class="bi bi-exclamation-triangle"></i> ${error}
        </div>
    `).join('');
}

function updateMonthlyTargetPreview() {
    const monthlyTarget = parseFloat(document.getElementById('monthlyTarget').value) || 0;
    const targetAmount = parseFloat(document.getElementById('targetAmount').value) || 0;
    const currentAmount = parseFloat(document.getElementById('currentAmount').value) || 0;

    if (monthlyTarget > 0 && targetAmount > 0) {
        const remainingAmount = targetAmount - currentAmount;
        const monthsToGoal = Math.ceil(remainingAmount / monthlyTarget);

        // Could show additional preview for custom monthly target
        console.log(`With $${monthlyTarget}/month, you'll reach your goal in ${monthsToGoal} months`);
    }
}

function updateReviewStep() {
    const reviewTitle = document.getElementById('reviewTitle');
    const reviewDescription = document.getElementById('reviewDescription');
    const reviewTargetAmount = document.getElementById('reviewTargetAmount');
    const reviewTargetDate = document.getElementById('reviewTargetDate');
    const reviewMonthlyTarget = document.getElementById('reviewMonthlyTarget');
    const reviewCategoryIcon = document.getElementById('reviewCategoryIcon');
    const reviewCategoryName = document.getElementById('reviewCategoryName');

    if (reviewTitle) reviewTitle.textContent = document.getElementById('goalTitle').value || '';
    if (reviewDescription) reviewDescription.textContent = document.getElementById('goalDescription').value || 'No description provided';
    if (reviewTargetAmount) reviewTargetAmount.textContent = '$' + (parseFloat(document.getElementById('targetAmount').value) || 0).toFixed(2);
    if (reviewTargetDate) reviewTargetDate.textContent = document.getElementById('targetDate').value || '';
    if (reviewMonthlyTarget) reviewMonthlyTarget.textContent = '$' + (parseFloat(document.getElementById('monthlyTarget').value) || 0).toFixed(2);

    if (selectedCategory) {
        if (reviewCategoryIcon) reviewCategoryIcon.innerHTML = `<i class="bi bi-${selectedCategory.icon}"></i>`;
        if (reviewCategoryName) reviewCategoryName.textContent = selectedCategory.name;
    }

    // Update image preview in review step
    updateReviewImagePreview();
}

function updateReviewImagePreview() {
    const reviewImageContainer = document.getElementById('reviewImagePreview');

    if (reviewImageContainer) {
        if (selectedImageFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                reviewImageContainer.innerHTML = `
                    <h6>Goal Image</h6>
                    <img src="${e.target.result}" alt="Goal Image"
                         class="img-thumbnail" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                    <div class="mt-2">
                        <small class="text-muted">
                            ${selectedImageFile.name} (${(selectedImageFile.size / 1024).toFixed(1)} KB)
                        </small>
                    </div>
                `;
            };
            reader.onerror = function(e) {
                console.error('FileReader error:', e);
                reviewImageContainer.innerHTML = `
                    <h6>Goal Image</h6>
                    <p class="text-danger">Error loading image preview</p>
                `;
            };
            reader.readAsDataURL(selectedImageFile);
        } else {
            reviewImageContainer.innerHTML = `
                <h6>Goal Image</h6>
                <p class="text-muted">No image uploaded</p>
            `;
        }
    }
}

// Form submission
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(event) {
            event.preventDefault();

            // Check if the file input still has the file (recovery mechanism)
            const imageInput = document.getElementById('goalImage');
            if (imageInput && imageInput.files.length > 0) {
                // Use the file from input if selectedImageFile is null but input has file
                if (!selectedImageFile && imageInput.files[0]) {
                    selectedImageFile = imageInput.files[0];
                }
            }

            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            this.disabled = true;

            const milestones = Array.from(document.querySelectorAll('.milestone-check:checked')).map(cb => cb.value);

            const formData = new FormData();
            formData.append('category_id', selectedCategory?.id || '');
            formData.append('title', document.getElementById('goalTitle').value || '');
            formData.append('description', document.getElementById('goalDescription').value || '');
            formData.append('target_amount', document.getElementById('targetAmount').value || '');
            formData.append('current_amount', document.getElementById('currentAmount').value || '');
            formData.append('target_date', document.getElementById('targetDate').value || '');
            formData.append('priority', document.getElementById('goalPriority').value || '');
            formData.append('monthly_target', document.getElementById('monthlyTarget').value || '');
            formData.append('is_public', document.getElementById('isPublic').checked || false);
            formData.append('milestones', JSON.stringify(milestones));

            // Add image file to form data using the global variable
            if (selectedImageFile) {
                formData.append('goal_image', selectedImageFile);
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            fetch('/goal/store', {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": csrfToken ? csrfToken.content : '',
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        showSuccess(data.message || "Goal created successfully!");

                        setTimeout(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        }, 3000);
                    } else {
                        const errors = Array.isArray(data.message) ? data.message : [data.message];
                        errors.forEach(showError);
                    }
                })
                .catch(error => {
                    console.error("Goal creation error:", error);
                    showError("Something went wrong. Please try again.");
                })
                .finally(() => {
                    this.innerHTML = '<i class="bi bi-check-lg"></i> Create Goal';
                    this.disabled = false;
                });
        });
    }
});

// Make functions globally available
window.clearSelectedImage = clearSelectedImage;
window.removeImagePreview = removeImagePreview;
