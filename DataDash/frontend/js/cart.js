// Get the cart container and table
const cartContainer = document.querySelector('.cart-container');
const cartTable = document.querySelector('.cart-table');

// Get the cart total span
const cartTotalSpan = document.querySelector('#cart-total');

// Get the checkout button
const checkoutButton = document.querySelector('.checkout-button');

// Initialize the cart items array
let cartItems = [];

// Function to add a product to the cart
function addProductToCart(productId) {
    // Check if the product is already in the cart
    const existingItem = cartItems.find(item => item.product_id === productId);
    if (existingItem) {
        // If the product is already in the cart, increment the quantity
        existingItem.quantity++;
    } else {
        // If the product is not in the cart, add it with a quantity of 1
        cartItems.push({ product_id: productId, quantity: 1 });
    }
    updateCartTable();
}

// Function to update the cart table
function updateCartTable() {
    // Clear the table rows
    cartTable.innerHTML = '';
    // Loop through the cart items and add each row to the table
    cartItems.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.product_name}</td>
            <td>$${item.price}</td>
            <td>${item.quantity}</td>
            <td><button class="remove-button" data-product-id="${item.product_id}">Remove</button></td>
        `;
        cartTable.appendChild(row);
    });
    // Update the cart total
    let total = 0;
    cartItems.forEach((item) => {
        total += item.price * item.quantity;
    });
    cartTotalSpan.textContent = `$${total.toFixed(2)}`;
}

// Function to remove a product from the cart
function removeProductFromCart(productId) {
    // Find the product in the cart items array
    const item = cartItems.find(item => item.product_id === productId);
    if (item) {
        // Decrement the quantity if it's more than 1
        if (item.quantity > 1) {
            item.quantity--;
        } else {
            // If the quantity is 1, remove the item from the array
            const index = cartItems.indexOf(item);
            cartItems.splice(index, 1);
        }
    }
    updateCartTable();
}

// Add event listeners for adding and removing products from the cart
cartTable.addEventListener('click', (e) => {
    if (e.target.classList.contains('add-button')) {
        addProductToCart(e.target.dataset.product_id);
    } else if (e.target.classList.contains('remove-button')) {
        removeProductFromCart(e.target.dataset.product_id);
    }
});

// Update the cart table when the page loads
updateCartTable();
