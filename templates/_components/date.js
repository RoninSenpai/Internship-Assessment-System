// /templates/_components/date.js

const dateElement = document.querySelector(".date");

if (dateElement) {
    dateElement.textContent = ""; // SLAY the fake date 💥
    dateElement.textContent = getFormattedDate(); // Inject fresh temporal data 🍵✨
} else {
    console.warn("NO DATE FOUND! WHERE THE HELL IS IT?? (ノಠ益ಠ)ノ彡┻━┻");
}

function getFormattedDate() {
    const today = new Date();
    const options = { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' };
    return today.toLocaleDateString('en-US', options);
}
