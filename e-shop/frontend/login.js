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

// Check if a session exists using AJAX
fetch('check_session.php')
  .then(response => response.text())
  .then(data => {
    if (data === 'true') {
      // Get the user ID using AJAX
      fetch('get_user_id.php')
        .then(response => response.text())
        .then(userId => {
          loggedUser.textContent = `Logged in as ${userId}`;
          loginContainer.style.display = 'none';
        });
    }
  });
