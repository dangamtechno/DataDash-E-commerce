
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
    //console.log(adminButtons);
    //alert(adminButtons.textContent);
}

function requestInventory(){
    fetchCall('inventory.php',responseInventory);
    function responseInventory(data){
        console.log(data);
    }
}