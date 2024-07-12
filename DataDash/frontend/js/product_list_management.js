document.addEventListener('DOMContentLoaded', function() {
    // Load initial products
    fetch('../../backend/utils/filter_and_sort.php')
        .then(response => response.text())
        .then(data => {
            const productGrid = document.getElementById('product-grid');
            // Append the new products to the existing grid
        })
        .catch(error => console.error('Error loading products:', error));

    // Search functionality
    const searchForm = document.querySelector('.search-form');
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const searchTerm = document.querySelector('input[name="search"]').value;
        fetch(`../../backend/utils/search.php?submit-search=1&search=${searchTerm}`)
            .then(response => response.text())
            .then(data => {
                const productGrid = document.getElementById('product-grid');
                // Clear existing products and append the new ones
                productGrid.innerHTML = ''; // Clear existing content
                productGrid.innerHTML += data;
            })
            .catch(error => console.error('Error:', error));
    });

    // Filtering
    const filterDropdown = document.getElementById('filter-dropdown');
    filterDropdown.addEventListener('change', function() {
        const selectedCategory = this.value;
        const sortOrder = document.getElementById('sort-dropdown').value;
        fetch(`../../backend/utils/filter_and_sort.php?category=${selectedCategory}&sort=${sortOrder}`)
            .then(response => response.text())
            .then(data => {
                const productGrid = document.getElementById('product-grid');
                productGrid.innerHTML = '';
                productGrid.innerHTML += data;
            })
            .catch(error => console.error('Error:', error));
    });

    // Sorting
    const sortDropdown = document.getElementById('sort-dropdown');
    sortDropdown.addEventListener('change', function() {
        const selectedCategory = document.getElementById('filter-dropdown').value;
        const sortOrder = this.value;
        fetch(`../../backend/utils/filter_and_sort.php?category=${selectedCategory}&sort=${sortOrder}`)
            .then(response => response.text())
            .then(data => {
                const productGrid = document.getElementById('product-grid');
                productGrid.innerHTML = '';
                productGrid.innerHTML += data;
            })
            .catch(error => console.error('Error:', error));
    });
});