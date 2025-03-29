
function toggleSidebar() {
    console.log("Sidebar toggled");
    window.parent.document.querySelector(".sidebar").classList.toggle("open");
    document.querySelectorAll(".sidebar-item").forEach(item => {
        item.classList.toggle("open");
    });
}

function changeIframe(newSrc) {
    window.parent.document.getElementById("content").src = newSrc;
}
