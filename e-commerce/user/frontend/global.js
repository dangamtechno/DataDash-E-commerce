document.addEventListener('DOMContentLoaded',requestCategories);
function requestCategories(){
    fetch("http://localhost:8081/user/backend/menu.php",
    { method:"GET",}
     ).then( (res)=>res.json() )
    .then((data)=>{
        console.log(data.categories);
        const nav = document.querySelector('.navigation');
        if(data.categories){
            const ul = document.createElement('ul');
            data.categories.forEach((category) => {
                const li = document.createElement('li');
                li.className = category;
                li.textContent = category;
                li.addEventListener('click',getCategoryProducts);
                ul.appendChild(li);
            });
            //append to dom 
            nav.append(ul);
        }
    })
    .catch((err) => console.log(err));
}

function getCategoryProducts(){
    console.log("category clicked");
}