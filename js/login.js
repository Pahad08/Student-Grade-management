const email_form = document.querySelector("#email-form");
const reset_form = document.querySelector("#reset-form");
const show_password = document.querySelector("#eye");
const loader = document.querySelector(".loader-body");

function ShowLoader() {
  loader.classList.toggle("loader-body");
  loader.classList.toggle("loader-body-show");
}

function HideLoader() {
  loader.classList.toggle("loader-body-show");
  loader.classList.toggle("loader-body");
}

if (email_form) {
  email_form.addEventListener("submit", (event) => {
    event.preventDefault();
    const ajax = new XMLHttpRequest();
    const form_data = new FormData(email_form);

    ShowLoader();

    ajax.open("POST", "../ajax/send_email.php");

    ajax.onreadystatechange = () => {
      if (ajax.readyState == 4 && ajax.status == 200) {
        const response = JSON.parse(ajax.responseText);
        HideLoader();
        if (response.status == "OK") {
          alert(response.message);
        } else if (response.status == "fail") {
          alert(response.error);
        } else {
          alert(response.empty);
        }
      }
    };

    ajax.send(form_data);
  });
}

if (reset_form) {
  reset_form.addEventListener("submit", (event) => {
    event.preventDefault();
    const ajax = new XMLHttpRequest();
    const form_data = new FormData(reset_form);

    ShowLoader();

    ajax.open("POST", "../ajax/reset_pass.php");

    ajax.onreadystatechange = () => {
      if (ajax.readyState == 4 && ajax.status == 200) {
        const response = JSON.parse(ajax.responseText);
        HideLoader();
        if (response.status == "OK") {
          alert(response.message);
          window.location.href = "login.php";
        } else {
          alert(response.error);
        }
      }
    };

    ajax.send(form_data);
  });
}

if (show_password) {
  show_password.addEventListener("click", () => {
    const password = document.querySelector("#password");
    if (password.type == "password") {
      password.type = "text";
      show_password.src = "../images/open-eye.png";
    } else {
      password.type = "password";
      show_password.src = "../images/close-eye.png";
    }
  });
}
