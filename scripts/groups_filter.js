const rowsPerPage = 5;
const table = document.getElementById("students-table");
const tbody = table.querySelector("tbody");
const paginationElement = document.getElementById("pagination");

const rows = Array.from(tbody.getElementsByTagName("tr"));
let filteredRows = Array.from(tbody.getElementsByTagName("tr"));
let currentPage = 1;

const updateTable = () => {
  while (tbody.firstChild) {
    tbody.removeChild(tbody.firstChild);
  }

  const start = (currentPage - 1) * rowsPerPage;
  const end = start + rowsPerPage;
  const paginatedItems = filteredRows.slice(start, end);

  paginatedItems.forEach((item) => tbody.appendChild(item));
};

const setupPagination = (rows) => {
  while (paginationElement.firstChild) {
    paginationElement.removeChild(paginationElement.firstChild);
  }

  const pageCount = Math.ceil(rows.length / rowsPerPage);
  for (let i = 1; i <= pageCount; i++) {
    const link = document.createElement("a");
    link.innerText = i;
    link.className = "pagination-link";
    link.addEventListener("click", function () {
      currentPage = i;
      updateTable();
    });

    paginationElement.appendChild(link);
  }
};

window.onload = () => {
  setupPagination(filteredRows);
  updateTable();
};