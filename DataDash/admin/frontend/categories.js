
function addCategoryButton(){
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
    const  categories = data.categories;
    const modal = document.createElement('div');
    modal.className="form-div";
    if(categories.length == 0){ 
        const section_header= document.createElement('h2');
        section_header.innerText="No categories to edit\nCreate Banner Here";
        modal.appendChild(section_header);
        createNewBannerForm(modal);
        //create banner form here
    }
    const header = document.createElement('h2');
    header.textContent = 'Edit Categories';
    modal.appendChild(header);
    console.log(modal);
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
    name.textContent =`Name: ${categories[category].category_name}`;
    const statusText = document.createElement("p");
    statusText.id = 'status';
    let status  =categories[category].status;
    statusText.textContent = `Is viewable: ${getStatus(status)}`;
     //append to description container
     descDiv.appendChild(name);
     descDiv.appendChild(statusText);

     modal.appendChild(card);
  }
  
        //button to show the create new Category
        const showCreateCategoryForm = document.createElement('div');
        showCreateCategoryForm.className='card';
        showCreateCategoryForm.innerText="Create New Category";
        modal.appendChild(showCreateCategoryForm);
        showCreateCategoryForm.addEventListener('click', function() {
            // Call createNewBannerForm with an argument
            const formContainer =   document.createElement('div');
            createNewCategoryForm(formContainer);
            showCreateCategoryForm.style.display = 'none';
            modal.appendChild(formContainer);
        });
      displayOverlay(modal);
   }    
}

function updateCategoryDetails(){
    removeOverlay();
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
    nameField.placeholder= category.category_name;
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
    //default values will be the current ones in db
    selectStatus.selectedIndex = category.status;
    nameField.value = category.category_name;

    statusSection.appendChild(selectStatus);
    const submit = document.createElement('input');
    submit.name='update';
    submit.type='submit';
    submit.addEventListener('click',submitCategoryUpdate.bind(category))
    //EVENTLISTENER FOR UPDATE TABLE
    form.appendChild(statusSection);
    form.appendChild(submit);
    formDiv.appendChild(form);
    const id = +(category.category_id);
    deleteCategory(formDiv,id);
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
    currentName.innerText  = `Current Category Name: ${category.category_name}`;
    currentStatus.innerText = `Current Status: ${getStatus(category.status)}`;
    currentInfoSection.appendChild(currentName);
    currentInfoSection.appendChild(currentStatus);
    modal.appendChild(currentInfoSection);
}

function submitCategoryUpdate(e){
        e.preventDefault();
        form = document.querySelector('.update-category');
        const formData = new FormData(form);
        formData.append('id',this.category_id);
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
                const newName = category.category_name;
                const newStatus = category.status;
                categoryName.innerText = `Updated Name: ${newName}`;
                categoryStatus.innerText =`Updated Status: ${getStatus(newStatus)}`;
                alert("Updated category sucessfully");
             }
   }
}




//create
function createNewCategoryForm(modal){
    //header 
    const formHeader = document.createElement('h2');
    formHeader.textContent = "Create Category"; 
    //the container
    const formDiv = document.createElement('div');
    formDiv.appendChild(formHeader);
    //create form
    const form = document.createElement('form');
    form.className = 'create-category';
    formDiv.className = 'form-div';
    //name
    const nameField= document.createElement('input');
    nameField.type = 'text';
    nameField.placeholder='name';
    nameField.name='name';
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
     submit.addEventListener('click',submitCreateCategory);
     //append fields
     form.appendChild(nameField);
     statusSection.appendChild(selectStatus);
     form.appendChild(statusSection);
     form.appendChild(submit);
     formDiv.appendChild(form);
     modal.appendChild(formDiv);
 }
 function submitCreateCategory(e){
    e.preventDefault();
    form = document.querySelector('.create-category');
    const formData = new FormData(form);
    fetchCall('categories.php',responseCreateCatgeory,'POST',formData);
    function responseCreateCatgeory(data){
        if(data['add_category']){
            alert('Added category to catalog');
            location.reload();
        }
        else{
            alert(data['error']);
        }
    }
}
//delete
function deleteCategory(container,id){
    //create delete button
    const deleteButton = document.createElement('button');
    deleteButton.innerText = "Delete Category";
    deleteButton.className = 'delete-button';
    container.appendChild(deleteButton);
    // Add event listener to delete button
    deleteButton.addEventListener('click', function() {
        submitDeleteCategory(id); // Pass id to submitDeleteBanner function
    });
}
function submitDeleteCategory(id){
    fetchCall(`delete_category.php?id=${id}`,responseDeleteCategory)
    function  responseDeleteCategory(data){
        if(data['deleteSuccess']){
            alert(data['deleteSuccess']);
            location.reload();
        }
        else{
            alert(data['error']);
        }
    }
}