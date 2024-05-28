document.addEventListener('DOMContentLoaded',requestCategories);
document.addEventListener('DOMContentLoaded',requestBanner);
document.addEventListener('DOMContentLoaded',requestFeaturedProducts);
document.addEventListener('DOMContentLoaded',requestNewArrivals);

function fillDropDownList(data){
    list = document.getElementById("drop-down");
    if(list){
    data.forEach((item) =>{
        let option = document.createElement('option');
        option.text = item;
        list.add(option);
    });
}
}



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
    fetchCall("featured_products.php",responseFeaturedProducts)
    function responseFeaturedProducts(data){
        const featuredProducts= data.featuredProducts;
        featuredSection = document.querySelector('.featured-products');
        populateCatalog(featuredProducts,featuredSection);
    }
}
function requestNewArrivals(){
    fetchCall("new_arrivals.php",responseNewArrivals)
    function responseNewArrivals(data){
        const featurdProducts= data.new_items;
        console.log("test: "+featurdProducts)
        newSection = document.querySelector('.new-products');
        populateCatalog(featurdProducts,newSection);
    }
}



function requestBanner(){
   fetchCall("banner.php",responseBanner)
   function responseBanner(data){
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
   }
}


function requestCategories(){
    fetchCall("menu.php",responseCategories)
    function responseCategories(data){
        const nav = document.querySelector('.navigation');
        if(data.categories){
            categories = data.categories;
            console.log(categories);
            fillDropDownList(categories);
            const ul = document.createElement('ul');
            data.categories.forEach((category) => {
                const li = document.createElement('li');
                li.className = category;
                li.textContent = category;
                li.addEventListener('click',getCategoryProducts.bind(category));
                ul.appendChild(li);
            });
            //append to dom 
            nav.append(ul);
         }
    }
}

function populateCatalog(products,section){
 //if nonn empty enter branch
 if(products){
    const catalog = document.createElement("div");
    catalog.className = "catalog";

    products.forEach((prod)=>{
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
    section.appendChild(catalog);    
   }
}
function fetchCall(resource, callBack, method="GET"){
    const url ="http://localhost:8081/user/backend/";
    fetch(url+resource,{
       method: method,
    })
    .then((res) => res.json())
    .then((data)=>{
    //....logic goes here
    callBack(data);
    }).catch((err)=>console.log(err));
}


function getCategoryProducts(){
    const cat = this;
    setActiveCategory(cat);
}
function setActiveCategory(cat){
    const categoryList = document.querySelectorAll(".navigation li");
    const root = document.querySelector(":root");
    const primaryColor = window
    .getComputedStyle(root)
    .getPropertyValue("--primaryColor");
    console.log(primaryColor);
    categoryList.forEach((category)=>{
        if(category.classList.contains(cat)){
        category.style.backgroundColor = primaryColor;
        } else category.style.backgroundColor = "initial"
    })
}