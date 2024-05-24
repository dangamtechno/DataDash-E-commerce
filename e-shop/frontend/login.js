const loginContainer = document.querySelector('.login-container');
const registerButton = document.querySelector('.register');
const loggedUser = document.querySelector('.login-status');

showHideIcon(registerButton, false);

function showHideIcon(icon, flag) {
  if (flag) {
    icon.style.display = 'none';
  } else {
    icon.style.display = 'block';
  }
}

// Add event listener to register button
registerButton.addEventListener('click', () => {
  showHideIcon(registerButton, true);
});
