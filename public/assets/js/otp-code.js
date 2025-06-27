// Verify OTP
document.addEventListener('DOMContentLoaded', function () {
    const codeInput = document.getElementById('code');
    const otpBoxes = document.querySelectorAll('#otpDisplay .otp-box');
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

            if (key.id === 'clearKey') {
                codeInput.value = '';
            } else if (key.id === 'backspaceKey') {
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
    const resendOtp = document.getElementById('resendOtp');

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
