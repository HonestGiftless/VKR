const notesBtns = document.querySelectorAll(".notes_link");
const notesModal = document.getElementById("notes_modal");
const notesInput = document.getElementById("notes_input");
const submitNotesBtn = document.getElementById("submit_note");
const closeModalNotesBtn = document.querySelector(".close_notes");

notesBtns.forEach(button => {
    button.addEventListener('click', () => {
        if (button.innerHTML != "Нет") {
            notesInput.value = button.innerHTML + ", ";
        } else {
            notesInput.value = '';
        }
        notesModal.style.display = 'block';
        const studentId = button.getAttribute('data_student_id');
        submitNotesBtn.onclick = () => {
            const newNotes = notesInput.value;
            saveNotes(studentId, newNotes);
            notesModal.style.display = 'none';
        };
    });
});

if (closeModalNotesBtn) {
    closeModalNotesBtn.onclick = () => {
        notesModal.style.display = 'none';
    };
}

window.onclick = (event) => {
    if (event.target === notesModal) {
        notesModal.style.display = 'none';
    }
};

function saveNotes(studentId, notes) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_notes.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                console.log("Error");
            }
        }
    };
    xhr.send('student_id=' + studentId + '&notes_value=' + notes);
}