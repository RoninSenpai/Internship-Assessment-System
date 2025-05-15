let currentPage = 1;
let limit = 10;
let totalCount = 0;
let maxPages = 1;
let searchTimeout;

document.getElementById("search").addEventListener("input", function () {
    clearTimeout(searchTimeout); // cancel last scheduled update

    searchTimeout = setTimeout(() => {
        updateTable(0); // re-fetch with search query
    }, 300); // 300ms debounce to prevent spam-fetching
});

document.getElementById("limitInput").addEventListener("input", () => {
    limit = Math.max(1, Math.min(parseInt(limitInput.value), totalCount));
    limitInput.value = limit;
    currentPage = 1;
    updateTable();
});

document.getElementById("pageInput").addEventListener("input", () => {
    let page = parseInt(pageInput.value);
    page = Math.max(1, Math.min(page, maxPages));
    currentPage = page;
    pageInput.value = currentPage;
    updateTable();
});

document.getElementById("resetButton").addEventListener("click", () => {
    limit = 10;
    currentPage = 1;
    document.getElementById("limitInput").value = limit;
    document.getElementById("pageInput").value = currentPage;
    updateTable();
});

document.getElementById("maxButton").addEventListener("click", () => {
    limit = totalCount;
    currentPage = 1;
    document.getElementById("limitInput").value = limit;
    document.getElementById("pageInput").value = currentPage;
    updateTable();
});

document.getElementById("prevButton").addEventListener("click", () => {
    if (currentPage > 1) {
        currentPage--;
        updateTable();
    }
});

document.getElementById("nextButton").addEventListener("click", () => {
    if (currentPage < maxPages) {
        currentPage++;
        updateTable();
    }
});

let currentSort = {
    key: null,
    direction: "asc", // or 'desc'
};

document.querySelectorAll(".sortable").forEach((th) => {
    th.addEventListener("click", () => {
        const key = th.dataset.key;

        if (currentSort.key === key) {
            // Flip direction if clicking same column
            currentSort.direction =
                currentSort.direction === "asc" ? "desc" : "asc";
        } else {
            currentSort.key = key;
            currentSort.direction = "asc";
        }

        // Update triangle icons
        document
            .querySelectorAll(".sort-icon")
            .forEach((icon) => (icon.textContent = ""));
        const icon = th.querySelector(".sort-icon");
        icon.textContent = currentSort.direction === "asc" ? "▲" : "▼";

        // Refetch with sort
        updateTable();
    });
});

function updateTable(direction = 0) {
    const limit = parseInt(document.getElementById("limitInput").value) || 5;
    let page = parseInt(document.getElementById("pageInput").value) || 1;
    const search = document.getElementById("search").value.trim();

    page += direction;
    if (page < 1) page = 1;

    let url = new URL(window.location.href);
    url.searchParams.set("ajax", "1");
    url.searchParams.set("limit", limit);
    url.searchParams.set("page", currentPage);
    url.searchParams.set("search", search); // pass search term
    if (currentSort.key) {
        url.searchParams.set("sort_by", currentSort.key);
        url.searchParams.set("sort_dir", currentSort.direction);
    }

    // Example: collect visible columns based on checkbox states (adapt to your UI)
    const visibleColumns = [];
    document.querySelectorAll(".column-toggle-checkbox").forEach((cb) => {
        if (cb.checked) visibleColumns.push(cb.value);
    });
    url.searchParams.set("visible_columns[]", visibleColumns); // PHP reads this as array

    fetch(url)
        .then((res) => {
            if (!res.ok) throw new Error("HTTP Error: " + res.status);
            return res.text(); // Always get raw text first
        })
        .then((text) => {
            try {
                const data = JSON.parse(text);
                // console.log("✅ Parsed JSON:", data);

                // Handle case when no users are found
                if (data.totalCount === 0) {
                    document.querySelector("#allColumns tbody").innerHTML = `
                        <tr><td colspan="99">No results found.</td></tr>
                    `;
                    document.getElementById("totalCount").textContent = 0;
                    document.getElementById("pageInput").value = 1;
                    return;
                }

                // Proceed with existing logic
                document.getElementById("totalCount").textContent =
                    data.totalCount;
                document.getElementById("pageInput").value = currentPage;
                totalCount = data.totalCount;
                maxPages = Math.ceil(totalCount / limit);

                const tbody = document.querySelector("#allColumns tbody");
                tbody.innerHTML = "";

                let rowNumber = (currentPage - 1) * limit + 1;
                data.data.forEach((row) => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td><input type="checkbox" class="select-row"></td>
                        <td>${rowNumber++}</td>
                        <td data-column="Email">${row.email}</td>
                        <td data-column="Name">${row.name}</td>

                        <!-- School Information -->
                        <td data-column="Batch">${row.batch || "—"}</td>
                        <td data-column="School and Program">${
                            row.school_program || "—"
                        }</td>
                        <td data-column="Internship Year">${
                            row.year || "—"
                        }</td>
                        <td data-column="Internship Start">${
                            row.start || "—"
                        }</td>
                        <td data-column="Internship End">${row.end || "—"}</td>

                        <!-- Internship Information -->
                        <td data-column="Company">${row.company || "—"}</td>
                        <td data-column="Department">${
                            row.department || "—"
                        }</td>
                        <td data-column="Job Role">${row.job_role || "—"}</td>
                        <td data-column="Supervisor">${
                            row.supervisor || "—"
                        }</td>
                        <td data-column="Supervisor Email">${
                            row.supervisor_email || "—"
                        }</td>
                        <td data-column="Supervisor Contact No">${
                            row.supervisor_contact || "—"
                        }</td>

                        <!-- User Information -->
                        <td data-column="Gender">${row.gender || "—"}</td>
                        <td data-column="Birthdate">${row.birthdate || "—"}</td>
                        <td data-column="Address">${row.address || "—"}</td>
                        <td data-column="Date Added">${row.created || "—"}</td>
                        <td data-column="Date Updated">${
                            row.updated || "—"
                        }</td>
                    `;
                    tbody.appendChild(tr);

                    applyColumnToggles();
                    document.getElementById("select-all-rows").checked = false;
                });
            } catch (err) {
                console.error("💀 Failed to parse JSON:", err);
                console.warn("📦 RAW TEXT RECEIVED:", text); // Let’s see what really came back
            }
        })
        .catch((err) => {
            console.error("💩 FETCH DIED:", err);
        });
}

// 🧛 Initial load
updateTable();

function setInputListener(input, clean = false) {
    input.addEventListener("input", () => {
        if (!clean) {
            const min = parseFloat(input.min);
            const max = parseFloat(input.max);
            let val = parseFloat(input.value) || 0;

            if (!isNaN(min)) val = Math.max(val, min);
            if (!isNaN(max)) val = Math.min(val, max);

            input.value = val;

            // console.log("hello");
        }

        updateTable();
    });
}

// // ────────────────────────────────────────────────────────────────────────
function updateGroupState(groupCheckbox) {
    const container = groupCheckbox.closest("details");
    const checkboxes = container.querySelectorAll(":scope .option");
    const checkedCount = Array.from(checkboxes).filter(
        (cb) => cb.checked
    ).length;

    if (checkedCount === checkboxes.length && checkedCount > 0) {
        groupCheckbox.indeterminate = false;
        groupCheckbox.checked = true;
    } else if (checkedCount > 0) {
        groupCheckbox.indeterminate = true;
        groupCheckbox.checked = false;
    } else {
        groupCheckbox.indeterminate = false;
        groupCheckbox.checked = false;
    }
}

function propagateGroupChange(groupCheckbox) {
    const container = groupCheckbox.closest("details");
    const checkboxes = container.querySelectorAll(":scope .option");
    checkboxes.forEach((cb) => (cb.checked = groupCheckbox.checked));

    const subGroups = container.querySelectorAll(
        ":scope details > summary > input.select-all"
    );
    subGroups.forEach((subGroup) => {
        if (subGroup !== groupCheckbox) {
            subGroup.checked = groupCheckbox.checked;
            subGroup.indeterminate = false;
            propagateGroupChange(subGroup);
        }
    });

    // 💥 UPDATE ALL PARENTS
    let parent = groupCheckbox
        .closest("details")
        .parentElement.closest("details");
    while (parent) {
        const parentGroup = parent.querySelector(
            ":scope > summary > input.select-all"
        );
        if (parentGroup) updateGroupState(parentGroup);
        parent = parent.parentElement.closest("details");
    }
}

document
    .querySelectorAll("summary > input.select-all")
    .forEach((groupCheckbox) => {
        groupCheckbox.addEventListener("change", () => {
            propagateGroupChange(groupCheckbox);
        });
    });

document.querySelectorAll("input.option").forEach((optionCheckbox) => {
    optionCheckbox.addEventListener("change", () => {
        let parent = optionCheckbox.closest("details");
        while (parent) {
            const groupCheckbox = parent.querySelector(
                ":scope > summary > input.select-all"
            );
            if (groupCheckbox) updateGroupState(groupCheckbox);
            parent = parent.parentElement.closest("details");
        }
    });
});

function initColumnControls() {
    // Per-column toggles
    document
        .querySelectorAll("#columnControls input.option")
        .forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                const columnName = this.dataset.column;
                const isVisible = this.checked;

                document
                    .querySelectorAll('th[data-column="' + columnName + '"]')
                    .forEach((th) => {
                        th.style.display = isVisible ? "" : "none";
                    });

                document
                    .querySelectorAll('td[data-column="' + columnName + '"]')
                    .forEach((td) => {
                        td.style.display = isVisible ? "" : "none";
                    });
            });
        });

    // Master toggles
    document
        .querySelectorAll("#columnControls .select-all")
        .forEach((masterCheckbox) => {
            masterCheckbox.addEventListener("change", function () {
                const container = this.closest("details, #columnControls");
                container
                    .querySelectorAll("input.option")
                    .forEach((optionCheckbox) => {
                        optionCheckbox.checked = this.checked;
                        optionCheckbox.dispatchEvent(new Event("change"));
                    });
            });
        });
}

// Run it on DOM ready
document.addEventListener("DOMContentLoaded", initColumnControls);

function toggleColumn(columnName, isVisible) {
    document
        .querySelectorAll(
            'th[data-column="' +
                columnName +
                '"], td[data-column="' +
                columnName +
                '"]'
        )
        .forEach((el) => {
            el.style.display = isVisible ? "" : "none";
        });
}

function applyColumnToggles() {
    // console.log("Applying column toggles...");
    document.querySelectorAll("#columnControls input.option").forEach((cb) => {
        toggleColumn(cb.dataset.column, cb.checked);
        // console.log(`Toggling column ${cb.dataset.column} to ${cb.checked}`);
    });
}

// Delegate all change events on the document
document.addEventListener("change", (e) => {
    // If the header checkbox toggled...
    if (e.target.id === "select-all-rows") {
        const checked = e.target.checked;
        // Query ALL current row checkboxes, now or later
        document.querySelectorAll(".select-row").forEach((cb) => {
            cb.checked = checked;
        });
    }

    // If any single row checkbox toggled...
    if (e.target.classList.contains("select-row")) {
        const allRows = Array.from(document.querySelectorAll(".select-row"));
        // Only check master if there’s at least one row, and every row is checked
        const allChecked =
            allRows.length > 0 && allRows.every((cb) => cb.checked);
        document.getElementById("select-all-rows").checked = allChecked;
    }
});
