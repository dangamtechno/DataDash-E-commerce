document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchForm = document.querySelector('.search-bar');
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        const searchInput = document.querySelector('input[name="search"]');
        const searchTerm = searchInput.value;
        fetchData('backend/utils/search.php', { 'submit-search': 1, search: searchTerm }, updateProductGrid);
    });

    // Filtering
    const filterDropdown = document.getElementById('filter-dropdown');
    if(filterDropdown){
        filterDropdown.addEventListener('change', function() {
            const selectedCategory = this.value;
            fetchData('backend/utils/filter.php', { category: selectedCategory }, updateProductGrid);
        });
    }

    // Sorting
    const sortDropdown = document.getElementById('sort-dropdown');
    if(sortDropdown){
        sortDropdown.addEventListener('change', function() {
            const sortOrder = this.value;
            fetchData('backend/utils/sort.php', { sort: sortOrder }, updateProductGrid);
        });
    }

    function fetchData(url, data, callback) {
        const params = new URLSearchParams(data).toString();
        const requestUrl = `${url}?${params}`;

        fetch(requestUrl)
            .then(response => response.text())
            .then(data => callback(data))
            .catch(error => console.error('Error:', error));
    }

    function updateProductGrid(response) {
        const productGrid = document.querySelector('.product-grid');
        productGrid.innerHTML = response;
    }
});