const eventsJSON = `[
  {"id":1, "title":"Tech Fest", "date":"2025-10-15"},
  {"id":2, "title":"Sports Meet", "date":"2025-11-02"},
  {"id":3, "title":"Cultural Night", "date":"2025-11-20"},
  {"id":4, "title":"Hackathon", "date":"2025-12-05"},
  {"id":5, "title":"Alumni Meet", "date":"2026-01-10"}
]`;

const studentsJSON = `[
  {"id":1, "name":"Alice", "course":"CSE", "year":3},
  {"id":2, "name":"Bob", "course":"ECE", "year":2}
]`;


let events = [];
let students = [];
try {
  events = JSON.parse(eventsJSON);
} catch (e) {
  console.error('Failed to parse events JSON', e);
}
try {
  students = JSON.parse(studentsJSON);
} catch (e) {
  console.error('Failed to parse students JSON', e);
}


function renderList(containerId, data, renderFn) {
  const container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = '';
  data.forEach(item => container.innerHTML += renderFn(item));
}

function eventCard(event) {
  return `
    <div class="card">
      <h3>${event.title}</h3>
      <p>Date: ${event.date}</p>
    </div>`;
}

function studentCard(student) {
  return `
    <div class="card">
      <h3>${student.name}</h3>
      <p>Course: ${student.course} | Year: ${student.year}</p>
    </div>`;
}

let currentPage = 1;
const studentsPerPage = 3;

function renderStudentsPage(page) {
  const start = (page - 1) * studentsPerPage;
  const paginatedData = students.slice(start, start + studentsPerPage);
  renderList('students-container', paginatedData, studentCard);

  document.getElementById('prevBtn').disabled = page === 1;
  document.getElementById('nextBtn').disabled = start + studentsPerPage >= students.length;
}

window.onload = () => {
  renderList('events-container', events, eventCard);
  renderStudentsPage(currentPage);

  document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentPage > 1) {
      currentPage--;
      renderStudentsPage(currentPage);
    }
  });

  document.getElementById('nextBtn').addEventListener('onclick', () => {
    if ((currentPage * studentsPerPage) < students.length) {
      currentPage++;
      renderStudentsPage(currentPage);
    }
  });
};
