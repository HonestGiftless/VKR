// конфигурация пагинации
const rowsPerPage = 5;
const table = document.getElementById("students-table");

if (table) {
  const tbody = table.querySelector("tbody");
  const paginationElement = document.getElementById("pagination");

  hasGroup = true;

  // создание общего списка, отфильтрованного списка и текущей страницы
  const rows = Array.from(tbody.getElementsByTagName("tr"));
  let filteredRows = Array.from(tbody.getElementsByTagName("tr"));
  let currentPage = 1;

  document.getElementById("inGroupBtn").addEventListener("click", function () {
    hasGroup = true;
    filterTable();
  });

  document.getElementById("noGroupBtn").addEventListener("click", function () {
    hasGroup = false;
    filterTable();
  });

  const updateTable = () => {
    // очистка таблицы
    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }

    // Ррасчет среза строк
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedItems = filteredRows.slice(start, end);

    // вставка строк в таблицу
    paginatedItems.forEach((item) => tbody.appendChild(item));
  };

  const setupPagination = (rows) => {
    // очистка кнопок страниц
    while (paginationElement.firstChild) {
      paginationElement.removeChild(paginationElement.firstChild);
    }

    // считаем страницы и добавляем кнопки
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

  const filterTable = () => {
    // фильтруем строки таблицы
    filteredRows = rows.filter((row) => {
      const groupCell = row.getElementsByTagName("td")[6];
      if (hasGroup) {
        return groupCell.innerHTML !== "Индивидуальное";
      } else {
        return groupCell.innerHTML === "Индивидуальное";
      }
    });

    currentPage = 1;
    setupPagination(filteredRows);
    updateTable();
  };

  window.onload = () => {
    filterTable();
  };
}