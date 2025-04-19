const filterInput = document.getElementById('userFilter');
const cards = document.querySelectorAll('.card');
const tableRows = document.querySelectorAll('#tableView tbody tr');
const noResults = document.getElementById('noResults');

filterInput.addEventListener('input', function () {
  const filter = this.value.toLowerCase().trim();
  let anyVisible = false;

  // 🃏 Filter CARDS by name, course, and intern-id
  cards.forEach(card => {
    const name = card.querySelector('.name')?.textContent.toLowerCase() || "";
    const course = card.querySelector('.course')?.textContent.toLowerCase() || "";
    const id = card.querySelector('.intern-id')?.textContent.toLowerCase() || "";

    const isMatch = name.includes(filter) || course.includes(filter) || id.includes(filter);
    card.style.display = isMatch ? '' : 'none';
    if (isMatch) anyVisible = true;
  });

  // 🧾 Filter TABLE ROWS by *any* column text
  tableRows.forEach(row => {
    const rowText = Array.from(row.children).map(td => td.textContent.toLowerCase()).join(" ");
    const isMatch = rowText.includes(filter);
    row.style.display = isMatch ? '' : 'none';
    if (isMatch) anyVisible = true;
  });

  // 😔 Show/hide "No results found"
  noResults.style.display = anyVisible ? 'none' : 'block';
});

document.querySelectorAll('.card .name').forEach(el => {
    const span = document.createElement('span');
    span.textContent = el.textContent;
    el.innerHTML = '';
    el.appendChild(span);

    const containerWidth = el.offsetWidth;
    const textWidth = span.scrollWidth;

    if (textWidth > containerWidth) {
        const distance = textWidth - containerWidth;
        const duration = distance * 15; // Adjust speed here

        span.style.animation = `scrollOnly ${duration}ms linear infinite`;
        span.style.minWidth = 'max-content';

        const styleTag = document.createElement('style');
        styleTag.textContent = `
            @keyframes scrollOnly {
                0%   { transform: translateX(0); }
                100% { transform: translateX(-${distance}px); }
            }
        `;
        document.head.appendChild(styleTag);
    }
});

const evalBtns = document.querySelectorAll('.evaluation-btn[data-status="done"]');

evalBtns.forEach(btn => {
  btn.addEventListener('mouseenter', () => {
    btn.textContent = 'VIEW EVALUATION';
  });

  btn.addEventListener('mouseleave', () => {
    btn.textContent = 'DONE';
  });
});
