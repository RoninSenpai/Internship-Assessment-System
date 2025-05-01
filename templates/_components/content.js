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
        user-select: none;
    }

    #backToTopBtn:hover {
        background-color: #666;
    }

    .spacer {
        height: 80px; /* Same height as the "Back to Top" button */
        width: 100%;
        display: block;
        flex-shrink: 0;
    }
`;

document.head.appendChild(style);

let spacerEl = null;
const scrollContainer = document.querySelector(".content");

window.addEventListener("scroll", function () {
    // console.log("Scrolling detected inside window");
    toggleButton(scrollContainer);
});

scrollContainer.addEventListener("scroll", function () {
    // console.log("Scrolling detected inside scrollContainer");
    toggleButton(scrollContainer);
});

function toggleButton(el) {
    if (el.scrollTop > 300) {
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
    greeting = "Go to sleep my lovely client";
}

// Grab the current text, find the comma, and replace only before it 😎🔧
if (greetingEl) {
    let currentText = greetingEl.textContent;
    let namePart = currentText.substring(currentText.indexOf(','));

    // Now reforge the line like the cursed blacksmith you are
    greetingEl.textContent = `${greeting}${namePart}`;
}