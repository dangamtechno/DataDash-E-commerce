function remove(stars) {
  let i = 0;
  while (i < 5) {
    stars[i].className = "star";
    i++;
  }
}

function manageStars(stars) {
  return function(event) {
    remove(stars);
    let n = parseInt(event.target.dataset.value);

    for (let i = 0; i < n; i++) {
      let cls = "";
      if (i == 0) {
        cls = "one";
      } else if (i == 1) {
        cls = "two";
      } else if (i == 2) {
        cls = "three";
      } else if (i == 3) {
        cls = "four";
      } else {
        cls = "five";
      }
      stars[i].className = "star " + cls;
    }
  }
}

function createStarRating(section) {
  for (let j = 1; j <= 5; j++) {
    const i = document.createElement('span');
    i.className = "star";
    i.textContent = "★";
    i.dataset.value = j;
    section.appendChild(i);
  }
  // Add event listeners after creating all stars
  section.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', manageStars(section.querySelectorAll('.star')));
  });
}

function setStarRating(stars, rating) {
  remove(stars); // Clear existing classes

  for (let i = 0; i < rating; i++) {
    let cls = "";
    if (i == 0) {
      cls = "one";
    } else if (i == 1) {
      cls = "two";
    } else if (i == 2) {
      cls = "three";
    } else if (i == 3) {
      cls = "four";
    } else {
      cls = "five";
    }
    stars[i].className = "star " + cls;
  }
}