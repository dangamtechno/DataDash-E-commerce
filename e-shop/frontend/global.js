document.addEventListener('DOMContentLoaded',requestCategories);
document.addEventListener('DOMContentLoaded',requestBanner);
document.addEventListener('DOMContentLoaded',requestFeaturedProducts);


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

function requestFeaturedProducts(){
    fetch("http://localhost:8081/user/backend/featuredProducts.php",
    { method:"GET",}
     ).then( (res)=>res.json() )
    .then((data)=>{
        const featurdProducts= data.featuredProducts;
       //if nonn empty enter branch
        if(featurdProducts){
        const featuredSection = document.querySelector('.featured-products');
        const catalog = document.createElement("div");
        catalog.className = "catalog";

        featurdProducts.forEach((prod)=>{
           const card = document.createElement('div');
           card.className = "card";
           const imgDiv = document.createElement('div');
           imgDiv.className = "card-img";
           const descDiv = document.createElement('div');
           descDiv.className = "card-desc";
           card.appendChild(imgDiv);
           card.appendChild(descDiv);
           const img = document.createElement('img');
           img.src = `http://localhost:8081${prod.image}`;
           imgDiv.appendChild(img);
           const name = document.createElement("p");
           name.textContent=prod.name;
           const price = document.createElement("p");
           price.textContent = `${prod.price}$`
           name.className = "product-name";
           price.className="product-price";
           descDiv.appendChild(name);
           descDiv.appendChild(price);
           catalog.appendChild(card);
           
        })
        featuredSection.appendChild(catalog);
       }
    })
    .catch((err) => console.log(err));
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