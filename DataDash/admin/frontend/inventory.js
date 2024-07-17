
function addInventoryButton(){
    const username = document.querySelector('.username').textContent;
    //console.log(username);
    const adminButtons = document.querySelector('.admin-buttons');
    const inventory    =   document.createElement('li');
    const editInventoryButton = document.createElement('button');
    editInventoryButton.textContent='Edit Inventory';
    editInventoryButton.className='model-button';
    inventory.append(editInventoryButton);
    adminButtons.appendChild(inventory);
    editInventoryButton.addEventListener('click',requestInventory);
}

function updateQuantity(id, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${backend_url}update_inventory.php`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data.message);
            requestInventory();
        } else {
            console.error('Error updating quantity:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify({ id, quantity }));
}

function addItem() {
    const name = document.getElementById('newItemName').value;
    console.log(name);
    const quantity = document.getElementById('newItemQuantity').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${backend_url}add_inventory.php`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data.message);
            alert(data.message);
            requestInventory();
        } else {
            console.error('Error adding item:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify({ name, quantity }));
}

function deleteItem(id) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', `${backend_url}delete_inventory.php`, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data.message);
            alert(data.message);
            requestInventory();
        } else {
            console.error('Error deleting item:', xhr.statusText);
        }
    };
    xhr.send(JSON.stringify({id}));
}

function addNewRowSection(section){
    const  addNewRowSectionContainer =   document.createElement('div');
    const h2 = document.createElement('h2');
    h2.innerText="ADD new row";
    const newItemName=document.createElement('input');
    newItemName.name='name';newItemName.type="text";newItemName.id="newItemName"; newItemName.placeholder="Product Name";

    const newItemQuantity=document.createElement('input');
    newItemQuantity.name='quantity';
    newItemQuantity.type="number";newItemQuantity.id="newItemQuantity"; newItemQuantity.placeholder="Quantity";
    const button=document.createElement('button');
    button.addEventListener('click',addItem);
    button.innerText="Add Item";
    addNewRowSectionContainer.appendChild(h2);
    addNewRowSectionContainer.appendChild(newItemName);
    addNewRowSectionContainer.appendChild(newItemQuantity);
    addNewRowSectionContainer.appendChild(button);
    section.appendChild(addNewRowSectionContainer);
}


function displayInventory(data) {
    const inventoryContainer = document.createElement('div');
    inventoryContainer.className="form-div";
    inventoryContainer.id= 'inventoryContainer';
    addNewRowSection(inventoryContainer);
    const table = document.createElement('table'); // Create table element
    const thead = document.createElement('thead'); // Create table header element
    const tbody = document.createElement('tbody'); // Create table body element

    // Create table headers
    const headerRow = document.createElement('tr');
    headerRow.innerHTML = `
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Actions</th>
    `;
    thead.appendChild(headerRow);

    // Create table rows with inventory data
    data.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
    
            <td>${item.product_name}</td>
            <td><input type="number" value="${item.quantity}" onchange="updateQuantity(${item.product_id}, this.value)"></td>
            <td><button onclick="deleteItem(${item.product_id})">Delete</button></td>
        `;
        tbody.appendChild(row);
    });

    table.appendChild(thead);
    table.appendChild(tbody);
    inventoryContainer.appendChild(table);
    displayOverlay(inventoryContainer);
}


function requestInventory(){
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `${backend_url}fetch_inventory.php`, true); // Replace 'fetch_inventory.php' with your server endpoint
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            const data = JSON.parse(xhr.responseText);
            console.log(data); // Output the received data for debugging
            displayInventory(data); // Call function to display inventory data
        } else {
            console.error('Error fetching data:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
    };
    xhr.send();
    
}