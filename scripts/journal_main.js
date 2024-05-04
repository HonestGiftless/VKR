function updateDropdown() {
    var daysPerWeek = document.getElementById("days_per_week").value;
    var dropDownContainer = document.getElementById("dropdown_container");

    dropDownContainer.innerHTML = "";

    if (1 <= daysPerWeek && daysPerWeek <= 7) {
        for (var i = 0; i < daysPerWeek; i++) {
            var label = document.createElement("label");
            label.textContent = "Выберите день " + (i + 1) + ": ";

            var select = document.createElement("select");
            select.name = "selected_weekdays[]";
            select.required = true;

            var daysOfWeek = ["Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"];
            for (var j = 0; j < daysOfWeek.length; j++) {
                var option = document.createElement("option");
                option.text = daysOfWeek[j];
                option.value = daysOfWeek[j];
                select.appendChild(option);
            }

            label.appendChild(select);
            dropDownContainer.appendChild(label);
        }
    } else {
        var label = document.createElement("label");
        label.textContent = "Нельзя выбирать меньше 1 или больше 7!";
        dropDownContainer.appendChild(label);
    }
}