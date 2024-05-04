const groupBtns = document.querySelectorAll(".group_link");
const groupModal = document.getElementById("group_modal");
const groupInput = document.getElementById("group_input");
const submitGroupBtn = document.getElementById("submit_group_name");
const closeGroupModalBtn = document.querySelector(".close_group_modal");

const priorityBtns = document.querySelectorAll(".priority_link");
const priorityModal = document.getElementById("priority_modal");
const priorityInput = document.getElementById("priority_input");
const submitPriorityBtn = document.getElementById("submit_priority");
const closePriorityBtn = document.querySelector(".close_priority_modal");

const notesBtns = document.querySelectorAll(".group_notes_link");
const notesModal = document.getElementById("notes_modal");
const notesInput = document.getElementById("notes_input");
const submitNotesBtn = document.getElementById("submit_notes");
const closeNotesBtn = document.querySelector(".close_notes_modal");

groupBtns.forEach(button => {
    button.addEventListener('click', () => {
        groupModal.style.display = 'block';
        const groupId = button.getAttribute('group_id');
        submitGroupBtn.onclick = () => {
            const groupName = groupInput.value;
            saveGroupName(groupId, groupName);
            groupModal.style.display = 'none';
        };
    });
});

priorityBtns.forEach(button => {
    button.addEventListener('click', () => {
        priorityInput.placeholder = button.innerHTML;
        priorityModal.style.display = 'block';
        const groupIdPriority = button.getAttribute('group_id');
        submitPriorityBtn.onclick = () => {
            const newPriority = priorityInput.value;
            saveNewPriority(groupIdPriority, newPriority);
            priorityModal.style.display = 'none';
        };
    });
});

notesBtns.forEach(button => {
    button.addEventListener('click', () => {
        if (button.textContent.toLowerCase() != "нет") {
            notesInput.placeholder = button.innerHTML;
        }
        notesModal.style.display = 'block';
        const groupIdNotes = button.getAttribute('group_id');
        submitNotesBtn.onclick = () => {
            const newNotes = notesInput.value;
            saveNewNotes(groupIdNotes, newNotes);
            notesModal.style.display = 'none';
        };
    });
});

closeGroupModalBtn.onclick = () => {
    groupModal.style.display = 'none';
};

closePriorityBtn.onclick = () => {
    priorityModal.style.display = 'none';
};

closeNotesBtn.onclick = () => {
    notesModal.style.display = 'none';
};

window.onclick = (event) => {
    if (event.target === groupModal) {
        groupModal.style.display = 'none';
    }
};

window.onclick = (event) => {
    if (event.target === priorityModal) {
        priorityModal.style.display = 'none';
    }
};

window.onclick = (event) => {
    if (event.target === notesModal) {
        notesModal.style.display = 'none';
    }
};

function saveGroupName(groupId, groupName) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_groups_name.php', true);
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
    xhr.send('group_id=' + groupId + '&group_name=' + groupName);
}

function saveNewPriority(groupId, priority) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_group_priority.php', true);
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
    xhr.send('group_id=' + groupId + '&priority_name=' + priority);
}

function saveNewNotes(groupId, newNotes) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_group_notes.php', true);
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
    xhr.send('group_id=' + groupId + '&new_notes=' + newNotes);
}