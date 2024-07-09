$(document).ready(function() {
    $("#search-form").submit(function(event) {
        event.preventDefault();
        var searchTerm = $("#search-input").val();

        // Redirect to shop.php with search term as a query parameter
        window.location.href = "shop.php?search=" + searchTerm;
    });
});