const form = document.getElementById('forgot-password-form');
const favoriteMovieInput = document.getElementById('favorite_movie');
const passwordInput = document.getElementById('new_password');
const confirmPasswordInput = document.getElementById('confirm_password');
const passwordMismatchError = document.getElementById('password-mismatch-error');

form.addEventListener('submit', function(event) {
  if (passwordInput.value !== confirmPasswordInput.value) {
    event.preventDefault(); // Prevent form submission
    passwordMismatchError.style.display = 'block'; // Show error message
  } else {
    passwordMismatchError.style.display = 'none'; // Hide error message
  }
});
