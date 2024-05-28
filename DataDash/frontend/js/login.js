const loginButton = document.querySelector('.login');
const registerButton = document.querySelector('.register');
const logoutButton = document.querySelector('.logout');
const loggedUser = document.querySelector('.logged-user');

// Check if a session exists using AJAX
fetch('get_session.php')
  .then(response => response.text())
  .then(data => {
    if (data === 'true') {
      // Get the username using AJAX
      fetch('get_username.php')
        .then(response => response.text())
        .then(username => {
          loggedUser.textContent = `Logged in as ${username}`;

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
