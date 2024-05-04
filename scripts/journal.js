function updateCheckboxes() {
    var daysPerWeek = document.getElementById("days_per_week").value;
    var checkboxContainer = document.getElementById("checkbox_container");
    checkboxContainer.innerHTML = "";

    var daysOfWeek = ["Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"];

    for (var i = 0; i < daysOfWeek.length; i++) {
        var checkboxLabel = document.createElement("label");
        checkboxLabel.textContent = daysOfWeek[i] + ": ";

        var checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "selected_weekdays[]";
        checkbox.value = daysOfWeek[i];

        checkboxLabel.appendChild(checkbox);
        checkboxContainer.appendChild(checkboxLabel);
    }

    // Add event listener to checkboxes
    var checkboxes = document.getElementsByName("selected_weekdays[]");
    for (var j = 0; j < checkboxes.length; j++) {
        checkboxes[j].addEventListener("change", function() {
            var selectedCheckboxes = document.querySelectorAll('input[name="selected_weekdays[]"]:checked');
            if (selectedCheckboxes.length > daysPerWeek) {
                this.checked = false;
                alert("Вы можете выбрать максимум " + daysPerWeek + " дней!");
            }
        });
    }
}