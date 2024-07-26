function requestBanner(){
    fetchCall("banner.php",responseBanner)
    function responseBanner(data){
     if(data.banners.length > 0){
         const banners = data.banners
         banners.forEach((banner) => {
             console.log(banner.image);
             const slide = document.createElement("div")
             slide.className = "swiper-slide";
             slide.style.backgroundImage=` url('../images/banner/${banner.image}')`;
             slide.style.backgroundSize = "cover";
             slide.style.height="50vh";
             const h3 = document.createElement('h3');
             h3.textContent = banner.name;
             const p = document.createElement('p');
             p.textContent = banner.description;
             const button = document.createElement('button');
             button.textContent = 'Shop Now';
             const swiperWrapper = document.querySelector(".swiper-wrapper");
             slide.append(h3);
             slide.append(p);
             slide.append(button);
             swiperWrapper.append(slide);
         });
         callCarousal();
        }
    }
 }
 function callCarousal(){
    const swiper = new Swiper('.swiper', {
       
        loop: true,
      
        // If we need pagination
        pagination: {
          el: '.swiper-pagination',
        },
      
        // Navigation arrows  
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      
      
      });

}