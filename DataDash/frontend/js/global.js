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
        const featuredProducts= data.featured_products;
        featuredSection = document.querySelector('.featured-products');
        populateCatalog(featuredProducts,featuredSection);
    }
}
function requestNewArrivals(){
    fetchCall("new_arrivals.php",responseNewArrivals)
    function responseNewArrivals(data){
        const new_items = data.new_items;
        newSection = document.querySelector('.new-products');
        populateCatalog(new_items,newSection);
    }
}



function requestBanner(){
   fetchCall("banner.php",responseBanner)
   function responseBanner(data){
    if(data.banners){
        const banners = data.banners
        banners.forEach((banner) => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";
            url =` url('http://localhost:8081${banner.image}')`;
            if(url===""){
            slide.style.backgroundImage=` url('http://localhost:8081${banner.image}')`;
            }
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
       const prodDescription = document.createElement('p');
       prodDescription.textContent = prod.description;
       descDiv.appendChild(name);
       descDiv.appendChild(prodDescription);
       descDiv.appendChild(price);
       cart = document.createElement('button');
       cart.textContent='Add to cart';
       cart.className='cart-button';
       cart.addEventListener('click',addToCart);
       wishlist = document.createElement('button');
       wishlist.textContent='wish List';
       wishlist.className='cart-button';
       wishlist.addEventListener('click',addToWishlist);
       let x = 0;
       const sub = document.createElement('h2');
       const quantity = document.createElement('h2');
       quantity.innerHTML = `quantity ${x}`;
       quantity.id = 'quantity';
       const increment = document.createElement('button');
       increment.className='cart-button';
       increment.textContent="increment";

       increment.addEventListener('click',()=>{
         x+=1;
         let total = x*prod.price;
         sub.innerHTML= total.toFixed(2);

     quantity.textContent=`quantity: ${x}`;})
     const decrement = document.createElement('button');
        decrement.textContent="decrement";
        decrement.className="cart-button";

        decrement.addEventListener('click',()=>{
         if( x > 0){
            x-=1;
            let total = x*prod.price;
            sub.innerHTML= total.toFixed(2);
            quantity.textContent=`quantity: ${x}`;
            }} ) ;

     card.appendChild(sub);
        card.appendChild(quantity);
        card.appendChild(increment);
        card.appendChild(decrement);
        card.appendChild(cart);
        card.appendChild(wishlist)
       catalog.appendChild(card);
       
    })
    section.appendChild(catalog);    
   }
}

function addToWishlist(){
    console.log("Add to Wishlist");
}
function addToCart(){
    console.log("Add to cart");
}

function fetchCall(resource, callBack, method="GET"){
    const url ="http://localhost:8081/backend/utils/";
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
    const main = document.querySelector("main");
    setActiveCategory(cat);
    fetchCall(`products.php?category=${cat}`,responseCategoryProducts)
    function responseCategoryProducts(data){
       //console.log(data);
       if(data.products){
        let products = data.products;
        let count = products.length;
        console.log("Count: " + count);
        main.innerHTML='';
        if(count > 0) populateCatalog(products,main);
        else{
            alert("Empty");
            main.innerHTML='<h2>nothing to see here</h2>';
        }
       }
    }
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