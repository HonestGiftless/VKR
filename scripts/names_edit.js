const gradeBtns = document.querySelectorAll(".name_link");
const gradeModal = document.getElementById("grade_modal");
const gradeInput = document.getElementById("grade_input");
const submitGradeBtn = document.getElementById("submit_grade");
const closeModalBtn = document.querySelector('.close');

gradeBtns.forEach(button => {
    button.addEventListener('click', () => {
        gradeModal.style.display = 'block';
        const studentId = button.getAttribute('data_student_id');
        submitGradeBtn.onclick = () => {
            const grade = gradeInput.value;
            saveGrade(studentId, grade);
            gradeModal.style.display = "none";
        };
    });
});

if (closeModalBtn) {
    closeModalBtn.onclick = () => {
        gradeModal.style.display = 'none';
    };
}

window.onclick = (event) => {
    if (event.target === gradeModal) {
        gradeModal.style.display = "none";
    }
};

function saveGrade(studentId, grade) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_student_name.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                console.log("NO");
            }
        }
    };
    xhr.send('student_id=' + studentId + '&grade_value=' + grade);
}