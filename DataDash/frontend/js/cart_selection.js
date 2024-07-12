// Select All functionality
const selectAllCheckbox = document.getElementById('select-all');
const selectItemCheckboxes = document.querySelectorAll('.select-item');
const totalPriceElement = document.getElementById('total-price');

selectAllCheckbox.addEventListener('change', () => {
    selectItemCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
        updateTotalPrice();
        updateSelectedProducts();
    });
});

// Update selected products on checkbox change
function updateSelectedProducts() {
    const selectedProductIds = [];
    const selectedQuantities = {};
    selectItemCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            const productId = checkbox.dataset.productId;
            const quantityInput = document.querySelector(`input[name="quantity[${productId}]"]`);
            const quantity = parseInt(quantityInput.value);
            selectedProductIds.push(productId);
            selectedQuantities[productId] = quantity;
        }
    });

    // Update hidden input fields with selected product data
    const selectedProductsInput = document.getElementById('selected-products');
    selectedProductsInput.value = JSON.stringify(selectedProductIds);

    const selectedQuantitiesInput = document.getElementById('selected-quantities');
    selectedQuantitiesInput.value = JSON.stringify(selectedQuantities);
}
selectItemCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedProducts);
    checkbox.addEventListener('change', updateTotalPrice);
});

// Update total price based on selected items
function updateTotalPrice() {
    let selectedPrice = 0;
    selectItemCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selectedPrice += parseFloat(checkbox.dataset.price);
        }
    });
    totalPriceElement.textContent = selectedPrice.toFixed(2);
}

// Initial total price (when no items are selected)
updateTotalPrice();