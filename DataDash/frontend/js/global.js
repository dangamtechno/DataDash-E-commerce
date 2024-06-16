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
          img.src = `http://localhost:8080${prod.image}`;
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
   function getProductDetails(){
    const main = document.querySelector('main');
    const price = this.price;
    fetchCall(`inventory.php?id=${this.id}`,responseInventory.bind(this))
    function responseInventory(data){
       //get howmany instock
       const inStock = +(data.inStock); 
       //overlay
       const overlay = document.createElement('div');
       overlay.className ='overlay';
       overlay.addEventListener('click',removeOverlay);
       main.appendChild(overlay);
       //modal container
       const modalContainer = document.createElement('div');
       modalContainer.className = 'modal-container';
       //image container
       const modalImageContainer = document.createElement('div');
       const modal = document.createElement('div');
       modal.className ='modal';
       modalContainer.appendChild(modal)
       main.appendChild(modalContainer);
       modalImageContainer.className = 'modalImage';
       const img = document.createElement('img');
       img.src = `http://localhost:8080${this.image}`;
       modalImageContainer.appendChild(img);
       modal.appendChild(modalImageContainer);
       const modalDesc = document.createElement('div');
       modalDesc.className = 'modal-desc';
       const title = document.createElement('div');
       title.textContent = this.name;
       modalDesc.appendChild(title);     
       //Submit Review section that will only be shown if user is logged and has a order id with product id in it
       //so from here we would need to do a fetch for user credentials to get orders then check to see if product id of card is in one of the orders.
       //select order_id and user id from ordered_item where order_product_id = product_id;
       //if true show this section or even just create the section
       const review = document.createElement('button');
       review.addEventListener('click',submitReview);
       review.textContent = 'leave review';
       review.className ='cart-button';
       const reviewText = document.createElement('input');
       reviewText.type = 'text';
       reviewText.className ='review_text';
       reviewText.setAttribute("placeholder", "Enter text here");
       const reviewTextLabel = document.createElement('h2');
       reviewTextLabel.textContent = "Leave a review!";
       const ratingDiv = document.createElement('div');
       ratingDiv.className = 'rating-container'
       createStarRating(ratingDiv);
       const review_container = document.createElement('div');
       review_container.className = 'review-container';
       review_container.appendChild(reviewTextLabel);
       review_container.appendChild(reviewText);
       //Cart functionallity
       cart = document.createElement('button');
       cart.textContent='Add to cart';
       cart.className='cart-button';
       wishlist = document.createElement('button');
       wishlist.textContent='wish List';
       wishlist.className='cart-button';
       cart.addEventListener('click',addToCart);
       wishlist.addEventListener('click',addToWishlist);
       const itemsForCartSection = document.createElement('div');
       itemsForCartSection.className = 'items-for-cart-section';
       getStockText(inStock,modalImageContainer);
       const buttonContainer = document.createElement('div');
       buttonContainer.className = 'modal-buttons';
       //past review section for product
       const pastReviews = document.createElement("div");
       pastReviews.className = "past-reviews-container";
       //append to container
       //add quantitySelector
       quantitySelector(inStock,price,itemsForCartSection);
       buttonContainer.appendChild(cart);
       buttonContainer.appendChild(wishlist);
       itemsForCartSection.appendChild(buttonContainer);
       modal.appendChild(itemsForCartSection)
       modal.appendChild(review_container);
       modal.appendChild(pastReviews);
       review_container.appendChild(ratingDiv);
       review_container.appendChild(review);
       addRatingClickEvent();
    //this will be the fetch request for reviews
       getReviews(this);
    }
}
function quantitySelector(inStock,price,container){
    const select = document.createElement('select');
    const label= document.createElement('h2');
    label.innerHTML = 'Choose amount:';
    const subTotal = document.createElement('p');
       select.className = 'selectQuantity';
       if(inStock == 0) select.disabled = true;
       else{
          let count = inStock > 10 ? 10 : inStock;
          for(let i = 1 ; i <= count; i++){
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            select.appendChild(option);
          }
       }
       select.addEventListener('change',function(){
          let toBuy = +select.value;
          let sub = toBuy*price;
          sub = sub.toFixed(2);
          subTotal.innerHTML = `Sub total: ${sub} $`;
          itemCount = toBuy;
       });
       container.append(label);
       container.appendChild(select);
       container.appendChild(subTotal);
}
function getStockText(inStock,modal){
    const inStockText = document.createElement('p');
    inStockText.className = 'in-stock';
    inStockText.innerHTML = `In stock: ${inStock}`;
    switch (true) {
        case inStock > 10 :
        inStockText.innerHTML = "In stock" 
        inStockText.style.color = 'green'
        break;
        case inStock > 0 && inStock <=10 :
            inStockText.innerHTML = `Only ${inStock} left`;
            inStockText.style.color = 'orange'
            break;
        case inStock == 0:
            inStockText.innerHTML = "out of stock";
            break;
        default:
            console.log(inStock);
            break;
    }
    modal.appendChild(inStockText);
}


function fetchCall(resource, callBack, method="GET",data = undefined){
    const url ="http://localhost:8080/backend/utils/";
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
 function addRatingClickEvent(){
    const ratingCards = document.querySelectorAll('.rating-container');
    ratingCards.forEach((card)=>{
        const stars = card.querySelectorAll('.star');
        stars.forEach((star,index)=>{
            star.addEventListener('click',manageStars(stars,index+=1));
        });
    })
}
function submitReview(e){
    let ratingConversion = {
        'one' : 1 ,'two' : 2, 'three' : 3,'four' : 4,'five' : 5
    };
    const parentElement = e.target.parentNode;
    let num_stars = document.querySelector('.star').classList.toString();
    num_stars = num_stars.replace('star ','');
    const textBox = parentElement.querySelector('.review_text');
    const text = textBox.value;
    
    if(text) console.log(text);
    else console.log("No review entered!".toUpperCase());
    
    if(ratingConversion[num_stars]) console.log(`rating ${ratingConversion[num_stars]}`);
    else console.log("No rating entered!".toUpperCase());
    const data = {review : text, rating: ratingConversion[num_stars]}; 
    /*logic for submiting to backend call method for post
    fetchCall('product_review.php',responseSubmit,"POST",data)
    function responseSubmit(data){
        console.log(data);
    }
    */
}
function getReviews(product){
    const reviewSection = document.querySelector('.past-reviews-container');
    const section_title = document.createElement('h2');
    section_title.innerHTML = "Past reviews";
    reviewSection.appendChild(section_title);
    const  id = +(product.id);
    fetchCall(`get_reviews.php?id=${product.id}`,responseReviews)
    function responseReviews(data){
        if(data.reviews.length > 0){
           const reviews = data.reviews;
           console.log(reviews);
           reviews.forEach((review)=>{
                const past_review = document.createElement('div');
                past_review.className='past-review';
                const rating = review.rating;
                const text = `Review: ${review.reviewText}`;
                const date = `Date: ${review.date}`;
                const user_id = `Name: ${review.fname} ${review.lname}`;
                const text_p = document.createElement('p');
                const date_p = document.createElement('p');
                const rating_p = document.createElement('p');
                const user_id_p =document.createElement('p');
                text_p.innerHTML = text;
                user_id_p.innerHTML = user_id;
                date_p.innerHTML = date;
                rating_p.innerHTML =`Rating: ${rating}`;
                past_review.appendChild(user_id_p);
                past_review.appendChild(date_p);
                past_review.appendChild(rating_p);
                createStarRating(past_review);
                past_review.appendChild(text_p);
                reviewSection.appendChild(past_review);
                const r = Math.floor(Math.random()*255)+1;
                const g = Math.floor(Math.random()*255)+1;
                const b = Math.floor(Math.random()*255)+1;
                console.log(`rgb(${r},${g},${b})`);
                past_review.style.backgroundColor = `rgb(${r},${g},${b})`;
                const stars = past_review.querySelectorAll('.star');
                setStarRating(stars,rating);
           });
        }
    }
}
function removeOverlay(){
    //const main = document.querySelector('main');
    const overlay =document.querySelector('.overlay');
    const modalContainer = document.querySelector('.modal-container');
    if(overlay){
        overlay.remove();
    }
    if(modalContainer){
        modalContainer.remove();
    }

}
function addToWishlist(){
    selectValue = document.querySelector(".selectQuantity");
    itemCount= selectValue.value;
    console.log(`Add ${itemCount} to Wishlist`);
}
function addToCart(){
    selectValue = document.querySelector(".selectQuantity");
    itemCount= selectValue.value;
    console.log(`Add ${itemCount} to cart`);
}
