function getProducts(){
    const username = document.querySelector('.username').textContent;
    const adminButtons = document.querySelector('.admin-buttons');
    const products    =   document.createElement('li');
    const editProductsBTN = document.createElement('button');
    editProductsBTN.textContent='EDIT PRODUCTS';
    editProductsBTN.className='model-button';
    products.append(editProductsBTN);
    adminButtons.appendChild(products);
    editProductsBTN.addEventListener('click',requestProducts);
    
}
function requestProducts(){
   fetchCall('product.php',responseGetProducts);
   function responseGetProducts(data) {
    const modal = document.createElement('div');
    modal.className = 'modal-container';
    modal.id = 'product-container'; 
    var  products = data.products;
    console.log(products);    
    for(const product in products){
            //create card to hold product info
        const card = document.createElement('div');
        card.className = "product-card";
        
        const imgDiv = document.createElement('div');
        imgDiv.className = "card-img";
        //product name price category and product desc
        const descDiv = document.createElement('div');
        descDiv.className = "card-desc";
        //apply it to card
        card.appendChild(descDiv);
        //apply event when card click to get product details 
        card.addEventListener('click',getProductDetails.bind(products[product]));

        //product name will be the name the for card
        const name = document.createElement("p");
        name.textContent = products[product].name;
        //append to description container
        descDiv.appendChild(name);
        modal.appendChild(card);
  }
    displayOverlay(modal);
   }
}
function getProductDetails(){
    fetchCall(`product.php?id=${this.product_id}`,responseProductDetails.bind(this))
    function responseProductDetails(data){
        //grab prod from response
        let product = data.product;
        const modal = document.createElement('div');
        modal.className= 'modal';
        modal.id = 'update-prod-container';
        //header    for edit    prod
        const formHeader = document.createElement('h2');
        formHeader.textContent = "Update products"; 
        modal.appendChild(formHeader);
        //desc will be the name and descp of prod
        const descDiv = document.createElement('div');
        descDiv.className = 'desc-div';
        const desc = document.createElement('p');
        desc.id = 'description';
        desc.innerText = `Description: ${product.description}`;
        //image section of prod
        const modalImage = document.createElement('div');
        modalImage.className='card-img';
        const img = document.createElement('img')
        img.src = `../../frontend/images/electronic_products/${product.image}`;
        //name of prod
        const name = document.createElement('p');
        name.id = 'name';
        name.innerText = `Name: ${product.name}`;
        //category

        const category = document.createElement('p');
        category.id = 'category';
        setCategoryName(product.category_id);
        //status of prod
        const status = document.createElement('p');
        status.id = 'status';
        status.innerText = `status: ${getStatus(product.status)}`;
        //price of prod
        const price = document.createElement('p');
        price.id = 'price';
        price.innerText = `price: ${product.price}$`;

     //append to desc div
        descDiv.appendChild(name);
        descDiv.appendChild(category);
        descDiv.appendChild(desc);
        descDiv.appendChild(price);
        descDiv.appendChild(status);
    //append to modal image dive
        modalImage.appendChild(img);
        modal.appendChild(modalImage);
        modal.appendChild(descDiv);        
    //remove current overlay
        removeOverlay();
    //display new overlay
        displayOverlay(modal);
    //add update form to modal    
       updateProductForm(modal,this);
    }
}
function updateProductForm(modal,product){
    //header of form
   const formHeader = document.createElement('h3');
   formHeader.textContent = "Update Product"; 
   const formDiv = document.createElement('div');
   formDiv.appendChild(formHeader);
   const form = document.createElement('form');
   form.className = 'update-products';
   formDiv.className = 'form-div';
   const nameField= document.createElement('input');

   nameField.type = 'text';
   nameField.placeholder='name';
   nameField.name='name';
   nameField.id = 'name';

   const nameLabel = document.createElement('label');
   nameLabel.innerText = "Product Name";
   nameLabel.setAttribute('for',nameField.id);

   const descField = document.createElement('input');
   descField.type= 'text';
   descField.placeholder = 'description';
   descField.name = 'description';
   descField.id = 'description';
   const descLabel = document.createElement('label');

   descLabel.innerText = "Product description";
   descField.setAttribute('for',descField.id);
    //price
    const priceLabel = document.createElement('label');
    priceLabel.innerText = 'Price';
    const price = document.createElement('input');
    price.type = 'number';
    price.name = 'price';
    price.id = 'price';
    price.min = 0;
    price.value = 0;  
    price.step = .01;
    priceLabel.setAttribute('for',price.id);
    //category input will be here needs a select field that has options from the category
    // i will need to fetch the name and cat id the option text will be the name but the value will be the id
    const categoryLabel = document.createElement('label');
    categoryLabel.innerText = "Category Name";
    const categoryList = document.createElement('select');
    categoryList.id = 'drop-down';
    categoryLabel.setAttribute('for',categoryList.id);
    categoryList.name = 'category';
    categoryDropDown();

//image url field will come here
 //image url
 const imageField= document.createElement('input');
        
 imageField.type = 'text';
 imageField.placeholder='image src';
 imageField.name='image';
 imageField.id ='image'
 imageField.value =  product.image;
 const imageLabel = document.createElement('label');
 imageLabel.innerText = "Image src";
 imageLabel.setAttribute('for',imageField.id);

   //status is either 1 or 0
   const statusSection = document.createElement('div');
   const statusSectionHeader = document.createElement('h3');
   statusSectionHeader.innerHTML = 'Status';
   statusSection.appendChild(statusSectionHeader);
   const selectStatus = document.createElement('select');
   selectStatus.name = 'status';
   for(let i = 0; i <= 1;i++){
    let statusField =   document.createElement('option');
    statusField.type = '';
    statusField.value = i;
    statusField.innerText = getStatus(i);
    selectStatus.appendChild(statusField);
}
//default values will be the current ones in db
   selectStatus.selectedIndex = product.status;
   nameField.value = product.name;
   descField.value = product.description;
   price.value = product.price;
//submit will send info to be used to update value in database where id is the currently viewed object id
   const submit = document.createElement('input');
   submit.name='submit';
   submit.type='submit';
   //EVENTLISTENER FOR UPDATE TABLE
   submit.addEventListener('click',submitProductUpdate.bind(product));
   //form.appendChild(statusSection);
   
   //name
   form.appendChild(nameLabel);
   form.appendChild(nameField);
   //desc
   form.appendChild(descLabel);
   form.appendChild(descField);
   //price
   form.appendChild(priceLabel);
   form.appendChild(price);
  // category
  form.appendChild(categoryLabel);
  form.appendChild(categoryList);
 //image
 form.appendChild(imageLabel);
 form.appendChild(imageField);
   //status
   statusSection.appendChild(selectStatus);
   form.appendChild(statusSection);
   form.appendChild(submit);
   //add delete button
   const id = product.product_id;
   console.log(id);
   //append to form-containet
   formDiv.appendChild(form);
   deleteProduct(formDiv,id);
   //append to the main view
   modal.appendChild(formDiv);
}

function submitProductUpdate(e){
    e.preventDefault();
    form = document.querySelector('.update-products');
    const formData = new FormData(form);
    formData.append('id',this.product_id);
    fetchCall("product.php",responseSubmitProductUpdate,"POST",formData);
    function responseSubmitProductUpdate(data){
        if(data.product){
            let product = data.product;
            const name = document.getElementById('name');
            const price = document.getElementById('price');
            const desc  = document.getElementById('description');
            const status = document.getElementById('status');
            setCategoryName(product.category_id);

            name.innerText = `Updated Product Name: ${product.name}`;
            price.innerText = `Updated Product price: ${product.price}$`;
            status.innerHTML = `Updated Product status: ${getStatus(product.status)}`;
            desc.innerHTML =`Updated Product Description: ${product.description}`;
            alert("Product update successful");
        }
    }
    //perform update then show the change in the product card in the admin update product page.

}
function setCategoryName(id){
    fetchCall(`categories.php?id=${id}`,responseCategory);
    function responseCategory(data){
        if(data.category){
            const category = data.category;
            document.getElementById('category').innerText = `Category: ${category.category_name}`;
        }
    }
}
function deleteProduct(container,id){
    //create delete button
    const deleteButton = document.createElement('button');
    deleteButton.innerText = "Delete Product";
    deleteButton.className = 'delete-button';
    container.appendChild(deleteButton);
    // Add event listener to delete button
    deleteButton.addEventListener('click', function() {
        submitDeleteProduct(id); // Pass id to submitDeleteBanner function
    });
}
function submitDeleteProduct(id){
    fetchCall(`delete_product.php?id=${id}`,responseDeleteProduct)
    function  responseDeleteProduct(data){
        if(data['deleteSuccess']){
            alert(data['deleteSuccess']);
            location.reload();
        }
        else{
            alert(data['error']);
        }
    }
}
