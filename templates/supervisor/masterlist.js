const scrollContainer = document.querySelector(".content");

scrollContainer.addEventListener("scroll", function () {
    console.log("Scrolling detected inside .content");
    toggleButton(scrollContainer);
});

function toggleButton(el) {
    if (el.scrollTop > 200) {
        document.getElementById("backToTopBtn").style.display = "block";
    } else {
        document.getElementById("backToTopBtn").style.display = "none";
    }
}

function scrollToTop() {
    scrollContainer.scrollTo({ top: 0, behavior: 'smooth' });
}
