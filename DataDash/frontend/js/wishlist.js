// Get the wishlist container and table
const wishlistContainer = document.querySelector('.wishlist-container');
const wishlistTable = document.querySelector('.wishlist-table');

// Initialize the wishlist items array
let wishlistItems = [];

// Function to add a product to the wishlist
function addProductToWishlist(productId, productName, price) {
    // Check if the product is already in the wishlist
    const existingItem = wishlistItems.find(item => item.product_id === productId);
    if (existingItem) {
        // If the product is already in the wishlist, do nothing
        return;
    } else {
        // If the product is not in the wishlist, add it
        wishlistItems.push({ product_id: productId, product_name: productName, price: price });
    }
    updateWishlistTable();
}

// Function to update the wishlist table
function updateWishlistTable() {
    // Clear the table rows
    wishlistTable.innerHTML = '';
    // Loop through the wishlist items and add each row to the table
    wishlistItems.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.product_name}</td>
            <td>$${item.price}</td>
            <td>
                <button class="add-to-cart-button" data-product-id="${item.product_id}">Add to Cart</button>
                <button class="remove-button" data-product-id="${item.product_id}">Remove</button>
            </td>
        `;
        wishlistTable.appendChild(row);
    });
}

// Function to remove a product from the wishlist
function removeProductFromWishlist(productId) {
    // Find the product in the wishlist items array
    const item = wishlistItems.find(item => item.product_id === productId);
    if (item) {
        // Remove the item from the array
        const index = wishlistItems.indexOf(item);
        wishlistItems.splice(index, 1);
    }
    updateWishlistTable();
}

// Add event listeners for adding and removing products from the wishlist
wishlistTable.addEventListener('click', (e) => {
    if (e.target.classList.contains('add-to-cart-button')) {
        // Handle adding the product to the cart
        const productId = e.target.dataset.product_id;
        // Add your logic here to add the product to the cart
    } else if (e.target.classList.contains('remove-button')) {
        removeProductFromWishlist(e.target.dataset.product_id);
    }
});

// Update the wishlist table when the page loads
updateWishlistTable();