const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");
const add_sub = document.querySelector("#sub-form");
const loader = document.querySelector(".loader-body");

//showing and hiding loader
function ShowLoader() {
  loader.classList.toggle("loader-body");
  loader.classList.toggle("loader-body-show");
}

function HideLoader() {
  loader.classList.toggle("loader-body-show");
  loader.classList.toggle("loader-body");
}

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
    });
  });
}

//ajax for adding subject
if (add_sub) {
  add_sub.addEventListener("submit", (event) => {
    event.preventDefault();

    const ajax = new XMLHttpRequest();
    const form_data = new FormData(add_sub);

    ShowLoader();

    ajax.open("POST", "../ajax/add_sub.php");

    ajax.onreadystatechange = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const response = JSON.parse(ajax.responseText);
        HideLoader();

        if (response.status == "OK") {
          alert(response.message);
        } else {
          alert(response.error);
        }
      }
    };

    ajax.send(form_data);
  });
}
