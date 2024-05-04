const groupBtns = document.querySelectorAll(".group_link");
const groupModal = document.getElementById("group_modal");
const groupSelector = document.getElementById("take_group");
const submitGroupBtn = document.getElementById("submit_group");
const closeModalGroupBtn = document.querySelector(".close_group");

groupBtns.forEach(button => {
    button.addEventListener('click', () => {
        groupModal.style.display = 'block';
        const studentId = button.getAttribute('data_student_id');
        const currGroupId = button.getAttribute('current_group_id');
        submitGroupBtn.onclick = () => {
            const newGroup = groupSelector.value;
            saveNewGroup(studentId, currGroupId, newGroup);
            groupModal.style.display = "none";
        };
    });
});

if (closeModalGroupBtn) {
    closeModalGroupBtn.onclick = () => {
        groupModal.style.display = 'none';
    };
}

window.onclick = (event) => {
    if (event.target === groupModal) {
        groupModal.style.display = 'none';
    }
};

function saveNewGroup(studentId, oldGroup, newGroup) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'inserts/edit_student_group.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.status === 200) {
            location.reload();
        } else {
            console.log("NO");
        }
    };
    xhr.send('student_id=' + studentId + '&currentGroupId=' + oldGroup + '&group_id=' + newGroup);
}