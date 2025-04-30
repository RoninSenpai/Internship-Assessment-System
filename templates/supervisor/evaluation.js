const form = document.getElementById("evaluationForm");
const inputs = form.querySelectorAll("input, textarea, select");

if (typeof dataStatusFromPHP !== 'undefined' && dataStatusFromPHP === 'done') {
    const inputs = document.querySelectorAll('input, textarea, select'); // whatever your fields are

    inputs.forEach(input => {
        input.disabled = true;
        input.style.cursor = "default";

        // Nuke all parent label + span elements too
        const label = input.closest('label');
        if (label) label.style.cursor = "default";

        const span = label?.querySelector('span');
        if (span) span.style.cursor = "default";

        if (input.title) input.removeAttribute("title");
    });

    // console.log("Inputs have been disabled because status = DONE, loser.");
}

document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelector('.button-panel');
    const buttons2 = document.querySelector('.button-panel2');

    if (typeof dataStatusFromPHP !== 'undefined' && dataStatusFromPHP === 'done') {
        // If data-status = done
        buttons2.style.display = 'flex';  // Show cancel
        buttons.style.display = 'none';             // Hide save
    } else {
        // If not done (aka still editable)
        buttons2.style.display = 'none';           // Hide cancel
        buttons.style.display = 'flex';     // Show save
    }
});

// Auto-expand textareas and add character count
document.querySelectorAll('.textarea-expand').forEach(function (textarea) {
    const maxLength = textarea.getAttribute('maxlength');

    // Create char count element
    const charCount = document.createElement('p');
    charCount.classList.add('char-count');
    charCount.textContent = `${maxLength} characters remaining`;
    textarea.insertAdjacentElement('afterend', charCount);

    textarea.addEventListener('input', function () {
        // Auto resize
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';

        // Update character count
        const remaining = maxLength - this.value.length;
        charCount.textContent = `${remaining} characters remaining`;
    });
});

// Update table counts and subtotal
function updateTableCountsAndSubtotal() {
    const values = ['5', '4', '3', '2', '1', '0', 'N/A'];
    const weights = {
        '5': 100,
        '4': 90,
        '3': 80,
        '2': 75,
        '1': 65,
        '0': 0,
        'N/A': 0
    };

    const counts = {
        '5': 0,
        '4': 0,
        '3': 0,
        '2': 0,
        '1': 0,
        '0': 0,
        'N/A': 0
    };

    let subtotal = 0;
    let criteriaUsed = 0;

    const checkedRadios = document.querySelectorAll('input[type="radio"]:checked');

    // Count selections
    checkedRadios.forEach(radio => {
        const val = radio.value;
        if (counts.hasOwnProperty(val)) {
            counts[val]++;
        }
    });

    // Loop through each value, update count cells, subtotal cells
    for (const val of values) {
        const count = counts[val];
        const weight = weights[val];
        const countId = val === 'N/A' ? 'count-na' : `count-${val}`;
        const subtotalId = val === 'N/A' ? 'subtotal-na' : `subtotal-${val}`;

        const countCell = document.getElementById(countId);
        const subtotalCell = document.getElementById(subtotalId);

        // Update count
        const countSpan = countCell.querySelector('span') || countCell;
        countSpan.textContent = count;

        // Update subtotal per score
        const scoreSubtotal = count * weight;
        const subtotalSpan = subtotalCell?.querySelector('span') || subtotalCell;
        if (subtotalSpan) {
            subtotalSpan.textContent = scoreSubtotal;
        }

        // Update styles
        if (count > 0) {
            countCell.classList.add('active');
            subtotalCell?.classList.add('active');
        } else {
            countCell.classList.remove('active');
            subtotalCell?.classList.remove('active');
        }

        countCell.classList.add('point-count');
        subtotalCell?.classList.add('point-count');

        // Only include in overall subtotal if it's not N/A
        if (val !== 'N/A') {
            subtotal += scoreSubtotal;
            criteriaUsed += count;
        }
    }

    // Loop through each subtotal cell and divide it by criteria used (to normalize each subtotal)
    const subtotalCells = document.querySelectorAll('.subtotal-cell');
    subtotalCells.forEach(cell => {
        const span = cell.querySelector('span') || cell;
        const scoreId = cell.id.split('-')[1]; // Get the score (5, 4, etc.)

        const count = counts[scoreId];
        const weight = weights[scoreId];

        if (criteriaUsed > 0 && count > 0) {
            // Calculate the subtotal per score
            let subtotalForScore = count * weight;

            // Normalize by dividing by the number of criteria used
            let normalizedScore = subtotalForScore / criteriaUsed;

            // Prevent the normalized score from exceeding 100 (if all questions are rated 5)
            if (normalizedScore > 100) normalizedScore = 100;

            span.textContent = Math.round(normalizedScore);
        } else {
            span.textContent = 0; // If no criteria used, set 0
        }
    });

    // Update average score (global)
    const averageCell = document.getElementById('average-score');
    if (averageCell) {
        const average = criteriaUsed > 0 ? Math.round(subtotal / criteriaUsed) : 0;
        const avgSpan = averageCell.querySelector('span') || averageCell;
        avgSpan.textContent = average;

        if (average > 0) averageCell.classList.add('active');

        const apcGradeCell = document.getElementById('apc-grade');

        // console.log("apcGradeCell ", apcGradeCell);
        if (apcGradeCell) {
            let apcGrade = '';

            // UwU put your grading logic here nya~
            if (average >= 95) apcGrade = '4.00';
            else if (average >= 91) apcGrade = '3.50';
            else if (average >= 87) apcGrade = '3.00';
            else if (average >= 83) apcGrade = '2.50';
            else if (average >= 79) apcGrade = '2.00';
            else if (average >= 75) apcGrade = '2.50';
            else if (average >= 70) apcGrade = '1.00';
            else if (average > 0) apcGrade = 'R';
            else apcGrade = '0.0';

            apcGradeCell.textContent = apcGrade;
        }
    }
}

// Register update on all radio button changes
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', updateTableCountsAndSubtotal);
});

window.addEventListener('load', function () {
    updateTableCountsAndSubtotal(); // Run when DOM's ready, unlike you—who’s *never* ready ✧(>o<)ﾉ✧
});

// Form submission with fetch (NO page reload)
if (document.getElementById("myForm")) {
    document.getElementById("myForm").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData(this);
    let data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    // console.log("Sending data:", data);

    fetch("/submit", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    // .then(data => console.log("Server says:", data));
})};

// Optional confirm function
function confirmAction(event) {
    let userResponse = confirm("Are you sure you want to proceed? 😔");
    if (!userResponse) {
        event.preventDefault();
    }
}

document.querySelectorAll('#evaluationForm button').forEach(button => {
  button.addEventListener('click', function(event) {
    clickedButton = event.target; // Save the button that was clicked
  });
});

let checkBlanks = false; // Global like your bad decisions


function handleEvaluationSubmit(event) {
    const form = document.getElementById('evaluationForm');
    const inputs = form.querySelectorAll('input, textarea, select'); // YOU THOUGHT YOU COULD HIDE? NOT TODAY
    
    // NOW WE CHECK WHICH SACRED BUTTON WAS SMASHED
    // console.log("Clicked button:", clickedButton);
    // console.log("clickedButton.classList:", clickedButton.classList);
    if (clickedButton && clickedButton.classList.contains('save-buttons')) {
        console.log('SAVE was clicked, baka!');
        // // Do your iframe change here for saving
        changeIframe('../../templates/supervisor/masterlist.php?token={$token}', 'You can continue evaluating later. Press OK to return to the list of Interns.');
    }
    else if (clickedButton && clickedButton.classList.contains('submit-buttons')) {
        let firstBlank = null;

        inputs.forEach(input => {
            if (input.type !== "hidden" && !input.value.trim() && !firstBlank) {
                firstBlank = input;
            }
        });

        if (firstBlank) {
            event.preventDefault();
            firstBlank.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstBlank.focus();
            alert("Fill out all the fields before submitting.");
            return false;
        }

        // You can ask confirmation here before changing iframe
        
        checkBlanks = true; // Set to true to check for blanks
        const confirmed = confirm("You cannot add any more changes once submitted. Submit evaluation?");
        if (!confirmed) {
            event.preventDefault();
            // console.log("Evaluation submission canceled by user with functioning self-preservation.");
            return false;
        }
    }

    // Let form submit or iframe update
    // changeIframe('masterlist.html', 'You cannot add any more changes once submitted. Submit evaluation?');
    return true;
}

const token = window.tokenFromPHP;
// console.log("Token:", token);

document.getElementById("save-button").addEventListener("click", function () {
    const form = document.getElementById("evaluationForm");
    const formData = new FormData(form);
    formData.append('action', 'save'); // 👈 THE BLESSED LINE

    // console.log("Sending fetch to:", window.location.href);
    // console.log("FormData contents:");
    // for (let pair of formData.entries()) {
    //     console.log(pair[0]+ ': ' + pair[1]);
    // }

    fetch(window.location.href, {
        method: "POST",
        body: formData
    })
    .then(res => {
        // console.log("Status Code:", res.status);
        // console.log("Was it OK?", res.ok);
        // return res.text();
    })
    .then(response => {
        // console.log(response);
        // alert("Hehehe~! PHP just got YEETED with a POST request! ✨");

        // document.getElementById("server-response").innerHTML = response;
        // location.reload(); // Refresh the page to see the changes
        changeIframe('../../templates/supervisor/masterlist.php?token=' + token, 'You can continue evaluating later.\nPress OK to return to the list of Interns.');
    })
    .catch(err => {
        console.error("Error summoning the backend demon:", err);
        alert("WHOOPSIE~! The server choked on your dreams 💀");
    });
});


document.getElementById("evaluationForm").addEventListener("submit", function(e) {
    const form = document.getElementById('evaluationForm');
    const inputs = form.querySelectorAll('input, textarea, select'); // YOU THOUGHT YOU COULD HIDE? NOT TODAY
    
    let firstBlank = null;

    inputs.forEach(input => {
        if (input.type !== "hidden" && !input.value.trim() && !firstBlank) {
            firstBlank = input;
        }
    });

    if (firstBlank == null) {
        e.preventDefault(); // STOP the page from reloading like a donkey 🛑🐴

        const formData = new FormData(this);
        formData.append('action', 'submit'); // Add extra data if needed

        fetch(window.location.href, {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            // console.log("Server blessed us:", response);
            // document.getElementById("server-response").innerHTML = response;

            // location.reload(); // Refresh the page to see the changes   
            changeIframe('../../templates/supervisor/masterlist.php?token=' + token, '', true);
        })
        .catch(err => {
            console.error("Form died painfully 💀🔥", err);
        });
    }

});