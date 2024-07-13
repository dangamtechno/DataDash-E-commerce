
function addBrandsUpdateButton(){
    const username = document.querySelector('.username').textContent;
    //console.log(username);
    const adminButtons = document.querySelector('.admin-buttons');
    const brands    =   document.createElement('li');
    const editBrandsButton = document.createElement('button');
    editBrandsButton.textContent='Edit Brands';
    editBrandsButton.className='model-button';
    brands.append(editBrandsButton);
    adminButtons.appendChild(brands);
    editBrandsButton.addEventListener('click',requestBrands);
    //console.log(adminButtons);
    //alert(adminButtons.textContent);
}



function requestBrands(){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `${backend_url}brands.php`, true); 
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data); // Output the received data for debugging
            displayBrands(data.brands);
        } else {
            console.error('Error fetching data:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
    
}


function addNewBrandSection(section){
    const  addNewBrandSectionContainer =   document.createElement('div');
    const h2 = document.createElement('h2');
    h2.innerText="Add New Brand";
    const newBrandName=document.createElement('input');
    newBrandName.name='name';newBrandName.type="text";newBrandName.id="newBrandName"; newBrandName.placeholder="Brand Name";

  
    const button=document.createElement('button');
    button.addEventListener('click',addBrand);
    button.innerText="Add Brand";
    addNewBrandSectionContainer.appendChild(h2);
    addNewBrandSectionContainer.appendChild(newBrandName);
    addNewBrandSectionContainer.appendChild(button);
    section.appendChild(addNewBrandSectionContainer);
}

function displayBrands(data) {
    const brandContainer = document.createElement('div');
    brandContainer.className="form-div";
    brandContainer.id= 'brandContainer';
    addNewBrandSection(brandContainer);
    const table = document.createElement('table'); // Create table element
    const thead = document.createElement('thead'); // Create table header element
    const tbody = document.createElement('tbody'); // Create table body element

    // Create table headers
    const headerRow = document.createElement('tr');
    headerRow.innerHTML = `
        <th>Product Name</th>
        <th>Actions</th>
    `;
    thead.appendChild(headerRow);

    // Create table rows with inventory data
    data.forEach(brand => {
        const row = document.createElement('tr');
        row.innerHTML = `
    
            <td>  
            <input type="text" value="${brand.brand_name}" onchange="updateName(${brand.brand_id}, this.value)">
           </td>
            <td><button onclick="deleteBrand(${brand.brand_id})">Delete</button></td>
        `;
        tbody.appendChild(row);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    brandContainer.appendChild(table);
    displayOverlay(brandContainer);
}







function updateName(id, name) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${backend_url}update_brand.php`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data.message);
            requestBrands();
        } else {
            console.error('Error updating quantity:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify({ id, name }));
}

function addBrand() {
    const name = document.getElementById('newBrandName').value;
    console.log(name);
    //const quantity = document.getElementById('newItemQuantity').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${backend_url}add_brand.php`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data.message);
            alert(data.message);
            requestBrands();
        } else {
            console.error('Error adding item:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify({ name}));
}

function deleteBrand(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${backend_url}delete_brand.php`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data.message);
            alert(data.message);
            requestBrands();
        } else {
            console.error('Error deleting item:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify({id}));
}


