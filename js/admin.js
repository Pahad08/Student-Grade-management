const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");

//toggle drowdown
if (dropdown) {

    dropdown.forEach((element) => {
        element.addEventListener("click", () => {

            const dropdown_body = element.nextElementSibling;
            const arrow = element.lastElementChild;

            if (dropdown_body.classList.contains("show")) {
                dropdown_body.classList.toggle("show");
                arrow.style.transform = "rotate(0deg)";

            } else if (dropdown_body.classList.contains("dropdown")) {
                arrow.style.transform = "rotate(-180deg)";
                dropdown_body.classList.toggle("show");
            }
        })
    })

}