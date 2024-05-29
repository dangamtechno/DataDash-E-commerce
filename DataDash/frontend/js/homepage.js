// Check if there is a session
if (sessionStorage.getItem('session')) {
  const username = sessionStorage.getItem('username');
  const loginButton = document.getElementById('login-button');
  loginButton.innerHTML = `
    <i class="fas fa-user-check fa-2x"></i>
    <a href="/account">${username}</a>
  `;
}

// Logout function
function logoutUser() {
  // Check if there is a session
  if (sessionStorage.getItem('session')) {
    // Log the user out and redirect to login_page.html
    sessionStorage.removeItem('session');
    window.location.href = 'login_page.html';
  } else {
    // Redirect to homepage.html
    window.location.href = 'homepage.html';
  }
}