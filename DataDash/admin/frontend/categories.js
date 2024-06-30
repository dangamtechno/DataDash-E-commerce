
function getCategories(){
    const username = document.querySelector('.username').textContent;
    //console.log(username);
    const adminButtons = document.querySelector('.admin-buttons');
    const categories    =   document.createElement('li');
    const editCategoryButton = document.createElement('button');
    editCategoryButton.textContent='EDIT CATEGORIES';
    editCategoryButton.className='model-button';
    categories.append(editCategoryButton);
    adminButtons.appendChild(categories);
    editCategoryButton.addEventListener('click',requestCategories);
    //console.log(adminButtons);
    //alert(adminButtons.textContent);
}
function requestCategories(){
   fetchCall('categories.php',responseCategories)
   function responseCategories(data){
    const modal = document.createElement('div');
    const header = document.createElement('h2');
    header.textContent = 'Edit Categories';
    modal.appendChild(header);  
    console.log(modal);
    var  categories = data.categories;
    console.log(categories);    
    for(const category in categories){
          //create card to hold product info
    const card = document.createElement('div');
    card.className = "card";
    //product name price category and product desc
    const descDiv = document.createElement('div');
    descDiv.className = "card-desc";
    //apply it to card
    card.appendChild(descDiv);
    //apply event when card click to get product details 
    card.addEventListener('click',updateCategoryDetails.bind(categories[category]));
   
    //category name will be the name the for card
    const name = document.createElement("p");
    name.id = 'name';
    name.textContent =`Name: ${categories[category].Name}`;
    const statusText = document.createElement("p");
    statusText.id = 'status';
    const status = Boolean(categories[category].status);
    statusText.textContent = `Is viewable: ${status}`;
     //append to description container
     descDiv.appendChild(name);
     descDiv.appendChild(statusText);

     modal.appendChild(card);
  }
    displayOverlay(modal);
   }    
}

function updateCategoryDetails(){
    removeOverlay();
    const id = this.id
  
    const modal = document.createElement('div');
    updateCategoryForm(modal,this);
    displayOverlay(modal);
}

function updateCategoryForm(modal,category){
    const formHeader = document.createElement('h2');
    formHeader.textContent = "Update Category"; 
    const formDiv = document.createElement('div');
    formDiv.appendChild(formHeader);
    const form = document.createElement('form');
    form.className = 'update-category';
    formDiv.className = 'form-div';
    const nameField= document.createElement('input');
    nameField.type = 'text';
    nameField.name = 'name';
    nameField.placeholder= category.Name;
    const descDiv = document.createElement('div');
    descDiv.className = 'desc-div';
    form.appendChild(nameField);
    

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
    statusSection.appendChild(selectStatus);
    const submit = document.createElement('input');
    submit.name='update';
    submit.type='submit';
    submit.addEventListener('click',submitCategoryUpdate.bind(category))
    //EVENTLISTENER FOR UPDATE TABLE
    form.appendChild(statusSection);
    form.appendChild(submit);
    formDiv.appendChild(form);
    modal.appendChild(formDiv);
    createCategoryCard(modal,category);
 }
 function createCategoryCard(modal,category){
    const currentInfoSection = document.createElement('div');
    currentInfoSection.className = 'card';
    const currentName = document.createElement('h3');
    currentName.id = 'name';
    const currentStatus = document.createElement('h3');
    currentStatus.id = 'status';
    currentName.innerText  = `Current Category Name: ${category.Name}`;
    currentStatus.innerText = `Current Status: ${getStatus(category.status)}`;
    currentInfoSection.appendChild(currentName);
    currentInfoSection.appendChild(currentStatus);
    modal.appendChild(currentInfoSection);
}

function submitCategoryUpdate(e){
        e.preventDefault();
        form = document.querySelector('.update-category');
        const formData = new FormData(form);
        formData.append('id',this.id);
        console.log(this.id);
        //formData.append('status',)
        fetchCall(`categories.php`,responseUpdate,'POST',formData);
        function responseUpdate(data){
            console.log(data);
        //    update category card
             if(data.category){
                const category = data.category;
                const categoryName = document.getElementById('name');
                console.log(categoryName);
                const categoryStatus = document.getElementById('status');
                const newName = category.Name;
                const newStatus = category.status;
                categoryName.innerText = newName;
                categoryStatus.innerText = getStatus(newStatus);
             }
   }
}




//create
//update
//delete