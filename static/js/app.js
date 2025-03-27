
const nav = document.querySelector(".nav");

  /* -- (1)  Scroll () -- */

window.addEventListener("scroll", fixNav);
function fixNav() {
  if (window.scrollY > nav.offsetHeight + 150) {
    nav.classList.add("active");
  } else {
    nav.classList.remove("active");
  }
}

  /* -- (2)  Navigation Bar (NAV bar replaces the header when scrolled up) -- */


window.addEventListener("scroll", function () {
    let nav = document.querySelector(".nav");
    let header = document.querySelector(".header");
  
    if (window.scrollY > header.offsetHeight) {
      nav.classList.add("fixed");
    } else {
      nav.classList.remove("fixed");
    }
  });
  