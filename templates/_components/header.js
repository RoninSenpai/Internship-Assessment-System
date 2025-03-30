
window.addEventListener("message", (event) => {
    if (event.data.role) {
        document.getElementById("roleText").textContent = event.data.role;
    }
});