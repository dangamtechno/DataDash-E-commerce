const loginButton = document.querySelector('.login');
const registerButton = document.querySelector('.register');
const logoutButton = document.querySelector('.logout');
const loggedUser = document.querySelector('.logged-user');

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
          loginButton.style.display = 'none'; // Hide only the login button
          registerButton.style.display = 'none'; // Hide only the register button
        });
    }
  });

function showHideIcon(icon, flag) {
  if (flag) {
    icon.style.display = 'none';
  } else {
    icon.style.display = 'block';
  }
}
