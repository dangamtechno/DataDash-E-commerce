const login = document.querySelector(".login");
const register = document.querySelector(".register");
const logout = document.querySelector(".logout");
const loggedUser = document.querySelector(".logged-user");

showHideIcon(register,false);

function showHideIcon(icon,flag){
    flag ? (icon.style.display= "none"):(icon.display= "block");
}