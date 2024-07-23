function addProductButton(){
    //get admin button container
    const adminButtons = document.querySelector('.admin-buttons');
    //create a list item for addproduct
    const product    =   document.createElement('li');
    const addProdButton = document.createElement('button');
    addProdButton.textContent='Add Product';
    addProdButton.className='model-button';
    product.append(addProdButton);
    adminButtons.appendChild(product);
    //add a click event which will perform a get fetch request for products in db
    addProdButton.addEventListener('click',addProductForm);
   
}
function  addProductForm(){
    fetchCall("addProduct.php",responseAddProductForm);
    function   responseAddProductForm(data){
        console.log(data);
        //form header
        const formHeader = document.createElement('h3');
        formHeader.textContent = "Add Product"; 
        const formDiv = document.createElement('div');
        formDiv.appendChild(formHeader);
        const form = document.createElement('form');
        form.className = 'Add-products';
        formDiv.className = 'form-div';
        //form input fields
        //name
        const nameField= document.createElement('input');
        nameField.type = 'text';
        nameField.placeholder='name';
        nameField.name='name';
        nameField.id  = 'product name';
        const nameLabel = document.createElement('label');
        nameLabel.innerText = "Product Name";
        nameLabel.setAttribute('for',nameField.id);
        //desc
        const descField = document.createElement('input');
        descField.type= 'text';
        descField.placeholder = 'description';
        descField.name = 'description';
        descField.id = 'description';
        const descLabel = document.createElement('label');
        descLabel.innerText = "Product description";
        descLabel.setAttribute('for',descField.id);
        //price
        const priceLabel = document.createElement('label');
        priceLabel.innerText = "Price";
        const price = document.createElement('input');
        price.type = "number";
        price.name = "price";
        price.id = price.name;
        price.min = 0;
        price.value = 0;
        price.step = .01;
        priceLabel.setAttribute("for",price.id);
        //category input will be here needs a select field that has options from the category
        //i will need to fetch the name and cat id the option text will be the name but the value will be the id
         const categoryLabel = document.createElement('label');
         categoryLabel.innerText = "Category Name";
         const categoryList = document.createElement('select');
         categoryList.id = 'drop-down';
         categoryList.name = 'category';
         categoryLabel.setAttribute('for',categoryList.id);
         categoryDropDown();
    //image url
        const imageField= document.createElement('input');
        imageField.type = 'text';
        imageField.placeholder='image src';
        imageField.name='image';
        imageField.id=imageField.name;
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
        //submit will send info to be used to update value in database where id is the currently viewed object id
        const submit = document.createElement('input');
        submit.name='submit';
        submit.type='submit';
        //EVENTLISTENER FOR UPDATE TABLE
          submit.addEventListener('click',submitAddProduct);
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
        //append to form-containet
        formDiv.appendChild(form);
        displayOverlay(formDiv);
    }
}

function validateProductForm(){
    let input =  document.querySelectorAll('.Add-products input');
    let flag = false;
    const flagged_inputs = [];
    input.forEach(field=>{
        let input_value = field.value;
        //check all fields excpet for submit
        //input must be above 4 characters
        if(input_value.length < 4 && field.type != 'submit' && field.type != 'number' ){
            flagged_inputs.push(field);
            flag = true;
        }
    })
    if(flag){
        flagged_inputs.forEach(input=>{
            console.log(`field: ${input.name} value: ${input.value}`);
        })
    }
    return flag;
}



function submitAddProduct(e){
    e.preventDefault(); 
    let checkFormStatus = validateProductForm();
    if(checkFormStatus){
        alert('missing values'); 
        return;
    }
    const form = document.querySelector('.Add-products');
    const formData = new FormData(form);
    fetchCall('addProduct.php',responseSubmitAddProduct,'POST',formData);
    function responseSubmitAddProduct(data){
        console.log(data);
        if(data.add_product){
            removeOverlay();
            alert('add  Product successufully');
        }
        else{
            alert('registered unsuccessufully');
        }
    }
}