function requestFeaturedProducts(){
    fetchCall("featured_products.php",responseFeaturedProducts)
    function responseFeaturedProducts(data){
        const featuredProducts= data.featured_products;
        console.log(featuredProducts);
        featuredSection = document.querySelector('.featured-products');
        populateCatalog(featuredProducts,featuredSection);
    }
}