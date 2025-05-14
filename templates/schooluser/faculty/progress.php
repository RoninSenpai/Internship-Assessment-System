<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Progress Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    rel="stylesheet"
  />
  <style>
    /* Custom scrollbar for notifications */
    .scrollbar-thin::-webkit-scrollbar {
      width: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
      background-color: #cbd5e1;
      border-radius: 3px;
    }
  </style>
</head>
<body class="bg-[#f5f6f8] text-[13px] font-sans text-[#1a1a1a]">
  <div class="flex justify-center p-4 w-full min-h-screen">
    <div class="max-w-[1200px] w-full space-y-6 flex flex-col relative">
      <!-- Top container with progress dashboard and notifications -->
      <div class="flex flex-col lg:flex-row gap-6 flex-grow">
        <!-- Progress Dashboard -->
        <div
          class="bg-white rounded-xl p-6 flex-1 flex flex-col md:flex-row gap-6 shadow-sm min-w-0 relative"
        >
          <!-- Left images -->
          <div class="flex flex-col gap-6 min-w-[120px]">
            <div
              class="rounded-lg border border-[#e2e8f0] p-2 w-[120px] h-[120px] flex items-center justify-center"
            >
              <img
                alt="Pie chart showing year 2024-2025 internship distribution with orange, yellow, and gray segments"
                class="rounded-md"
                height="100"
                src="https://storage.googleapis.com/a1aa/image/ed5c1a52-86d2-4625-2939-552e9e93f580.jpg"
                width="100"
              />
            </div>
            <div
              class="rounded-lg border border-[#e2e8f0] p-2 w-[120px] h-[120px] flex items-center justify-center"
            >
              <img
                alt="Pie chart titled Interns Per School with green and orange segments on black background"
                class="rounded-md"
                height="100"
                src="https://storage.googleapis.com/a1aa/image/1ef2cc22-457c-4ae0-ad06-d9b30789ae6b.jpg"
                width="100"
              />
            </div>
          </div>
          <!-- Center and right content -->
          <div
            class="flex-1 flex flex-col md:flex-row md:items-center md:justify-between gap-6 min-w-0"
          >
            <div class="flex-1 min-w-0">
              <h2 class="text-[#0c2e6b] font-semibold text-[14px] mb-1">
                PROGRESS DASHBOARD
              </h2>
              <div id="monthNavigator" class="flex items-center text-[#0c2e6b] text-[14px] mb-2 select-none">
                <span id="prevMonth" class="cursor-pointer select-none">&lt;</span>
                <span id="currentMonth" class="mx-2 font-normal select-none">March 2025</span>
                <span id="nextMonth" class="cursor-pointer select-none">&gt;</span>
              </div>
              <h3 id="internCount" class="text-[#0c2e6b] font-bold text-[18px] mb-4">130 Interns</h3>
              <ul id="schoolList" class="space-y-2 text-[12px] max-w-[350px] truncate">
                <!-- Dynamic school list items will be inserted here -->
              </ul>
            </div>
            <!-- Right donut chart and edit info -->
            <div class="flex flex-col items-end space-y-4 min-w-[120px] relative">
              <button
                id="editInfoBtn"
                class="flex items-center text-[#0c2e6b] text-[13px] font-normal space-x-1 hover:underline z-10"
                type="button"
              >
                <i class="fas fa-edit text-[14px]"></i>
                <span>Edit Info</span>
              </button>
              <img
                id="donutChart"
                alt="Donut chart with blue gradient segments labeled with percentages 20%, 15%, 21%, 13%, 20%"
                class="rounded-full"
                height="100"
                src="https://storage.googleapis.com/a1aa/image/126bf857-30ac-4cff-d06f-dcb1ea303fff.jpg"
                width="100"
              />
              <!-- Edit Info Card -->
              <div id="editInfoCard" class="hidden absolute top-12 right-0 bg-white rounded-xl shadow-lg p-6 w-[320px] z-20">
                <h3 class="text-[#0c2e6b] font-semibold mb-4 text-[16px]">Edit Info</h3>
                <form id="editInfoForm" class="space-y-4">
                  <div>
                    <label for="yearSelect" class="block text-[13px] font-semibold mb-1 text-[#0c2e6b]">Year</label>
                    <select id="yearSelect" class="w-full border border-[#e2e8f0] rounded px-2 py-1 text-[13px]">
                      <option value="2024">2024</option>
                      <option value="2025" selected>2025</option>
                      <option value="2026">2026</option>
                      <option value="2027">2027</option>
                    </select>
                  </div>
                  <div>
                    <label for="internSelect" class="block text-[13px] font-semibold mb-1 text-[#0c2e6b]">Intern</label>
                    <select id="internSelect" class="w-full border border-[#e2e8f0] rounded px-2 py-1 text-[13px]">
                      <option value="Intern1" selected>Intern 1</option>
                      <option value="Intern2">Intern 2</option>
                    </select>
                  </div>
                  <div>
                    <label for="termSelect" class="block text-[13px] font-semibold mb-1 text-[#0c2e6b]">Term</label>
                    <select id="termSelect" class="w-full border border-[#e2e8f0] rounded px-2 py-1 text-[13px]">
                      <option value="Term1" selected>Term 1</option>
                      <option value="Term2">Term 2</option>
                      <option value="Term3">Term 3</option>
                    </select>
                  </div>
                  <div>
                    <label for="schoolSelect" class="block text-[13px] font-semibold mb-1 text-[#0c2e6b]">School</label>
                    <select id="schoolSelect" class="w-full border border-[#e2e8f0] rounded px-2 py-1 text-[13px]">
                      <option value="Management" selected>School of Management</option>
                      <option value="Computing">School of Computing and Information Technologies</option>
                      <option value="Multimedia">School of Multimedia and Arts</option>
                      <option value="Architecture">School of Architecture</option>
                      <option value="Engineering">School of Engineering</option>
                    </select>
                  </div>
                  <div>
                    <label for="programInput" class="block text-[13px] font-semibold mb-1 text-[#0c2e6b]">Program</label>
                    <input id="programInput" type="text" placeholder="Enter program" class="w-full border border-[#e2e8f0] rounded px-2 py-1 text-[13px]" />
                  </div>
                  <div class="flex justify-between mt-6">
                    <button type="button" id="backEditBtn" class="border border-gray-400 rounded px-4 py-1 text-[13px] hover:bg-gray-100">Back</button>
                    <button type="submit" class="bg-[#0c2e6b] text-white rounded px-4 py-1 text-[13px] hover:bg-[#0a2654]">Proceed</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Notifications -->
        <div
          class="bg-white rounded-xl p-4 w-full max-w-[320px] flex flex-col shadow-sm"
        >
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-[14px] font-semibold flex items-center gap-1">
              <span>›</span>
              <span>Notifications</span>
              <i class="fas fa-bell"></i>
            </h3>
            <button
              aria-label="Refresh notifications"
              class="text-[#0c2e6b] hover:text-[#0a2654]"
              type="button"
              onclick="location.reload()"
            >
              <i class="fas fa-sync-alt"></i>
            </button>
          </div>
          <a
            id="setEventBtn"
            class="text-[#0c2e6b] text-[13px] flex items-center gap-1 mb-3 hover:underline cursor-pointer"
            ><i class="fas fa-bell"></i> Set Event</a
          >
          <!-- Hidden form to add event -->
          <form
            id="eventForm"
            class="mb-3 hidden"
            onsubmit="return addEvent(event)"
          >
            <label class="block mb-1 text-[12px] font-semibold text-[#0c2e6b]"
              >Event Date & Time</label
            >
            <input
              type="datetime-local"
              id="eventDateTime"
              required
              class="w-full border border-[#e2e8f0] rounded px-2 py-1 mb-2 text-[12px]"
            />
            <label class="block mb-1 text-[12px] font-semibold text-[#0c2e6b]"
              >Event Description</label
            >
            <textarea
              id="eventDescription"
              rows="2"
              required
              class="w-full border border-[#e2e8f0] rounded px-2 py-1 mb-2 text-[12px] resize-none"
              placeholder="Enter event description"
            ></textarea>
            <div class="flex justify-end gap-2">
              <button
                type="button"
                class="border border-gray-400 rounded px-3 py-1 text-[12px] hover:bg-gray-100"
                onclick="toggleEventForm(false)"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="bg-[#0c2e6b] text-white rounded px-3 py-1 text-[12px] hover:bg-[#0a2654]"
              >
                Add
              </button>
            </div>
          </form>
          <div class="text-[11px] text-[#6b7280] mb-2">Sort by ↓</div>
          <div
            id="notificationsList"
            class="space-y-3 overflow-y-auto scrollbar-thin max-h-[180px]"
          >
            <div
              
            >
            </div>
            <div
              
            >
            </div>
          </div>
        </div>
      </div>
      <!-- Middle container with intern status -->
      <div
        class="bg-white rounded-xl p-4 flex flex-col md:flex-row items-center justify-between shadow-sm"
      >
        <div class="flex items-center gap-2 text-[14px] font-semibold text-[#0c2e6b]">
          <span>INTERN</span>
          <span id="internNumber" class="font-normal">1</span>
          <span id="internTermRange" class="text-[11px] font-normal text-[#6b7280]"
            >December - March 2025</span
          >
          <i class="fas fa-chevron-up text-[11px] cursor-pointer"></i>
        </div>
        <div
          id="internStatus"
          class="flex flex-wrap items-center gap-6 text-[14px] font-semibold select-none"
        >
          <!-- Status items dynamically updated -->
        </div>
        <div class="flex gap-3 mt-4 md:mt-0">
          <button
            class="border border-[#6b7280] rounded-full px-4 py-1 text-[12px] text-[#6b7280] hover:bg-[#f3f4f6]"
            type="button"
          >
            Tag Intern
          </button>
          <button
            class="border border-[#0c2e6b] rounded-full px-4 py-1 text-[12px] text-[#0c2e6b] hover:bg-[#e0e7ff]"
            type="button"
          >
            Go to Masterlist
          </button>
        </div>
      </div>
      <!-- Bottom container with terms -->
      <div
        class="bg-white rounded-xl p-4 max-w-[900px] shadow-sm text-[13px] text-[#1a1a1a]"
      >
        <div
          class="flex items-center gap-1 mb-3 select-none cursor-pointer"
        >
          <span>2025, INTERN</span>
          <span class="font-semibold">1</span>
          <i class="fas fa-chevron-down text-[11px]"></i>
        </div>
        <div class="flex gap-6 font-semibold">
          <div>
            <span>INTERNS TERM1:</span>
            <span class="text-[#3b82f6] cursor-pointer hover:underline">54</span>
          </div>
          <div>
            <span>INTERNS TERM2:</span>
            <span class="text-[#f97316] cursor-pointer hover:underline">0</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Data for terms and months
    // Each term is 4 months, starting from March 2025 as term 1
    // We'll create a list of terms with their start month/year and data
    const termsData = [
      {
        termLabel: "Term 1",
        startDate: new Date(2025, 2, 1), // March 2025 (month 2 zero-based)
        internCount: 130,
        schools: [
          { name: "School of Management", color: "#f9b5b5", count: 3 },
          {
            name: "School of Computing and Information Technologies",
            color: "#3b82f6",
            count: 12,
          },
          { name: "School of Multimedia and Arts", color: "#34d399", count: 21 },
          { name: "School of Architecture", color: "#f9b5b5", count: 25 },
          { name: "School of Engineering", color: "#f97316", count: 90 },
        ],
        internStatus: [
          { label: "Deployed", color: "#2dd4bf", count: 10 },
          { label: "Interview/Waiting", color: "#3b82f6", count: 13 },
          { label: "Undeployed", color: "#f9b5b5", count: 3 },
          { label: "Unlisted", color: "#9ca3af", count: 31 },
        ],
        internNumber: 1,
        internTermRange: "December - March 2025",
      },
      {
        termLabel: "Term 2",
        startDate: new Date(2025, 6, 1), // July 2025
        internCount: 140,
        schools: [
          { name: "School of Management", color: "#f9b5b5", count: 5 },
          {
            name: "School of Computing and Information Technologies",
            color: "#3b82f6",
            count: 15,
          },
          { name: "School of Multimedia and Arts", color: "#34d399", count: 25 },
          { name: "School of Architecture", color: "#f9b5b5", count: 30 },
          { name: "School of Engineering", color: "#f97316", count: 65 },
        ],
        internStatus: [
          { label: "Deployed", color: "#2dd4bf", count: 15 },
          { label: "Interview/Waiting", color: "#3b82f6", count: 10 },
          { label: "Undeployed", color: "#f9b5b5", count: 5 },
          { label: "Unlisted", color: "#9ca3af", count: 20 },
        ],
        internNumber: 2,
        internTermRange: "April - July 2025",
      },
      {
        termLabel: "Term 3",
        startDate: new Date(2025, 10, 1), // November 2025
        internCount: 120,
        schools: [
          { name: "School of Management", color: "#f9b5b5", count: 4 },
          {
            name: "School of Computing and Information Technologies",
            color: "#3b82f6",
            count: 10,
          },
          { name: "School of Multimedia and Arts", color: "#34d399", count: 20 },
          { name: "School of Architecture", color: "#f9b5b5", count: 22 },
          { name: "School of Engineering", color: "#f97316", count: 64 },
        ],
        internStatus: [
          { label: "Deployed", color: "#2dd4bf", count: 12 },
          { label: "Interview/Waiting", color: "#3b82f6", count: 8 },
          { label: "Undeployed", color: "#f9b5b5", count: 4 },
          { label: "Unlisted", color: "#9ca3af", count: 25 },
        ],
        internNumber: 3,
        internTermRange: "August - November 2025",
      },
    ];

    // Elements
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    const currentMonthSpan = document.getElementById("currentMonth");
    const internCountEl = document.getElementById("internCount");
    const schoolListEl = document.getElementById("schoolList");
    const internNumberEl = document.getElementById("internNumber");
    const internTermRangeEl = document.getElementById("internTermRange");
    const internStatusEl = document.getElementById("internStatus");

    const editInfoBtn = document.getElementById("editInfoBtn");
    const editInfoCard = document.getElementById("editInfoCard");
    const editInfoForm = document.getElementById("editInfoForm");
    const yearSelect = document.getElementById("yearSelect");
    const internSelect = document.getElementById("internSelect");
    const termSelect = document.getElementById("termSelect");
    const schoolSelect = document.getElementById("schoolSelect");
    const programInput = document.getElementById("programInput");
    const backEditBtn = document.getElementById("backEditBtn");

    // Current term index
    let currentTermIndex = 0;

    // Format month year string like "March 2025"
    function formatMonthYear(date) {
      const options = { year: "numeric", month: "long" };
      return date.toLocaleDateString("en-US", options);
    }

    // Update UI for current term
    function updateTermUI() {
      const term = termsData[currentTermIndex];
      currentMonthSpan.textContent = formatMonthYear(term.startDate);
      internCountEl.textContent = `${term.internCount} Interns`;

      // Update school list
      schoolListEl.innerHTML = "";
      term.schools.forEach((school) => {
        const li = document.createElement("li");
        li.className = "flex items-center gap-2";
        li.innerHTML = `
          <div class="w-3 h-3 rounded-full" style="background-color: ${school.color}"></div>
          <span class="flex-1 truncate">${school.name}</span>
          <span class="bg-[#0c2e6b] text-white text-[12px] font-semibold rounded px-2 py-[2px]">${school.count}</span>
        `;
        schoolListEl.appendChild(li);
      });

      // Update intern status
      internStatusEl.innerHTML = "";
      term.internStatus.forEach((status) => {
        const div = document.createElement("div");
        div.className = "flex items-center gap-1";
        div.style.color = status.color;
        div.innerHTML = `
          <span class="w-3 h-3 rounded-full block" style="background-color: ${status.color}"></span>
          <span class="font-normal text-[11px]">${status.label}</span>
          <span class="text-[24px] leading-none">${status.count}</span>
        `;
        internStatusEl.appendChild(div);
      });

      // Update intern number and term range
      internNumberEl.textContent = term.internNumber;
      internTermRangeEl.textContent = term.internTermRange;
    }

    // Event listeners for prev/next buttons
    prevBtn.addEventListener("click", () => {
      if (currentTermIndex > 0) {
        currentTermIndex--;
        updateTermUI();
      }
    });

    nextBtn.addEventListener("click", () => {
      if (currentTermIndex < termsData.length - 1) {
        currentTermIndex++;
        updateTermUI();
      }
    });

    // Initialize UI on page load
    updateTermUI();

    // Existing event form code (from previous)
    const setEventBtn = document.getElementById("setEventBtn");
    const eventForm = document.getElementById("eventForm");
    const notificationsList = document.getElementById("notificationsList");

    setEventBtn.addEventListener("click", () => {
      toggleEventForm(true);
    });

    function toggleEventForm(show) {
      if (show) {
        eventForm.classList.remove("hidden");
        setEventBtn.classList.add("hidden");
      } else {
        eventForm.classList.add("hidden");
        setEventBtn.classList.remove("hidden");
        // Clear form inputs
        eventForm.reset();
      }
    }

    function addEvent(e) {
      e.preventDefault();
      const dateTimeInput = document.getElementById("eventDateTime");
      const descInput = document.getElementById("eventDescription");

      const dateTimeValue = dateTimeInput.value;
      const descValue = descInput.value.trim();

      if (!dateTimeValue || !descValue) return false;

      // Format date/time nicely
      const dateObj = new Date(dateTimeValue);
      const formattedDate = `${dateObj.getMonth() + 1}/${dateObj.getDate()}/${dateObj.getFullYear()} ${dateObj.getHours().toString().padStart(2, "0")}:${dateObj.getMinutes().toString().padStart(2, "0")}`;

      const newNotification = document.createElement("div");
      newNotification.className = "border border-[#e2e8f0] rounded-md p-2 text-[12px] leading-tight flex justify-between items-start";
      newNotification.innerHTML = `<div><div class="text-[#6b7280] mb-1">${formattedDate}</div>${descValue}</div><button aria-label="Remove notification" class="removeNotificationBtn text-[#0c2e6b] hover:text-[#0a2654] text-[14px] font-semibold ml-2" type="button">&times;</button>`;

      // Add new notification to top of list
      notificationsList.prepend(newNotification);

      // Attach remove event to new button
      newNotification.querySelector(".removeNotificationBtn").addEventListener("click", () => {
        newNotification.remove();
      });

      // Hide form and show button again
      toggleEventForm(false);

      return false;
    }

    // Edit Info button toggle
    editInfoBtn.addEventListener("click", () => {
      editInfoCard.classList.remove("hidden");
    });

    // Back button hides the edit info card
    backEditBtn.addEventListener("click", () => {
      editInfoCard.classList.add("hidden");
      // Optionally clear form inputs
      editInfoForm.reset();
    });

    // Proceed button submits form (for demo, just alert and close)
    editInfoForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const year = yearSelect.value;
      const intern = internSelect.value;
      const term = termSelect.value;
      const school = schoolSelect.value;
      const program = programInput.value.trim();

      if (!program) {
        alert("Please enter a program.");
        return;
      }

      alert(
        `Submitted:\nYear: ${year}\nIntern: ${intern}\nTerm: ${term}\nSchool: ${school}\nProgram: ${program}`
      );

      editInfoCard.classList.add("hidden");
      editInfoForm.reset();
    });

      // Remove individual notification buttons
      function attachRemoveListeners() {
      document.querySelectorAll(".removeNotificationBtn").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const notification = e.target.closest("div.border");
          if (notification) notification.remove();
        });
      });
    }
    attachRemoveListeners();

    // Remove all notifications button
    removeAllBtn.addEventListener("click", () => {
      notificationsList.innerHTML = "";
    });

    // Close notifications panel button
    closeNotificationsBtn.addEventListener("click", () => {
      notificationsPanel.remove();
    });
  </script>
</body>
</html>