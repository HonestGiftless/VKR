let form = document.getElementById("take_notes");

form.addEventListener("change", function() {
    let option = document.getElementsByTagName("option");
    var paragraph = document.getElementById("without_students");

    let selector = document.getElementById("groupSelect");
    let btn = document.getElementById("send");

    if (paragraph) {
        btn.style.display = "inline-block";
        selector.style.display = "inline-block";
    } else {
        btn.style.display = "none";
    }
});