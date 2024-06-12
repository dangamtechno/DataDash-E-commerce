
function remove(stars) {
    let i = 0;
    while (i < 5) {
        stars[i].className = "star";
        i++;
    }
}
function manageStars(stars,n){
    return function(event){
       remove(stars);
       let cls = "";
       for (let i = 0; i < n; i++) {
           if (n == 1){ 
              cls = "one";
           }
           else if (n == 2) cls = "two";
           else if (n == 3) cls = "three";
           else if (n == 4) cls = "four";
           else cls = "five";
           stars[i].className = "star " + cls;
       }
   }
}
function createStarRating(section){
    for(let j = 1 ; j <= 5; j++ ){
        const i = document.createElement('span');
        i.className = "star";
        i.textContent ="★";
        section.appendChild(i);
    }
}
function setStarRating(stars,rating){
    let cls = "";
    switch(rating){
        case 1 : 
           cls = "one";
           break;
        case 2 : 
           cls = "two";
           break;
        case 3 :
           cls = "three";
           break;
        case 4 :
           cls = "four"
           break;
        case 5 :
           cls = "five";
           break;
        default : console.log(rating);
           break;
    }
    for(let i = 0 ; i < rating; i++){
       stars[i].className = `${stars[i].className} ${cls}`;
    }
}