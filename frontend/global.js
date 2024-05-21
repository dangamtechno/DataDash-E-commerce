document.addEventListener('DOMContentLoaded',requestCategories);
document.addEventListener('DOMContentLoaded',requestBanner);
function callCarousal(){
    const swiper = new Swiper('.swiper', {
       
        loop: true,
      
        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },
      
        // Navigation arrows
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      
      
      });

}



function requestBanner(){
   fetch("http://localhost:8081/user/backend/banner.php",{method:"GET",})
   .then((res)=>res.json())
   .then((data)=>{
       console.log(data);
       if(data.banners){
        const banners = data.banners
        banners.forEach((banner) => {
            console.log(banner.image)
            const slide = document.createElement("div")
            slide.className = "swiper-slide";
            slide.style.backgroundImage=` url('http://localhost:8081${banner.image}')`;
            slide.style.backgroundSize = "cover";
            slide.style.height="50vh";
            const h3 = document.createElement('h3');
            h3.textContent = banner.name;
            const p = document.createElement('p');
            p.textContent = banner.description;
            const button = document.createElement('button');
            button.textContent = 'Shop Now';
            const swiperWrapper = document.querySelector(".swiper-wrapper");
            slide.append(h3);
            slide.append(p);
            slide.append(button);
            swiperWrapper.append(slide);
        });
        callCarousal();
       }
   })
   .catch( (err) => console.log(err) );

}


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