// Upload Profile Picture
document.addEventListener('DOMContentLoaded', function() {
    const profileImageInput = document.getElementById('profile-image-input');
    const profileImagePreview = document.getElementById('profile-image-preview');
    const fileErrorAlert = document.getElementById('file-error');
    const fileErrorMessage = document.getElementById('file-error-message');
    const fileSuccessAlert = document.getElementById('file-success');
    const updateForm = document.getElementById('update-profile-picture-form');

    // Show error in Bootstrap alert
    function showError(message) {
        if (!fileErrorAlert || !fileErrorMessage) return;
        if (fileSuccessAlert) {
            const bsSuccess = bootstrap.Alert.getInstance(fileSuccessAlert);
            if (bsSuccess) bsSuccess.close();
            else fileSuccessAlert.style.display = 'none';
        }
        fileErrorMessage.textContent = message;
        fileErrorAlert.style.display = 'block';
        new bootstrap.Alert(fileErrorAlert);
    }

    // Hide error alert
    function hideError() {
        if (!fileErrorAlert) return;
        const bsAlert = bootstrap.Alert.getInstance(fileErrorAlert);
        if (bsAlert) {
            bsAlert.close();
        } else {
            fileErrorAlert.style.display = 'none';
        }
    }

    // Image preview functionality
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            if (!e.target.files?.length) return;
            hideError();

            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                showError('Only JPG and PNG images are allowed.');
                return;
            }

            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                showError('Image must be less than 2MB.');
                return;
            }

            // Create preview
            const reader = new FileReader();
            reader.onload = function(event) {
                if (profileImagePreview) {
                    profileImagePreview.src = event.target.result;
                }
                if (updateForm) {
                    uploadProfilePicture(file);
                }
            };
            reader.onerror = function() {
                showError('Error reading image file.');
            };
            reader.readAsDataURL(file);
        });
    }

    // Handle form submission with progress
    function uploadProfilePicture(file) {
        if (!updateForm) return;

        const formData = new FormData();
        formData.append('profile_image', file);
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) {
            formData.append('_token', tokenInput.value);
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', updateForm.action, true);

        xhr.onload = function() {
            try {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.status === 'success') {
                    iziToast.success({
                        ...iziToastSettings,
                        message: response.message || 'Profile picture updated successfully!'
                    });
                    setTimeout(() => location.reload(), 3000);
                } else {
                    iziToast.error({
                        ...iziToastSettings,
                        message: response.message || 'Upload failed.'
                    });
                }
            } catch (e) {
                iziToast.error({
                    ...iziToastSettings,
                    message: e || 'Error processing server response.'
                });
                console.error(e);
            }
        };

        xhr.onerror = function() {
            iziToast.error({
                ...iziToastSettings,
                message: 'Network error occurred. Please try again.'
            });
        };

        xhr.send(formData);
    }

    // Initialize Bootstrap alert dismiss functionality
    if (fileErrorAlert) {
        fileErrorAlert.addEventListener('closed.bs.alert', function() {
            fileErrorAlert.style.display = 'none';
        });
    }

    if (fileSuccessAlert) {
        fileSuccessAlert.addEventListener('closed.bs.alert', function() {
            fileSuccessAlert.style.display = 'none';
        });
    }

    // Initialize Cleave for phone number if element exists
    const phoneNumberInput = document.getElementById('phone_number');
    if (phoneNumberInput && typeof Cleave !== 'undefined') {
        new Cleave('#phone_number', {
            numericOnly: true,
            blocks: [0, 3, 0, 4, 4],
            delimiters: ['(', ')', ' ', '-', ' '],
            maxLength: 16
        });
    }
});
