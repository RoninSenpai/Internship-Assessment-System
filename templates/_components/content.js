// Step 1: Create and inject the "Back to Top" button into the page
const backToTopBtn = document.createElement('button');
backToTopBtn.id = 'backToTopBtn';
backToTopBtn.textContent = '↑ Back to Top';
backToTopBtn.style.display = 'none'; // Hide it initially
backToTopBtn.onclick = scrollToTop;
document.body.appendChild(backToTopBtn);

// Step 2: Add some nasty lil' CSS so it don’t look like dogwater
const style = document.createElement('style');
style.textContent = `
    #backToTopBtn {
        position: fixed;
        bottom: 20px;
        right: 32px;
        z-index: 9999;
        background-color: #66666680;
        transition: background-color 0.1s ease;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
    }

    #backToTopBtn:hover {
        background-color: #666;
    }

    .spacer {
        height: 50px; /* Same height as the "Back to Top" button */
        width: 100%;
        display: block;
        margin-bottom: 80px;
    }
`;
document.head.appendChild(style);

// Step 3: Keep your old demon code, but let it vibe with the summoned button and spacer
const scrollContainer = document.querySelector(".content");
let spacerEl = null;

scrollContainer.addEventListener("scroll", function () {
    console.log("Scrolling detected inside .content (aka hell's scroll zone)");
    toggleButton(scrollContainer);
});

function toggleButton(el) {
    if (el.scrollTop > 200) {
        document.getElementById("backToTopBtn").style.display = "block";

        // Create the spacer if it's not already there
        if (!spacerEl) {
            spacerEl = document.createElement('div');
            spacerEl.className = 'spacer';
            scrollContainer.appendChild(spacerEl);
        }
    } else {
        document.getElementById("backToTopBtn").style.display = "none";

        // Remove the spacer when the button is hidden
        if (spacerEl) {
            scrollContainer.removeChild(spacerEl);
            spacerEl = null;
        }
    }
}

function scrollToTop() {
    scrollContainer.scrollTo({ top: 0, behavior: 'smooth' });
}

const greetingEl = document.getElementById('greeting');
const now = new Date();
const hour = now.getHours();

let greeting = "Hello";

if (hour >= 5 && hour < 12) {
    greeting = "Good morning";
} else if (hour >= 12 && hour < 18) {
    greeting = "Good afternoon";
} else if (hour >= 18 && hour < 22) {
    greeting = "Good evening";
} else {
    greeting = "Go to sleep you crusty goblin";
}

greetingEl.textContent = `${greeting}, Mr. John Park!`;
