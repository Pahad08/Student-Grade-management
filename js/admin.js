const dropdown = document.querySelector(".dropdown-text");
const arrow = document.querySelector(".arrow");

//toggle drowdown
if (dropdown) {
    dropdown.addEventListener("click", () => {
        const dropdown_menu = document.querySelector(".dropdown-text+.dropdown");
        const arrow = document.querySelector(".arrow");

        if (dropdown_menu.classList.contains("show")) {

            dropdown_menu.classList.toggle("show");
            arrow.style.transform = "rotate(0deg)";

        } else if (dropdown_menu.classList.contains("dropdown")) {
            arrow.style.transform = "rotate(-180deg)";
            dropdown_menu.classList.toggle("show");
        }

    })
}