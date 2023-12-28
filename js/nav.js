const menu_icon = document.querySelector("#menu-icon");
const sidebar = document.querySelector(".sidebar");
const body = document.querySelector(".body");

//sidebar function
if (menu_icon) {
  menu_icon.addEventListener("click", () => {
    if (window.innerWidth > 991) {
      sidebar.classList.toggle("hide-nav");
      body.classList.toggle("grow");
    }

    if (window.innerWidth < 991) {
      sidebar.classList.toggle("show-nav");
    }
  });
}

//hide sidebar for responsiveness
window.addEventListener("resize", () => {
  if (window.innerWidth > 991 && sidebar.classList.contains("show-nav")) {
    sidebar.classList.remove("show-nav");
  }
  if (
    window.innerWidth < 991 &&
    body.classList.contains("grow") &&
    sidebar.classList.contains("hide-nav")
  ) {
    body.classList.remove("grow");
    sidebar.classList.remove("hide-nav");
  }
});
