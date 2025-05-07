document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.clickable-row');
    const info = document.querySelector('.user-info');

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const isActive = row.classList.contains('active');

            // Remove 'active' from all rows
            rows.forEach(r => r.classList.remove('active'));

            if (!isActive) {
                // Re-mark this row if it wasn't active
                row.classList.add('active');

                const data = JSON.parse(row.getAttribute('data-row'));
                info.innerHTML = `
                <h2>USER INFORMATION</h2>
                <p>Name: ${data.first} ${data.last}</p>
                <p>Gender: ${data.gender}</p>
                <p>Birthdate: ${data.birthdate}</p>
                <p>Address: ${data.address}</p>
                <p>Email: ${data.email}</p>
                <p>Date Created: ${data.created}</p>
                <p>Date Updated: ${data.updated}</p>
                <hr>
                <p>Batch: ${data.batch}</p>
                <p>Student ID: ${data.internship_id}</p>
                <p>Course: ${data.course}</p>
                <p>School and Program: ${data.school_program}</p>
                <hr>
                <p>Job Role: ${data.job_role}</p>
                <p>Supervisor: ${data.supervisor}</p>
                <p>Department: ${data.department}</p>
                <p>Company: ${data.company}</p>
                <hr>
                <p>Internship Year: ${data.year}</p>
                <p>Internship Start: ${data.start}</p>
                <p>Internship End: ${data.end}</p>
                `;
            } else {
                // DE-ACTIVATE AND PURGE
                info.innerHTML = "<h2>USER INFORMATION</h2><p>Select a user to view info.</p>";
            }
        });
    });
});