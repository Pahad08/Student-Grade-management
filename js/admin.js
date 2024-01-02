const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");
const add_sub = document.querySelector("#sub-form");
const loader = document.querySelector(".loader-body");
const btn_delete = document.querySelectorAll(".btn-delete");
const alert_body = document.querySelector(".alert-body");
const cancel_delete = document.querySelector("#cancel-delete");
const delete_sub = document.querySelector("#delete-sub");
const input_container = document.querySelector(".input-container");
const code_body = document.querySelector("#code-body");
const subname_body = document.querySelector("#sub-body");
const description_body = document.querySelector("#description-body");
const edit_sub = document.querySelector("#edit-subform");

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

//create paragraph
function CreateParagraph(message, element) {
  if (!element.querySelector(`.error-message`)) {
    const paragraph = document.createElement("p");
    const classname = document.createAttribute("class");
    classname.value = "error-message";
    paragraph.setAttribute("class", classname.value);
    paragraph.innerText = message;
    paragraph.style.fontSize = "1rem";
    paragraph.style.color = "var(--red)";
    element.insertAdjacentElement("beforeend", paragraph);
  }
}

//remove paragraph
function RemoveParagraph(element) {
  const element_id = element.id;
  const err_mess = document.querySelector(`#${element_id} .error-message`);
  if (element.querySelector(`.error-message`)) {
    element.removeChild(err_mess);
  }
}

if (edit_sub) {
  //ajax for edit subject
  edit_sub.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(edit_sub);
    const code = form_data.get("code");
    const subname = form_data.get("subject");
    const description = form_data.get("description");

    if (code == "" && subname == "" && description == "") {
      CreateParagraph("Code cannot be empty", code_body);
      CreateParagraph("Subject cannot be empty", subname_body);
      CreateParagraph("Description cannot be empty", description_body);
      return;
    }

    if (code == "") {
      CreateParagraph("Code cannot be empty", code_body);
    } else {
      RemoveParagraph(code_body);
    }

    if (subname == "") {
      CreateParagraph("Subject cannot be empty", subname_body);
    } else {
      RemoveParagraph(subname_body);
    }

    if (description == "") {
      CreateParagraph("Description cannot be empty", description_body);
    } else {
      RemoveParagraph(description_body);
    }

    if (code !== "" && subname !== "" && description !== "") {
      const ajax = new XMLHttpRequest();
      ajax.open("POST", "../ajax/edit_sub.php");

      ShowLoader();

      ajax.onreadystatechange = () => {
        if (ajax.status == 200 && ajax.readyState == 4) {
          const response = JSON.parse(ajax.responseText);
          HideLoader();

          if (response.status == "OK") {
            alert(response.message);
            window.location.href = "view_subject.php";
          } else {
            alert(response.error);
          }
        }
      };
      ajax.send(form_data);
    }
  });

  //remove error message if form reset
  edit_sub.addEventListener("reset", () => {
    RemoveParagraph(code_body);
    RemoveParagraph(subname_body);
    RemoveParagraph(description_body);
  });
}

if (add_sub) {
  //ajax for adding subject
  add_sub.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(add_sub);
    const code = form_data.get("code");
    const subname = form_data.get("subject");
    const description = form_data.get("description");

    if (code == "" && subname == "" && description == "") {
      CreateParagraph("Code cannot be empty", code_body);
      CreateParagraph("Subject cannot be empty", subname_body);
      CreateParagraph("Description cannot be empty", description_body);
      return;
    }

    if (code == "") {
      CreateParagraph("Code cannot be empty", code_body);
    } else {
      RemoveParagraph(code_body);
    }

    if (subname == "") {
      CreateParagraph("Subject cannot be empty", subname_body);
    } else {
      RemoveParagraph(subname_body);
    }

    if (description == "") {
      CreateParagraph("Description cannot be empty", description_body);
    } else {
      RemoveParagraph(description_body);
    }

    if (code !== "" && subname !== "" && description !== "") {
      const ajax = new XMLHttpRequest();
      ajax.open("POST", "../ajax/add_sub.php");

      ShowLoader();

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
    }
  });

  //remove error message if form reset
  add_sub.addEventListener("reset", () => {
    RemoveParagraph(code_body);
    RemoveParagraph(subname_body);
    RemoveParagraph(description_body);
  });
}

//prompt the alert delete
if (btn_delete) {
  btn_delete.forEach((element) => {
    element.addEventListener("click", () => {
      alert_body.classList.toggle("show");
      const sub_id = element.getAttribute("data-id");
      const input_sub = document.querySelector("#sub-id");

      input_sub.value = sub_id;
    });
  });
}

//remove the show class in the alert body
if (alert_body) {
  alert_body.addEventListener("click", (event) => {
    if (
      event.target.className == "alert-body show" &&
      alert_body.classList.contains("show")
    ) {
      delete_sub.removeAttribute("data-id");
      alert_body.classList.remove("show");
    }
  });
}

//close the delete alert
if (cancel_delete) {
  cancel_delete.addEventListener("click", () => {
    delete_sub.removeAttribute("data-id");
    alert_body.classList.remove("show");
  });
}
