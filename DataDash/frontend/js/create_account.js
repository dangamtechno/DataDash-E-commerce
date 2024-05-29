const form = document.getElementById('create-account-form');
const passwordInput = document.getElementById('pass');
const confirmPassInput = document.getElementById('confirm-pass');

form.addEventListener('submit', (event) => {
    if (passwordInput.value !== confirmPassInput.value) {
        alert('Passwords do not match');
        event.preventDefault();
    }
});