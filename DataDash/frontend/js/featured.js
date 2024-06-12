function requestFeaturedProducts(){
    fetchCall("featuredProducts.php",responseFeaturedProducts)
    function responseFeaturedProducts(data){
        const featuredProducts= data.featuredProducts;
        featuredSection = document.querySelector('.featured-products');
        populateCatalog(featuredProducts,featuredSection);
    }
}