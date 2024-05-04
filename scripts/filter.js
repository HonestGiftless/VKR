const rowPerPage = 5;
const table = document.getElementById("students-table");

if (table !== null) {
    const tbody = table.querySelector("tbody");
    const paginationElement = document.getElementById("pagination");

    hasGroup = true;

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
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }

        const start = (currentPage - 1) * rowPerPage;
        const end = start + rowPerPage;
        const paginatedItems = filteredRows.slice(start, end);

        paginatedItems.forEach((item) => tbody.appendChild(item));
    }

    const setupPagination = (rows) => {
        while (paginationElement.firstChild) {
            paginationElement.removeChild(paginationElement.firstChild);
        }

        const pageCount = Math.ceil(rows.length / rowPerPage);

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
        filteredRows = rows.filter((row) => {
            const groupCell = row.getElementsByTagName("td")[2];
            const groupCell2 = groupCell.getElementsByTagName("a")[0];
            if (hasGroup) {
                return groupCell2.innerHTML !== "Без группы";
            } else {
                return groupCell2.innerHTML === "Без группы";
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
