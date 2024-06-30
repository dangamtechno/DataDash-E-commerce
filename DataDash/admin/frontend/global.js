document.addEventListener('DOMContentLoaded',createAdminLogin);
document.addEventListener('DOMContentLoaded',checkLoginStatus);

function getStatus(i){
    let status = '';
    if(Boolean(i)){
     status = 'viewable'; 
    }
    else status = 'unviewable'
    return status;
}

function removeOverlay(){
    //const main = document.querySelector('main');
    const overlay = document.querySelector('.overlay');
    const modalContainer = document.querySelector('.modal-container');
    if(overlay){
        overlay.remove();
    }
    if(modalContainer){
        modalContainer.remove();
    }

}
function displayOverlay(modal){
    //overlay
const main = document.querySelector('main');
const overlay = document.createElement('div');
overlay.className ='overlay';
overlay.addEventListener('click',removeOverlay);
main.appendChild(overlay);
//modal container
const modalContainer = document.createElement('div');
modalContainer.className = 'modal-container';
modalContainer.appendChild(modal)
main.appendChild(modalContainer);
}
function fetchCall(resource, callBack, method="GET",data = undefined){
    const url ="http://localhost:8080/admin/backend/";
    fetch(url+resource,{
       method: method,
       mode:"cors",
       credentials:"include",
       body:data, 
    })
    .then((res) => res.json())
    .then((data)=>{
    //....logic goes here
    callBack(data);
    }).catch((err)=>console.log(err+" "+ resource + ' resource '));
}