
const logout = document.querySelector('.logout');
const loggedUser = document.querySelector('.logged-user');

logout.addEventListener('click',adminLogout);
function showHideIcon(icon,flag){
    if(icon){
        flag ? (icon.style.display = "none") : (icon.style.display = "block");
    }
}

function checkLoginStatus(){
    fetchCall('login.php?q=check_status',responseLogin)
    function responseLogin(data){
        console.log(data);  

        if(data.admin){
            console.log(data);  
            displayLoggedUser(data.admin);
        }
        else displayLoginRegisterIcons();
    }
}


function adminLogout(){
    fetchCall('login.php',responseLogout)
    function responseLogout(data){
        const main = document.querySelector('.main-div');
        main.innerHTML='';
        displayLoginRegisterIcons();
        createAdminLogin();
    }
}


function createAdminLogin(){
    user = document.querySelector('.username').textContent;
    console.log(user);
        const main = document.querySelector('.main-div');
        const formDiv = document.createElement('div');
        formDiv.className='form-div';
        const h2 = document.createElement('h2');
        h2.textContent = 'Admin-login-form';
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
        main.appendChild(formDiv);
        createRegisterLink();
        getPasswordButton(formDiv);   
}
function displayLoggedUser(user){
    const main_div =  document.querySelector('.main-div');
   //show logout
   showHideIcon(logout,false);
   showHideIcon(loggedUser,false);
   //update username to logged username
   const loggedUserSpan = document.querySelector('.username');
   loggedUserSpan.textContent = user.name;
   main_div.innerHTML  = '';
    //' <div class="admin-functions">\n<ul class="admin-buttons">\n</ul>\n</div>';
   const admin_functions = document.createElement('ul');
   admin_functions.className='admin-functions';
   const section_header = document.createElement('h2');
   section_header.innerText=`Welcome ${user.name}`;
   const admin_buttons = document.createElement('ul');
   admin_buttons.className='admin-buttons';
   admin_functions.appendChild(section_header);
   admin_functions.appendChild(admin_buttons);
   main_div.appendChild(admin_functions);

   //main.appendChild();
   addAdminFunctions();
}


function addAdminFunctions(){
    //here will be the existing item edit options for update and delete
    addInventoryButton();
    addCategoryButton();
    getProducts();
    getBanners();
    addBrandsUpdateButton();
    addProductButton();
    //add new item buttons will for forms for inserting new tupples into the db
}

//banners categoreis and products will be just like the populate catalog with cards but with buttons for update and delete
//in these modals there can be a buttton for create new item


function submitLoginRequest(e){
   e.preventDefault();
   form = document.querySelector('.login-form');
   const formData = new FormData(form);
   for(key of formData){
    console.log(key);
   }
   fetchCall('login.php',userLoginResponse,'POST',formData)
   function userLoginResponse(data){
       if(data.admin) displayLoggedUser(data.admin);
       else{alert('error Incorrect username');}
   }
}

function displayLoginRegisterIcons(){
    //alert('logged out successfully');
    //showHideIcon(register,false);
    showHideIcon(loggedUser,false);
    showHideIcon(logout,true);
      //update username to logged username
   const loggedUserSpan = document.querySelector('.username');
   loggedUserSpan.textContent = 'Guest';
}


function getPasswordButton(container){
    const forgotPasswordButton = document.createElement('button');
    forgotPasswordButton.innerText='Forgot Password ?';
    forgotPasswordButton.className='forgotPassword-link';
    container.appendChild(forgotPasswordButton);
    forgotPasswordButton.addEventListener('click',forgotPasswordForm);
}
function forgotPasswordForm(){
    const  modal = document.createElement('div');
    modal.className='modal-container';
    const  formContainer = document.createElement('div');
    formContainer.className='form-div';
    const form =  document.createElement('form');
    form.className='forgot-password-form';
    formContainer.appendChild(form);

    //form header
    const header = document.createElement('h2');
    header.innerText = "Forgot Password";
    form.appendChild(header);
    //email
    const emailInput = document.createElement('input');
    const emailLabel = document.createElement('label');
    emailInput.type='email';
    emailInput.name='email'
    emailLabel.textContent='Email';

    //phone number
    const phoneInput = document.createElement('input');
    const phoneLabel = document.createElement('label');
    phoneInput.type='text'
    phoneInput.name='phone';
    phoneLabel.textContent='Phone number';
    //submit
    const submit = document.createElement('input');
    submit.type = 'submit';
    submit.name = 'get-password';
    submit.addEventListener('click',submitForgotPassword);
    //append fields
    form.appendChild(emailLabel);
    form.appendChild(emailInput);
    form.appendChild(phoneLabel);
    form.append(phoneInput);
    form.appendChild(submit);
    modal.appendChild(formContainer);
    displayOverlay(modal);
}
function  submitForgotPassword(e){
    e.preventDefault();
    const forgotPasswordForm = document.querySelector('.forgot-password-form');
    console.log(forgotPasswordForm);
    const formData = new FormData(forgotPasswordForm);
    fetchCall('get_password.php',responseSubmitForgotPassword,'POST',formData);
    function responseSubmitForgotPassword(data) {
        if(data['error']){
            alert('User info not in system'.toUpperCase());
        }
        else{
            alert(`password is ${data['password']}`);
        }
    }
}