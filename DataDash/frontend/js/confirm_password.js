const form = document.getElementById('create-account-form');
const passwordInput = document.getElementById('pass');const confirmPasswordInput = document.getElementById('confirm-pass');
const passwordMismatchError = document.getElementById('password-mismatch-error');

form.addEventListener('submit', function(event) {
    if (passwordInput.value !== confirmPasswordInput.value) {
        event.preventDefault(); // Prevent form submission
        passwordMismatchError.style.display = 'block'; // Show error message
    } else {
        passwordMismatchError.style.display = 'none'; // Hide error message
    }
});
