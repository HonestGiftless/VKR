const form = document.querySelector(".form-block");
const button = document.querySelector(".add-button");
let hidden = true;

button.addEventListener("click", () => {
    hidden = !hidden;
    form.style.display = hidden ? "none" : "flex";
    button.innerHTML = hidden ? "Добавить" : "Закрыть";

    if (hidden) {
        form.querySelector("form").reset();
    }
});