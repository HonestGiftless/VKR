function getStudents() {
    let groupId = document.getElementById("groupSelect").value;
    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            let btn = document.getElementById("send");
            let text = document.getElementById("descripe_header");

            if (response.hasStudents) {
                btn.style.display = "inline-block";
                text.style.display = "block";
                document.getElementById("studentList").innerHTML = response.studentsHTML;
            } else {
                btn.style.display = "none";
                text.style.display = "none";
                document.getElementById("studentList").innerHTML = response.studentsHTML;
            }
        }
    };

    xhr.open("GET", "gets/get_students_for_mark.php?group_id=" + groupId, true);
    xhr.send();
}