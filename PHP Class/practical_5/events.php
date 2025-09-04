<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events & Students List</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2em; background: #f8f8f8; }
        .container { background: #fff; padding: 2em; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        h2 { margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2em; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #eee; }
        .pagination { margin: 1em 0; }
        .pagination button { margin: 0 2px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Events List</h2>
    <table id="eventsTable">
        <thead>
            <tr><th>Name</th><th>Date</th><th>Location</th></tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="pagination" id="eventsPagination"></div>

    <h2>Student Profiles</h2>
    <table id="studentsTable">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Course</th></tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="pagination" id="studentsPagination"></div>
</div>
<script>
const eventsJSON = `[
    {"name":"Tech Fest","date":"2025-09-10","location":"Auditorium"},
    {"name":"Sports Day","date":"2025-09-15","location":"Ground"},
    {"name":"Art Expo","date":"2025-09-20","location":"Hall"},
    {"name":"Music Night","date":"2025-09-25","location":"Open Stage"},
    {"name":"Science Fair","date":"2025-09-30","location":"Lab"}
]`;

const studentsJSON = `[
    {"name":"Amit Sharma","email":"amit@example.com","course":"BCA"},
    {"name":"Priya Singh","email":"priya@example.com","course":"BSc"},
    {"name":"John Doe","email":"john@example.com","course":"BBA"},
    {"name":"Sara Khan","email":"sara@example.com","course":"BA"},
    {"name":"Vikram Patel","email":"vikram@example.com","course":"BCom"}
]`;

function renderTable(data, tableId, page, perPage) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    tbody.innerHTML = "";
    const start = (page - 1) * perPage;
    const end = start + perPage;
    data.slice(start, end).forEach(obj => {
        const tr = document.createElement('tr');
        Object.values(obj).forEach(val => {
            const td = document.createElement('td');
            td.textContent = val;
            tr.appendChild(td);
        });
        tbody.appendChild(tr);
    });
}

function renderPagination(data, pagId, tableId, perPage, page, setPage) {
    const pag = document.getElementById(pagId);
    pag.innerHTML = "";
    const totalPages = Math.ceil(data.length / perPage);
    for(let i=1; i<=totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.disabled = (i === page);
        btn.onclick = () => setPage(i);
        pag.appendChild(btn);
    }
}

// Events pagination
let eventsPage = 1;
const eventsPerPage = 2;
const eventsData = JSON.parse(eventsJSON);
function setEventsPage(p) {
    eventsPage = p;
    renderTable(eventsData, "eventsTable", eventsPage, eventsPerPage);
    renderPagination(eventsData, "eventsPagination", "eventsTable", eventsPerPage, eventsPage, setEventsPage);
}
setEventsPage(1);

// Students pagination
let studentsPage = 1;
const studentsPerPage = 2;
const studentsData = JSON.parse(studentsJSON);
function setStudentsPage(p) {
    studentsPage = p;
    renderTable(studentsData, "studentsTable", studentsPage, studentsPerPage);
    renderPagination(studentsData, "studentsPagination", "studentsTable", studentsPerPage, studentsPage, setStudentsPage);
}
setStudentsPage(1);
</script>
</body>
</html>