const menu_icon = document.querySelector("#menu-icon");

if (menu_icon) {
    menu_icon.addEventListener("click", () => {
        const body = document.querySelector(".body");
        const sidebar = document.querySelector(".sidebar");

        sidebar.classList.toggle("hide-nav")
        body.classList.toggle("body-grow")
    })
}