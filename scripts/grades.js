const gradeBtns = document.querySelectorAll(".grade_button");
const gradeModal = document.getElementById("grade_modal");
const gradeInput = document.getElementById("grade_input");
const submitGradeBtn = document.getElementById("submit_grade");
const closeModalBtn = document.querySelector('.close');

gradeBtns.forEach(button => {
    button.addEventListener('click', () => {
        gradeModal.style.display = 'block';
        const studentId = button.getAttribute('data_student_id');
        const gradeIndex = button.getAttribute('data_grade_index');
        submitGradeBtn.onclick = () => {
            const grade = gradeInput.value;
            saveGrade(studentId, gradeIndex, grade);
            gradeModal.style.display = "none";
        };
    });
});

closeModalBtn.onclick = () => {
    gradeModal.style.display = 'none';
};

window.onclick = (event) => {
    if (event.target === gradeModal) {
        gradeModal.style.display = "none";
    }
};

function saveGrade(studentId, gradeIndex, grade) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/insert_grade.php', true);
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
    xhr.send('student_id=' + studentId + '&grade_index=' + gradeIndex + '&grade=' + grade);
}