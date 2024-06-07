document.addEventListener('DOMContentLoaded',requestCategories);
document.addEventListener('DOMContentLoaded',requestBanner);
document.addEventListener('DOMContentLoaded',requestFeaturedProducts);
document.addEventListener('DOMContentLoaded',requestNewArrivals);

const searchSubmit = document.querySelector('.search-button');
searchSubmit.addEventListener('click',submitSearch);
function submitSearch(e){
    e.preventDefault();
    const form = document.querySelector('.search-form');
    const formData = new FormData(form);
    fetchCall('search.php',responseSearch,'POST',formData)
    function responseSearch(data){
        let products = data.search; 
        console.log(products);
        if(products){
           const main = document.querySelector('main');
           main.innerHTML= "";
           populateCatalog(products,main);
        }
    }
}



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
          //create card to hold product info
          const card = document.createElement('div');
          card.className = "card";
          //image for product
          const imgDiv = document.createElement('div');
          imgDiv.className = "card-img";
          //product name price category and product desc
          const descDiv = document.createElement('div');
          descDiv.className = "card-desc";
          //apply it to card
          card.appendChild(imgDiv);
          card.appendChild(descDiv);
          //apply event when card click to get product details 
          card.addEventListener('click',getProductDetails.bind(prod))
          //image element for product
          const img = document.createElement('img');
          img.src = `http://localhost:8081${prod.image}`;
          imgDiv.appendChild(img);
          //product name will be the name the for card
          const name = document.createElement("p");
          name.textContent = prod.name;
          // product price
          const price = document.createElement("p");
          price.textContent = `${prod.price}$`
          name.className = "product-name";
          price.className="product-price";
          //product description
          const prodDescription = document.createElement('p');
          prodDescription.textContent = prod.description;
          //append to description container
          descDiv.appendChild(name);
          descDiv.appendChild(prodDescription);
          descDiv.appendChild(price); 
          catalog.appendChild(card);
       });
       section.appendChild(catalog);
      }
   }
                     
function addToWishlist(){
    console.log("Add to Wishlist");
}
function addToCart(){
    console.log("Add to cart");
}

function fetchCall(resource, callBack, method="GET",data = undefined){
    const url ="http://localhost:8081/user/backend/";
    fetch(url+resource,{
       method: method,
       body:data, 
    })
    .then((res) => res.json())
    .then((data)=>{
    //....logic goes here
    callBack(data);
    }).catch((err)=>console.log(err));
}
 

function remove(stars) {
    let i = 0;
    while (i < 5) {
        stars[i].className = "star";
        i++;
    }
}
function manageStars(stars,n){
    return function(event){
       remove(stars);
       let cls = "";
       for (let i = 0; i < n; i++) {
           if (n == 1){ 
              cls = "one";
           }
           else if (n == 2) cls = "two";
           else if (n == 3) cls = "three";
           else if (n == 4) cls = "four";
           else cls = "five";
           stars[i].className = "star " + cls;
       }
   }
}
function createStarRating(section){
    for(let j = 1 ; j <= 5; j++ ){
        const i = document.createElement('span');
        i.className = "star";
        i.textContent ="★";
        section.appendChild(i);
    }
}

function addRatingClickEvent(){
    const ratingCards = document.querySelectorAll('.rating-container');
    ratingCards.forEach((card)=>{
        const stars = card.querySelectorAll('.star');
        stars.forEach((star,index)=>{
            star.addEventListener('click',manageStars(stars,index+=1));
        });
    })
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

function getProductDetails(){
    const main = document.querySelector('main');
    fetchCall(`inventory.php?id=${this.id}`,responseInventory.bind(this))
    function responseInventory(data){
        //console.log(data);
        const overlay = document.createElement('div');
        overlay.className ='overlay';
        overlay.addEventListener('click',removeOverlay);
        main.appendChild(overlay);
        const modal = document.createElement('div');
        modal.className ='modal';
        main.appendChild(modal);
        
        const modalImageContainer = document.createElement('div');
        modalImageContainer.className = 'modalImage';
        const img = document.createElement('img');
        img.src = `http://localhost:8081${this.image}`;
        modalImageContainer.appendChild(img);
        modal.appendChild(modalImageContainer);
        
        const modalDesc = document.createElement('div');
        modalDesc.className = 'modal-desc';
        const title = document.createElement('div');
        title.textContent = this.name;
        modalDesc.appendChild(title);
        const review = document.createElement('button');
       review.addEventListener('click',submitReview);
       review.textContent = 'leave review';
       review.className ='cart-button';
       const reviewText = document.createElement('input');
       reviewText.type = 'text';
       reviewText.className ='review_text';
       reviewText.setAttribute("placeholder", "Enter text here");
       const reviewTextLabel = document.createElement('label');
       reviewTextLabel.textContent = "Leave a review!";
       reviewTextLabel.setAttribute('for',"review_text");
       const ratingDiv = document.createElement('div');
       ratingDiv.className = 'rating-container'
       
       createStarRating(ratingDiv);
       const review_container = document.createElement('div');
       review_container.className = 'review-container';
       review_container.appendChild(reviewTextLabel);
       review_container.appendChild(reviewText);
       review_container.appendChild(review);
       cart = document.createElement('button');
       cart.textContent='Add to cart';
       cart.className='cart-button';
       cart.addEventListener('click',addToCart);
       wishlist = document.createElement('button');
       wishlist.textContent='wish List';
       wishlist.className='cart-button';
       wishlist.addEventListener('click',addToWishlist);
       let itemCount  = 0;
       const subTotal = document.createElement('h2');
       const itemsForCart = document.createElement('h2');
       itemsForCart.innerHTML = `Add to cart ${itemCount}`;
       itemsForCart.id = 'quantity';
       const increment = document.createElement('button');
       increment.className='cart-button';
       increment.textContent="increment";
       increment.addEventListener('click',()=>{
           itemCount+=1;
           let total = itemCount * this.price;
           subTotal.innerHTML= total.toFixed(2);
           itemsForCart.textContent=`Add to cart: ${itemCount}`;
        });
       const decrement = document.createElement('button');
       decrement.textContent="decrement";
       decrement.className="cart-button";
       decrement.addEventListener('click',
       ()=>{
           if( itemCount > 0){
              itemCount-=1;
              let total = itemCount * this.price;
              subTotal.innerHTML= total.toFixed(2);
              itemsForCart.textContent=`Add to cart: ${itemCount}`;
           }
        });
       const inStock = document.createElement('p');
       inStock.innerHTML = `In stock: ${data.inStock}`;
       modal.appendChild(inStock);
       modal.appendChild(itemsForCart);
       modal.appendChild(subTotal)
       const buttonContainer = document.createElement('div');
       buttonContainer.className = 'modal-buttons';
       buttonContainer.appendChild(increment);
       buttonContainer.appendChild(decrement);
       buttonContainer.appendChild(cart);
       buttonContainer.appendChild(wishlist);
       modal.appendChild(buttonContainer);
       review_container.appendChild(ratingDiv);
       modal.appendChild(review_container);
       addRatingClickEvent();
    }
}

function removeOverlay(){
    const main = document.querySelector('main');
    const overlay =document.querySelector('.overlay');
    const modal = document.querySelector('.modal');
    if(overlay){
        overlay.remove();
    }
    if(modal){
        modal.remove();
    }

}

