const btn = document.querySelector('.delete-button');
const deleter = document.getElementById('deleter');
const container = document.getElementById('delete_container');
const checkboxes = document.querySelectorAll('.delete_cell');
let selectedCheckboxes = [];

function createText() {
    let text = document.createElement("h3");
    text.innerHTML = "Выберите необходимых учеников, а затем нажмите на кнопку удаления";
    text.setAttribute('class', 'description_delete');

    return text;
}

function showCheckboxes(checks) {
    checks.forEach(element => {
        element.style.display = 'block';
    });
}

function toggleSelected(event) {
    const studentId = event.target.getAttribute('student_id');
    
    if (event.target.checked) {
        selectedCheckboxes.push(studentId);
    } else {
        const index = selectedCheckboxes.indexOf(studentId);
        if (index !== -1) {
            selectedCheckboxes.splice(index, 1);
        }
    }
}

function deleteStudent(studentId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'deletes/delete_student.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                console.log("ERROR");
            }
        }
    };
    xhr.send('student_id=' + studentId);
}

if (document.getElementById("students-table")) {
    btn.addEventListener('click', function() {
        btn.style.display = 'none';
        const text = createText();
        container.appendChild(text);
        deleter.style.display = 'block';
    
        showCheckboxes(checkboxes);
    });

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', toggleSelected);
    });
    
    deleter.addEventListener('click', function() {
        deleteStudent(selectedCheckboxes);
    });
}