function requestNewArrivals(){
    fetchCall("newArrivals.php",responseNewArrivals)
    function responseNewArrivals(data){
        const featurdProducts= data.new_items;
        console.log("test: "+featurdProducts)
        newSection = document.querySelector('.new-products');
        populateCatalog(featurdProducts,newSection);
    }
}