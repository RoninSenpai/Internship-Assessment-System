
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

const ICON_DIR = "/static/images/components/";

const sidebarItems = {
    admin: [
        { name: "HOME", icon: ICON_DIR + "home_icon.png", link: "/templates/schooluser/admin/home.html" },
        { name: "MASTERLIST", icon: ICON_DIR + "masterlist_icon.png", link: "/templates/schooluser/admin/masterlist.html" },
        { name: "DATABASE", icon: ICON_DIR + "database_icon.png", link: "/templates/schooluser/admin/database.html" },
        { name: "USERLOG", icon: ICON_DIR + "flag_icon.png", link: "/templates/schooluser/admin/userlog.html" }
    ],
    faculty: [
        { name: "HOME", icon: ICON_DIR + "home_icon.png", link: "/templates/schooluser/faculty/home.html" },
        { name: "PROGRESS", icon: ICON_DIR + "flag_icon.png", link: "/templates/schooluser/faculty/progress.html" },
        { name: "MASTERLIST", icon: ICON_DIR + "masterlist_icon.png", link: "/templates/schooluser/faculty/masterlist.html" },
        { name: "RUBRICS", icon: ICON_DIR + "checkbox_icon.png", link: "/templates/schooluser/faculty/rubrics.html" },
        { name: "REPORTS", icon: ICON_DIR + "report_icon.png", link: "/templates/schooluser/faculty/reports.html" }
    ],
    supervisor: [
        { name: "HOME", icon: ICON_DIR + "home_icon.png", link: "/templates/supervisor/home.html" },
        { name: "MASTERLIST", icon: ICON_DIR + "masterlist_icon.png", link: "/templates/supervisor/masterlist.html" }
    ],
    student: [
        { name: "HOME", icon: ICON_DIR + "home_icon.png", link: "/templates/schooluser/student/home.html" },
        { name: "PROFILE", icon: ICON_DIR + "masterlist_icon.png", link: "/templates/schooluser/student/profile.html" },
        { name: "EVALUATION", icon: ICON_DIR + "checkbox_icon.png", link: "/templates/schooluser/student/evaluation.html" },
        { name: "PORTFOLIO", icon: ICON_DIR + "report_icon.png", link: "/templates/schooluser/student/portfolio.html" }
    ]
};

function loadSidebar(role) {
    const sidebar = document.querySelector(".sidebar-content");
    sidebar.innerHTML = ""; // Clear existing items

    // **💀 ADD THE BURGER BUTTON FIRST!**
    const burger = document.createElement("div");
    burger.className = "sidebar-item";
    burger.setAttribute("onclick", "toggleSidebar()");

    const burgerImg = document.createElement("img");
    burgerImg.src = ICON_DIR + "burger_icon.png";
    burgerImg.alt = "Menu";
    burgerImg.className = "icon";

    burger.appendChild(burgerImg);
    sidebar.appendChild(burger); // **Always first!**

    // **💀 THEN ADD USER-SPECIFIC ITEMS!**
    sidebarItems[role].forEach(item => {
        const div = document.createElement("div");
        div.className = "sidebar-item";
        div.setAttribute("onclick", `changeIframe('${item.link}')`);

        const span = document.createElement("span");
        span.textContent = item.name;

        const img = document.createElement("img");
        img.src = item.icon;
        img.alt = "Icon";
        img.className = "icon";

        div.appendChild(span);
        div.appendChild(img);
        sidebar.appendChild(div);
    });
}

window.addEventListener("message", (event) => {
    if (event.data.role) {
        loadSidebar(event.data.role);
    }
});