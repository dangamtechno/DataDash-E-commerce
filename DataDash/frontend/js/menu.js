function requestCategories(){
    fetchCall("menu.php",responseCategories)
    function responseCategories(data){
        const nav = document.querySelector('.navigation');
        console.log(data);
        if(data.categories){
            categories = data.categories;
          //  console.log(categories);
          //  fillDropDownList(categories);
            const ul = document.createElement('ul');
            const li = document.createElement('li');
            li.textContent = "All Products";
            li.addEventListener('click',getAllProducts);
            ul.appendChild(li);
            categories.forEach((category) => {
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
function getCategoryProducts(){
    const cat = this;
    const main = document.querySelector('main');
    setActiveCategory(cat);
    fetchCall(`product.php?category=${cat}`,responseCategories)
    function responseCategories(data){
        if(data.products){
            main.innerHTML = "";
            let products = data.products;
            if(products.length > 0){
               populateCatalog(products,main);
            }
            else{
            main.innerHTML= "<h2>Nothing to see here</h2>";
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