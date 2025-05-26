// search

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

// pagination

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

// sort

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

// update table

function updateTable(direction = 0, blink = false) {
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
    document.querySelectorAll(".option").forEach((cb) => {
        if (cb.checked) {
            visibleColumns.push(cb.dataset.column); // not cb.value
        }
    });
    url.searchParams.delete("visible_columns[]"); // Ensure no duplicate keys
    visibleColumns.forEach((col) =>
        url.searchParams.append("visible_columns[]", col)
    );

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
                    tr.dataset.internshipId = row.internship_id; // 💉 YEAH SLAM IT IN
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
                            row.supervisor_contact_no || "—"
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
                });

                applyColumnToggles();
                document.getElementById("select-all-rows").checked = false;

                if (blink) {
                    const table = document.querySelector("#allColumns");
                    table.classList.remove("blink"); // reset if already blinking
                    void table.offsetWidth; // trigger reflow to restart animation
                    table.classList.add("blink");
                }
            } catch (err) {
                console.error("💀 Failed to parse JSON:", err);
                console.warn("📦 RAW TEXT RECEIVED:", text); // Let’s see what really came back
            }
        })
        .catch((err) => {
            console.error("💩 FETCH DIED:", err);
        });
}

updateTable();

// input listener

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

// column controls

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

// edit buttons

function editButtons() {
    // NEW: Toggle buttons based on selection count
    const selectedRows = document.querySelectorAll(
        ".select-row:checked"
    ).length;

    const btnViewUser = document.querySelector(
        ".edit-buttons button:nth-child(2)"
    ); // 2nd button: View User
    const btnViewBatch = document.querySelector(
        ".edit-buttons button:nth-child(3)"
    ); // 3rd button

    if (selectedRows === 1) {
        btnViewUser.disabled = false;
        btnViewBatch.disabled = false;
        btnViewUser.removeAttribute("data-tooltip");
        btnViewBatch.removeAttribute("data-tooltip");
    } else {
        btnViewUser.disabled = true;
        btnViewBatch.disabled = true;
        btnViewUser.setAttribute("data-tooltip", "Select only one user first.");
        btnViewBatch.setAttribute(
            "data-tooltip",
            "Select only one user first."
        );
    }
}

document.addEventListener("change", (e) => {
    // If the header checkbox toggled...
    if (e.target.id === "select-all-rows") {
        const checked = e.target.checked;
        // Query ALL current row checkboxes, now or later
        document.querySelectorAll(".select-row").forEach((cb) => {
            cb.checked = checked;
            cb.closest("tr").classList.toggle("selected-row", checked);
        });
    }

    // If any single row checkbox toggled...
    if (e.target.classList.contains("select-row")) {
        const row = e.target.closest("tr");
        row.classList.toggle("selected-row", e.target.checked);

        const allRows = Array.from(document.querySelectorAll(".select-row"));
        // Only check master if there’s at least one row, and every row is checked
        const allChecked =
            allRows.length > 0 && allRows.every((cb) => cb.checked);
        document.getElementById("select-all-rows").checked = allChecked;
    }

    editButtons();
});

// edit pane

const viewUserButton = document.getElementById("viewUserButton");
const pane = document.getElementById("user-floating-pane");
const backdrop = document.getElementById("floating-backdrop");
const closeBtn = document.querySelector(".close-pane");
const detailsDiv = document.querySelector(".user-details");
const selectAllRowsCheckbox = document.getElementById("select-all-rows");

function togglePane(show = false, userData = {}) {
    if (show) {
        // Populate pane if userData provided
        if (userData) {
            Object.entries(userData).forEach(([key, value]) => {
                const el = document.getElementById(`pane-${key}`);
                if (el) el.textContent = value;
            });
        }
        backdrop.style.display = "block";
        pane.style.display = "block";
    } else {
        backdrop.style.display = "none";
        pane.style.display = "none";
    }
}

function getSelectedRowData() {
    const selected = document.querySelector(".select-row:checked");
    if (!selected) return null;

    const row = selected.closest("tr");
    const data = {
        internship_id: row.dataset.internshipId, // 👈 suck it out like sweet forbidden nectar
    };

    row.querySelectorAll("td[data-column]").forEach((td) => {
        data[td.getAttribute("data-column")] = td.textContent.trim();
    });
    // console.log(data);
    return data;
}

function updateUserDetails(data) {
    if (!data) return;

    const infoHtml = Object.entries(data)
        .map(([key, value]) => {
            if (key.toLowerCase() === "batch") {
                return `
                    <p>
                        <strong>${key}:</strong> ${value}
                        <button id="view-batch-from-user" class="batch-button" style="margin-left: 10px;">
                            View Batch
                        </button>
                    </p>`;
            }
            return `<p><strong>${key}:</strong> ${value}</p>`;
        })
        .join("");

    // Inject content + edit button
    detailsDiv.innerHTML = `
        ${infoHtml}
        <div class="pane-footer">
            <button id="edit-user-info-btn" class="edit-button">Edit User Info</button>
            <button id="archiveUserButton" class="archive-button">Archive User</button>
        </div>
    `;

    setTimeout(() => {
        document
            .getElementById("view-batch-from-user")
            ?.addEventListener("click", () => {
                viewBatchButton?.click();
            });

        document
            .getElementById("edit-user-info-btn")
            ?.addEventListener("click", () => {
                showEditUserForm(data);
                console.log("data:\n" + JSON.stringify(data));
            });

        document
            .getElementById("archiveUserButton")
            ?.addEventListener("click", () => {
                detailsDiv.innerHTML = `
                    <h3>Archive User</h3>
                    <p>Provide a reason for archiving this user, baka~</p>
                    <textarea id="archive-reason" rows="5" cols="50" placeholder="Reason for archiving..."></textarea>
                    <div class="pane-footer">
                        <button type="button" id="cancel-archive" class="cancel-button">Cancel</button>
                        <button type="button" id="confirm-archive" class="archive-button">Confirm Archive</button>
                    </div>
                `;

                document
                    .getElementById("cancel-archive")
                    .addEventListener("click", () => {
                        updateUserDetails(data);
                    });

                document
                    .getElementById("confirm-archive")
                    .addEventListener("click", () => {
                        const reason = document
                            .getElementById("archive-reason")
                            .value.trim();

                        if (!reason)
                            return alert(
                                "Tell me WHY you're archiving, you indecisive donut 🍩"
                            );

                        // 🛑 Confirm box of doom
                        const proceed = confirm(
                            `You're about to archive this user for:\n\n"${reason}"\n\nThey will no longer appear in active lists, but their data will be preserved ` +
                                `for administrative purposes.\nDo you wish to proceed?`
                        );

                        if (!proceed) {
                            alert(
                                "🫣 Archive operation cancelled! You live to hesitate another day..."
                            );
                            return;
                        }

                        const postData = new URLSearchParams({
                            internship_id: data.internship_id,
                            update_description: reason,
                        });

                        fetch("archive_user.php", {
                            method: "POST",
                            headers: {
                                "Content-Type":
                                    "application/x-www-form-urlencoded",
                            },
                            body: postData.toString(),
                        })
                            .then(async (res) => {
                                const text = await res.text();
                                try {
                                    const json = JSON.parse(text);
                                    if (json.success) {
                                        detailsDiv.innerHTML = `
                                            <h3>💀 User Archived</h3>
                                            <p>This user has been archived for the following reason:</p>
                                            <blockquote style="border-left: 4px solid #999; padding-left: 10px; color: #444;">${reason}</blockquote>
                                        `;
                                        updateTable();
                                    } else {
                                        alert(
                                            "Archiving failed: " + json.error
                                        );
                                    }
                                } catch (e) {
                                    console.error(
                                        "Non-JSON response from PHP:",
                                        text
                                    );
                                    alert(
                                        "PHP coughed up spaghetti code again 🍝😭"
                                    );
                                }
                            })
                            .catch((err) => {
                                console.error("Network/Fetch error:", err);
                                alert(
                                    "No archive for you! Internet said 'nope' 🚫📡"
                                );
                            });
                    });
            });
    }, 0);
}

function showEditUserForm(data) {
    const fields = ["Email", "Name", "Gender", "Birthdate", "Address"];

    const formInputs = fields
        .map(
            (key) => `
        <label for="edit-${key}">
            <strong>${key.charAt(0).toUpperCase() + key.slice(1)}:</strong><br>
            <input 
                type="text" 
                id="edit-${key}" 
                name="${key}"
                value="${data[key] || ""}" 
            />
        </label>
        <br><br>
    `
        )
        .join("");

    const descriptionInput = `
        <label for="edit-update-description">
            <strong>Update Description:</strong><br>
            <textarea id="edit-update-description" name="update_description" rows="4" cols="50"></textarea>
        </label>
        <br><br>
    `;

    detailsDiv.innerHTML = `
        <h3>Edit User Info</h3>
        <form id="edit-user-form">
            ${formInputs}
            ${descriptionInput}
            <div class="pane-footer">
                <button type="button" id="cancel-edit" class="cancel-button">Cancel Changes</button>
                <button type="button" id="update-user-info" class="update-button">Update User Info</button>
            </div>
        </form>
    `;

    // Cancel button slaps the old data back
    document.getElementById("cancel-edit").addEventListener("click", () => {
        updateUserDetails(data); // 💫 back to the original info
    });

    // Update button pretends to save your soul
    document
        .getElementById("update-user-info")
        .addEventListener("click", () => {
            const updatedData = { ...data };
            const fields = ["Email", "Name", "Gender", "Birthdate", "Address"];

            fields.forEach((key) => {
                const input = document.getElementById(`edit-${key}`);
                if (input) updatedData[key] = input.value.trim();
            });

            updatedData.update_description = document
                .getElementById("edit-update-description")
                .value.trim();

            updatedData.internship_id = data.internship_id;
            console.log("Final data to send:", updatedData);

            fetch("update_user.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams(updatedData).toString(),
            })
                .then(async (res) => {
                    const text = await res.text();
                    try {
                        const json = JSON.parse(text);
                        if (json.success) {
                            updateUserDetails(updatedData);
                            alert("User info updated!");
                            updateTable();
                        } else {
                            alert("Update failed: " + json.error);
                        }
                    } catch (e) {
                        console.error("Non-JSON response from PHP:", text);
                        alert("PHP gave a weird response (HTML error?)");
                    }
                })
                .catch((err) => {
                    console.error("Network/Fetch error:", err);
                    alert("Request failed 💀");
                });
        });
}

// checkboxes

function updateSelectAllCheckbox() {
    const allCheckboxes = Array.from(document.querySelectorAll(".select-row"));
    selectAllRowsCheckbox.checked =
        allCheckboxes.length > 0 && allCheckboxes.every((cb) => cb.checked);
}

function toggleRowSelection(row) {
    const checkbox = row.querySelector(".select-row");
    if (!checkbox) return;
    checkbox.checked = !checkbox.checked;
    row.classList.toggle("selected-row", checkbox.checked);
    checkbox.dispatchEvent(new Event("change"));
    updateSelectAllCheckbox();
    editButtons(); // Assuming this function exists elsewhere
}

document.addEventListener("click", (e) => {
    const row = e.target.closest("tr");
    if (row && e.shiftKey && !e.target.classList.contains("select-row")) {
        e.preventDefault();
        toggleRowSelection(row);
    }
});

document.addEventListener("mousedown", (e) => {
    if (e.shiftKey && e.target.closest("tr")) e.preventDefault();
});

// view user

viewUserButton?.addEventListener("click", () => {
    const demoUser = {
        email: "nyanya@meow.net",
        name: "The Neco Arc",
        batch: "Batch 99",
        school_and_program: "Academy of Nyaaing",
        internship_year: "2099",
        internship_start: "2099-01-01",
        internship_end: "2099-12-31",
        company: "Blood Inc.",
        department: "Explosions",
        job_role: "Disruptor",
        supervisor: "Cat Daddy",
        supervisor_email: "meow@cat.net",
        supervisor_contact_no: "999-999-9999",
        gender: "Trash Goblin",
        birthdate: "199X-12-31",
        address: "4th Hellring, Chaos Street",
        date_added: "2025-05-01",
        date_updated: "2025-05-17",
    };

    // Either show demo data or real selected data
    const selectedData = getSelectedRowData();
    if (selectedData) {
        updateUserDetails(selectedData);
        togglePane(true);
    } else {
        togglePane(true, demoUser);
    }
});

closeBtn?.addEventListener("click", () => togglePane(false));
backdrop?.addEventListener("click", () => togglePane(false));

// view batch

const viewBatchButton = document.getElementById("viewBatchButton");

viewBatchButton?.addEventListener("click", () => {
    const selectedData = getSelectedRowData();
    if (!selectedData || !selectedData.Batch) return;

    const heading = pane.querySelector("h2");
    if (heading) heading.textContent = "Batch Information";

    const allRows = Array.from(document.querySelectorAll("tbody tr"));

    const batchmates = allRows
        .map((row) => {
            const data = {};
            row.querySelectorAll("td[data-column]").forEach((td) => {
                data[td.getAttribute("data-column")] = td.textContent.trim();
            });
            return data;
        })
        .filter((user) => user["Batch"] === selectedData["Batch"]);

    // Detect shared values across all batchmates
    const keysToCheck = [
        "School and Program",
        "Internship Year",
        "Internship Start",
        "Internship End",
        "Company",
        "Department",
        "Supervisor",
        "Supervisor Email",
        "Supervisor Contact No",
    ];

    const commonValues = {};
    keysToCheck.forEach((key) => {
        const firstValue = batchmates[0]?.[key];
        const allSame = batchmates.every((u) => u[key] === firstValue);
        if (allSame) commonValues[key] = firstValue;
    });

    // Build info section
    const sharedInfoHtml = Object.entries(commonValues)
        .map(([key, value]) => `<p><strong>${key}:</strong> ${value}</p>`)
        .join("");

    // Build the table of batchmates
    const tableHtml = `
        <div class="table-wrapper" style="overflow-x: auto; margin-top: 1em;">
            <table class="batchmates-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>View User</th>
                    </tr>
                </thead>
                <tbody>
                    ${batchmates
                        .map(
                            (user, index) => `
                        <tr>
                            <td>${user["Name"]}</td>
                            <td>${user["Email"]}</td>
                            <td>
                                <button class="view-user-btn" data-index="${index}">View User</button>
                            </td>
                        </tr>
                    `
                        )
                        .join("")}
                </tbody>
            </table>
        </div>
    `;

    // Inject the batch info + shared info + table
    detailsDiv.innerHTML = `
        <p><strong>Batch:</strong> ${selectedData["Batch"]}</p>
        ${sharedInfoHtml}
        <h3>Batchmates:</h3>
        ${tableHtml}
        <div style="margin-top: 1em;">
            <button id="add-to-batch-btn" class="update-button">Add New User to Batch</button>
        </div>
    `;

    const buttons = detailsDiv.querySelectorAll(".view-user-btn");
    buttons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const idx = btn.getAttribute("data-index");
            const userData = batchmates[idx];
            updateUserDetails(userData);
            togglePane(true);
        });
    });

    const addToBatchBtn = detailsDiv.querySelector("#add-to-batch-btn");
    addToBatchBtn?.addEventListener("click", () => {
        showAddUserForm(selectedData["Batch"]);
    });

    togglePane(true);
});

// add user

const addUserButton = document.getElementById("addUserButton");

addUserButton.addEventListener("click", () => {
    // Show the pane with empty inputs for adding a new user
    showAddUserForm();
    backdrop.style.display = "block";
    pane.style.display = "block";
});

function showAddUserForm(batchVal = null) {
    fetch("add_user_reference.php?load=all") // 👈 change to actual PHP path
        .then((response) => response.json())
        .then((data) => {
            const batchNumber = batchVal || data.batch || "Unknown";

            detailsDiv.innerHTML = `
                <div style="max-height: 70vh; overflow-y: auto; padding-right: 10px;">
                    <h3>Add New User For INTERN1</h3>
                    <p><strong>Batch: </strong>${batchNumber}</p>

                    <button type="button" id="toggle-mode-btn" class="update-button" style="margin-bottom: 10px;">Switch to Table Mode</button>

                    <form id="add-user-form">
                        <input type="hidden" name="batch" value="${batchNumber}" />

                        <label for="add-email"><strong>Email:</strong><br>
                            <input type="email" id="add-email" name="email" required />
                        </label><br><br>

                        <label for="add-first-name"><strong>First Name:</strong><br>
                            <input type="text" id="add-first-name" name="first-name" required />
                        </label><br><br>

                        <label for="add-last-name"><strong>Last Name:</strong><br>
                            <input type="text" id="add-last-name" name="last-name" required />
                        </label><br><br>

                        <label for="add-gender"><strong>Gender:</strong><br>
                            <select id="add-gender" name="gender" required>
                                <option value="">-- Select Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </label><br><br>

                        <label for="add-birthdate"><strong>Birthdate:</strong><br>
                            <input type="date" id="add-birthdate" name="birthdate" />
                        </label><br><br>

                        <label><strong>City:</strong><br>
                            <input type="text" id="add-city" name="city" required />
                        </label><br><br>

                        <label><strong>Province/State:</strong><br>
                            <input type="text" id="add-province" name="province" required />
                        </label><br><br>

                        <label><strong>Postal Code:</strong><br>
                            <input type="text" id="add-postal" name="postal" required />
                        </label><br><br>

                        <label><strong>Country:</strong><br>
                            <input type="text" id="add-country" name="country" required />
                        </label><br><br>

                        <label for="add-internship_start"><strong>Internship Start:</strong><br>
                            <input type="date" id="add-internship_start" name="internship_start" />
                        </label><br><br>

                        <label for="add-internship_end"><strong>Internship End:</strong><br>
                            <input type="date" id="add-internship_end" name="internship_end" />
                        </label><br><br>

                        <label for="add-school"><strong>School:</strong><br>
                            <select id="add-school" name="school" required>
                                <option value="">-- Select School --</option>
                            </select>
                        </label><br><br>

                        <div id="program-container" style="display:none;">
                            <label for="add-program"><strong>Program:</strong><br>
                                <select id="add-program" name="program" required>
                                    <option value="">-- Select Program --</option>
                                </select>
                            </label><br><br>
                        </div>

                        <label for="add-company"><strong>Company:</strong><br>
                            <select id="add-company" name="company" required>
                                <option value="">-- Select Company --</option>
                                <!-- Options filled dynamically -->
                            </select>
                        </label><br><br>

                        <div id="department-container" style="display:none;">
                            <label for="add-department"><strong>Department:</strong><br>
                                <select id="add-department" name="department" required>
                                    <option value="">-- Select Department --</option>
                                </select>
                            </label><br><br>
                        </div>

                        <div id="supervisor-container" style="display:none;">
                            <label for="add-supervisor"><strong>Supervisor:</strong><br>
                                <select id="add-supervisor" name="supervisor" required>
                                    <option value="">-- Select Supervisor --</option>
                                </select>
                            </label><br><br>
                        </div>

                        <div id="supervisor-contact-info" style="display:none; margin-bottom: 20px;">
                            <strong>Supervisor Email:</strong> <span id="supervisor-email"></span><br>
                            <strong>Supervisor Contact No:</strong> <span id="supervisor-contact"></span>
                        </div>

                        <label for="add-job_role"><strong>Job Role:</strong><br>
                            <input type="text" id="add-job_role" name="job_role" />
                        </label><br><br>
                    </form>

                    <div class="pane-footer">
                        <button type="button" id="cancel-add" class="cancel-button">Cancel</button>
                        <button type="submit" id="submit-add-user" class="update-button" form="add-user-form">Add User</button>
                    </div>
                </div>
            `;

            document
                .getElementById("toggle-mode-btn")
                .addEventListener("click", () => {
                    showAddUserTableForm(batchNumber); // poof! switch forms
                });

            setupAddUserFormEvents();
            loadAllDropdowns();
        })
        .catch((error) => {
            console.error(
                "Nyaaaa~ Failed to fetch batch! (╯°□°）╯︵ ┻━┻",
                error
            );
        });
}

// GLOBAL CHAOS DATA STORES!! (make sure these are at the top of your script)
let allSchools = [],
    allPrograms = [];
let allCompanies = [],
    allDepartments = [],
    allSupervisors = [],
    allUsers = [];

let programsBySchool = {};
let departmentsByCompany = {};
let supervisorsByDepartment = {};
let usersById = {};

function loadAllDropdowns() {
    fetch("add_user_reference.php?load=all")
        .then((res) => res.json())
        .then((data) => {
            // Store all data globally for later chaos
            allSchools = data.schools;
            allPrograms = data.programs;
            allCompanies = data.companies;
            allDepartments = data.departments;
            allSupervisors = data.supervisors;
            allUsers = data.users;

            // Map programs by school
            programsBySchool = {};
            allPrograms.forEach((p) => {
                if (!programsBySchool[p.school_id])
                    programsBySchool[p.school_id] = [];
                programsBySchool[p.school_id].push(p);
            });

            // Map departments by company
            departmentsByCompany = {};
            allDepartments.forEach((d) => {
                if (!departmentsByCompany[d.company_id])
                    departmentsByCompany[d.company_id] = [];
                departmentsByCompany[d.company_id].push(d);
            });

            // Map supervisors by department
            supervisorsByDepartment = {};
            allSupervisors.forEach((sup) => {
                if (!supervisorsByDepartment[sup.department_id])
                    supervisorsByDepartment[sup.department_id] = [];
                supervisorsByDepartment[sup.department_id].push(sup);
            });

            // Map users by ID
            usersById = {};
            allUsers.forEach((u) => {
                usersById[u.user_id] = u;
            });

            // === Populate the form on the LEFT ===

            const schoolSelect = document.getElementById("add-school");
            const programSelect = document.getElementById("add-program");
            const programContainer =
                document.getElementById("program-container");

            const companySelect = document.getElementById("add-company");
            const departmentSelect = document.getElementById("add-department");
            const departmentContainer = document.getElementById(
                "department-container"
            );

            const supervisorSelect = document.getElementById("add-supervisor");
            const supervisorContainer = document.getElementById(
                "supervisor-container"
            );
            const supervisorContactInfo = document.getElementById(
                "supervisor-contact-info"
            );
            const supervisorEmailSpan =
                document.getElementById("supervisor-email");
            const supervisorContactSpan =
                document.getElementById("supervisor-contact");

            // Populate schools
            populateOptions(
                schoolSelect,
                allSchools,
                "school_id",
                "school_name"
            );

            // Populate companies
            populateOptions(
                companySelect,
                allCompanies,
                "company_id",
                "company_name"
            );

            // Event: When school changes, show programs
            schoolSelect.addEventListener("change", () => {
                const schoolId = schoolSelect.value;
                const progs = programsBySchool[schoolId] || [];
                if (progs.length > 0) {
                    programContainer.style.display = "block";
                    populateOptions(
                        programSelect,
                        progs,
                        "program_id",
                        "program_name"
                    );
                } else {
                    programContainer.style.display = "none";
                    programSelect.innerHTML =
                        '<option value="">-- Select Program --</option>';
                }
            });

            // Event: When company changes, show departments
            companySelect.addEventListener("change", () => {
                const companyId = companySelect.value;
                const depts = departmentsByCompany[companyId] || [];
                if (depts.length > 0) {
                    departmentContainer.style.display = "block";
                    populateOptions(
                        departmentSelect,
                        depts,
                        "department_id",
                        "department_name"
                    );
                } else {
                    departmentContainer.style.display = "none";
                    departmentSelect.innerHTML =
                        '<option value="">-- Select Department --</option>';
                }
                // Hide supervisor until department is picked
                supervisorContainer.style.display = "none";
                clearSupervisor();
            });

            // Event: When department changes, show supervisors
            departmentSelect.addEventListener("change", () => {
                const deptId = departmentSelect.value;
                const sups = supervisorsByDepartment[deptId] || [];
                if (sups.length > 0) {
                    supervisorContainer.style.display = "block";
                    populateOptions(
                        supervisorSelect,
                        sups,
                        "supervisor_id",
                        (sup) => {
                            return (
                                usersById[sup.user_id]?.user_name || "Unknown"
                            );
                        }
                    );
                } else {
                    supervisorContainer.style.display = "none";
                    clearSupervisor();
                }
                supervisorContactInfo.style.display = "none";
            });

            // Event: When supervisor selected, show contact info
            supervisorSelect.addEventListener("change", () => {
                const supId = supervisorSelect.value;
                if (!supId) {
                    supervisorContactInfo.style.display = "none";
                    return;
                }
                const sup = allSupervisors.find(
                    (s) => s.supervisor_id == supId
                );
                supervisorEmailSpan.textContent = sup?.user_email || "N/A";
                supervisorContactSpan.textContent =
                    sup?.supervisor_contact_no || "N/A";
                supervisorContactInfo.style.display = "block";
            });

            function clearSupervisor() {
                supervisorSelect.innerHTML =
                    '<option value="">-- Select Supervisor --</option>';
                supervisorContactInfo.style.display = "none";
                supervisorEmailSpan.textContent = "";
                supervisorContactSpan.textContent = "";
            }
        })
        .catch((err) => console.error("Loading error", err));
}

// Shared function to populate dropdowns
function populateOptions(select, data, valueKey, textKey) {
    select.innerHTML = '<option value="">-- Select --</option>';
    data.forEach((item) => {
        const option = document.createElement("option");
        option.value = item[valueKey];
        option.textContent =
            typeof textKey === "function" ? textKey(item) : item[textKey];
        select.appendChild(option);
    });
}

function setupAddUserFormEvents() {
    document.getElementById("cancel-add").addEventListener("click", () => {
        togglePane(false);
    });

    document
        .getElementById("add-user-form")
        .addEventListener("submit", handleAddUserFormSubmit);
}

function handleAddUserFormSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    console.log(formData);

    fetch("add_user_reference.php", {
        method: "POST",
        body: formData,
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                alert("User added successfully!");
                togglePane(false); // or reload something if needed
                updateTable();
            } else {
                alert(
                    "Failed to add user: " + (data.message || "Unknown error")
                );
            }
        })
        .catch((err) => {
            console.error("Submit error:", err);
            alert("Something exploded! Try again later.");
        });
}

function showAddUserTableForm(batchVal) {
    detailsDiv.innerHTML = `
        <div style="max-height: 70vh; overflow-y: auto; padding-right: 10px;">
            <h3>Batch Add Users - INTERN1</h3>
            <p><strong>Batch:</strong> ${batchVal}</p>

            <button type="button" id="toggle-back-btn" class="cancel-button" style="margin-bottom: 10px;">Switch to Form Mode</button>

            <div style="overflow-y: auto;">
                <table id="user-add-table" border="1" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Postal</th>
                            <th>Country</th>
                            <th>Internship Start</th>
                            <th>Internship End</th>
                            <th>School</th>
                            <th>Program</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Supervisor</th>
                            <th>Job Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-add-tbody">
                        ${generateUserRow()}
                    </tbody>
                </table>
            </div>

            <button type="button" id="add-row-btn" class="update-button" style="margin-top: 10px;">➕ Add Row</button>

            <div class="pane-footer">
                <button type="button" id="cancel-add" class="cancel-button">Cancel</button>
                <button type="button" id="submit-multi-user" class="update-button">Submit All Users</button>
            </div>
        </div>
    `;
    document.getElementById("toggle-back-btn").addEventListener("click", () => {
        showAddUserForm(batchVal);
    });

    document.getElementById("add-row-btn").addEventListener("click", () => {
        const tbody = document.getElementById("user-add-tbody");
        tbody.insertAdjacentHTML("beforeend", generateUserRow());

        const lastRow = tbody.lastElementChild;

        const schoolSelect = lastRow.querySelector(".school-select");
        const programSelect = lastRow.querySelector(".program-select");
        const companySelect = lastRow.querySelector(".company-select");
        const departmentSelect = lastRow.querySelector(".department-select");
        const supervisorSelect = lastRow.querySelector(".supervisor-select");

        schoolSelect.addEventListener("change", () => {
            const schoolId = schoolSelect.value;
            const programs = programsBySchool[schoolId] || [];
            programSelect.innerHTML =
                `<option value="">-- Select Program --</option>` +
                programs
                    .map(
                        (p) =>
                            `<option value="${p.program_id}">${p.program_name}</option>`
                    )
                    .join("");
        });

        companySelect.addEventListener("change", () => {
            const companyId = companySelect.value;
            const departments = departmentsByCompany[companyId] || [];
            departmentSelect.innerHTML =
                `<option value="">-- Select Department --</option>` +
                departments
                    .map(
                        (d) =>
                            `<option value="${d.department_id}">${d.department_name}</option>`
                    )
                    .join("");

            supervisorSelect.innerHTML = `<option value="">-- Select Supervisor --</option>`; // Reset downstream
        });

        departmentSelect.addEventListener("change", () => {
            const deptId = departmentSelect.value;
            const supervisors = supervisorsByDepartment[deptId] || [];
            supervisorSelect.innerHTML =
                `<option value="">-- Select Supervisor --</option>` +
                supervisors
                    .map((s) => {
                        const user = usersById[s.user_id];
                        return `<option value="${s.supervisor_id}">${
                            user ? user.user_name : "Unknown"
                        }</option>`;
                    })
                    .join("");
        });

        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-row-btn")) {
                e.target.closest("tr").remove();
            }
        });
    });

    document
        .getElementById("submit-multi-user")
        .addEventListener("click", () => {
            submitMultipleUsers(batchVal);
        });
}

function generateUserRow() {
    const schoolOptions = allSchools
        .map((s) => `<option value="${s.school_id}">${s.school_name}</option>`)
        .join("");
    const companyOptions = allCompanies
        .map(
            (c) => `<option value="${c.company_id}">${c.company_name}</option>`
        )
        .join("");

    const genderOptions = `
        <option value="">-- Select --</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    `;

    const rowId = `row-${Math.random().toString(36).substr(2, 9)}`; // unique ID for event hook-up later

    return `
        <tr id="${rowId}">
            <td><input type="email" name="email[]" required></td>
            <td><input type="text" name="first_name[]" required></td>
            <td><input type="text" name="last_name[]" required></td>
            <td>
                <select name="gender[]">${genderOptions}</select>
            </td>
            <td><input type="date" name="birthdate[]" required></td>
            <td><input type="text" name="city[]" required></td>
            <td><input type="text" name="province[]" required></td>
            <td><input type="text" name="postal[]" required></td>
            <td><input type="text" name="country[]" required></td>
            <td><input type="date" name="start_date[]" required></td>
            <td><input type="date" name="end_date[]" required></td>
            <td>
                <select name="school[]" class="school-select">${schoolOptions}</select>
            </td>
            <td>
                <select name="program[]" class="program-select">
                    <option value="">-- Select Program --</option>
                </select>
            </td>
            <td>
                <select name="company[]" class="company-select">${companyOptions}</select>
            </td>
            <td>
                <select name="department[]" class="department-select">
                    <option value="">-- Select Department --</option>
                </select>
            </td>
            <td>
                <select name="supervisor[]" class="supervisor-select">
                    <option value="">-- Select Supervisor --</option>
                </select>
            </td>
            <td><input type="text" name="job_role[]" required></td>
            <td><button type="button" class="remove-row-btn">❌</button></td>
        </tr>
    `;
}

function submitMultipleUsers(batchVal) {
    const table = document.getElementById("user-add-table");
    const formData = new FormData();

    formData.append("batch", batchVal);

    const rows = table.querySelectorAll("tbody tr");
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll("input, select");
        inputs.forEach((input) => {
            formData.append(`${input.name}`, input.value);
        });
    });

    // replace this with actual POST to your backend
    fetch("submit_multiple_users.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.text())
        .then((result) => {
            alert("BATCH USERS ADDED~ BURENYUUU~ 💌");
            // maybe reload list or go back to view
        })
        .catch((error) => {
            console.error("✨ EVERYTHING’S ON FIRE ✨", error);
        });
}
