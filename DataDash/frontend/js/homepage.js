/*if (sessionExists()) {
    $username = getSessionUserID();
    $newProductsText = "$username's New Products";
} else {
    $newProductsText = "New Products";
}

const xhr = new XMLHttpRequest();

xhr.onload = function() {
  if (xhr.status === 200) {
    const response = JSON.parse(xhr.responseText);
    if (response.logged_in) {
      const username = response.username;
      document.getElementById('username').innerHTML = `Welcome, ${username}!`;
    } else {
      document.getElementById('username').innerHTML = 'Welcome, guest!';
    }
  }
};

xhr.open('GET', 'check_login.php', true);
xhr.send();

 */