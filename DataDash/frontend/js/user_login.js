const login = document.querySelector(".login");
const logout = document.querySelector(".logout");
const loggedUser = document.querySelector(".logged-user");
const register = document.querySelector(".register");
const loggedUserSpan = document.querySelector('.username');
loggedUserSpan.innerHTML = 'guest';
login.addEventListener('click',userLogin);
logout.addEventListener('click',userLogout);
//displayLoginRegisterIcons();
function checkLoginStatus(){
    fetchCall('user_login.php?q=check_status',responseLogin)
    function responseLogin(data){
        console.log(data.user);
        data.user!='guest'  &&  displayLoggedUser(data.user);
        data.user=='guest'  &&  displayLoginRegisterIcons();
    }
}

function userLogout(){
    fetchCall('user_login.php',responseLogout)
    function responseLogout(data){
        console.log(data);
        alert('logged out succesfully');
        data.logout && displayLoginRegisterIcons();
    }
}




function displayLoginRegisterIcons(){
    showHideIcon(login,false);
    showHideIcon(register,false);
    showHideIcon(loggedUser,false);
    showHideIcon(logout,true);
      //update username to logged username
   const loggedUserSpan = document.querySelector('.username');
   loggedUserSpan.textContent = '';
}
function displayLoggedUser(user){
    removeOverlay();
   //hide register & login button from user
   showHideIcon(login,true);
   showHideIcon(register,true);
   showHideIcon(loggedUser,false);
   showHideIcon(logout,false);

   console.log(user.name);
   //update username to logged username
   const loggedUserSpan = document.querySelector('.username');
   loggedUserSpan.textContent = user.name;
   //alert('login successfully');
   


}

function showHideIcon(icon,flag){
    if(icon){
        flag ? (icon.style.display = "none") : (icon.style.display = "block");
    }
}
function userLogin(e){
    e.preventDefault();
    const formDiv = document.createElement('div');
    formDiv.className = 'form-div';
    const h2 = document.createElement('h2');
    h2.textContent = 'login-form';
    formDiv.appendChild(h2);
    const loginForm = document.createElement('form');
    loginForm.className = 'login-form';
    const username = document.createElement('input');
    username.type ='text';
    username.name = 'email';
    username.placeholder = 'user name';
    const password = document.createElement('input');
    password.type='password';
    password.name='password_hashed';
    password.placeholder='password';
    const submit = document.createElement('input');
    submit.name='Login';
    submit.type='submit';
    submit.addEventListener('click',submitLoginRequest);
    loginForm.appendChild(username);
    loginForm.appendChild(password);
    loginForm.appendChild(submit);
    formDiv.appendChild(loginForm);
    displayOverlay(formDiv);
}
function submitLoginRequest(e){
    e.preventDefault();
    form = document.querySelector('.login-form');
    const formData = new FormData(form);
    fetchCall('user_login.php',userLoginResponse,'POST',formData)
    function userLoginResponse(data){
        console.log(data);
        if(data.user) displayLoggedUser(data.user);
        else{alert('error');}
    }
}

