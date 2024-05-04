const journalBtns = document.querySelectorAll('.mark_link');

journalBtns.forEach(button => {
    if (button.textContent == 'Н') {
        button.classList.add('not');
    } else {
        button.classList.remove('not');
    }
});

journalBtns.forEach(button => {
    button.addEventListener('click', () => {
        const studentId = button.getAttribute('data_student_id');
        const markIndex = button.getAttribute('mark_index');
        const markValue = button.textContent;
        saveMark(studentId, markIndex, markValue);
    });
});

function saveMark(studentId, markIndex, mark) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_marks.php', true);
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
    xhr.send('student_id=' + studentId + '&mark_index=' + markIndex + '&mark=' + encodeURIComponent(mark));
}