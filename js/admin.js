const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");
const add_sub = document.querySelector("#sub-form");
const loader = document.querySelector(".loader-body");
const alert_body = document.querySelector(".alert-body");
const cancel_delete = document.querySelector("#cancel-delete");
const delete_sub = document.querySelector("#delete-sub");
const input_container = document.querySelector(".input-container");
const code_body = document.querySelector("#code-body");
const subname_body = document.querySelector("#sub-body");
const description_body = document.querySelector("#description-body");
const edit_sub = document.querySelector("#edit-subform");
const search_sub = document.querySelector("#search-sub");
const add_student = document.querySelector("#student-form");
const fname_body = document.querySelector("#fname-body");
const lname_body = document.querySelector("#lname-body");
const number_body = document.querySelector("#number-body");
const section_body = document.querySelector("#section-body");
const glevel_body = document.querySelector("#glevel-body");
const username_body = document.querySelector("#username-body");
const email_body = document.querySelector("#email-body");

//function for adding and editing subject
function EditAddSubject(form, filename) {
  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(form);
    const code = form_data.get("code").trim();
    const subname = form_data.get("subject").trim();
    const description = form_data.get("description").trim();

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
      ajax.open("POST", `../ajax/${filename}`);

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
  form.addEventListener("reset", () => {
    RemoveParagraph(code_body);
    RemoveParagraph(subname_body);
    RemoveParagraph(description_body);
  });
}

//adding and editing Student
function EditAddStudent(form) {
  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(form);
    const fname = form_data.get("fname").trim();
    const lname = form_data.get("lname").trim();
    const number = form_data.get("number").trim();
    const section = form_data.get("section").trim();
    const g_level = form_data.get("g-level").trim();
    const username = form_data.get("username").trim();
    const email = form_data.get("email").trim();

    if (
      fname == "" &&
      lname == "" &&
      number == "" &&
      section == "" &&
      g_level == "" &&
      username == "" &&
      email == ""
    ) {
      CreateParagraph("First Name cannot be empty", fname_body);
      CreateParagraph("Last Name cannot be empty", lname_body);
      CreateParagraph("Contact number cannot be empty", number_body);
      CreateParagraph("Section cannot be empty", section_body);
      CreateParagraph("Grade level cannot be empty", glevel_body);
      CreateParagraph("Username cannot be empty", username_body);
      CreateParagraph("Email cannot be empty", email_body);
      return;
    }

    if (fname == "") {
      CreateParagraph("First Name cannot be empty", fname_body);
    } else {
      RemoveParagraph(fname_body);
    }

    if (lname == "") {
      CreateParagraph("Last Name be empty", lname_body);
    } else {
      RemoveParagraph(lname_body);
    }

    if (number == "") {
      CreateParagraph("Contact number cannot be empty", number_body);
    } else {
      RemoveParagraph(number_body);
    }

    if (section == "") {
      CreateParagraph("Contact number cannot be empty", section_body);
    } else {
      RemoveParagraph(section_body);
    }

    if (g_level == "") {
      CreateParagraph("Contact number cannot be empty", glevel_body);
    } else {
      RemoveParagraph(glevel_body);
    }

    if (username == "") {
      CreateParagraph("Contact number cannot be empty", username_body);
    } else {
      RemoveParagraph(username_body);
    }

    if (email == "") {
      CreateParagraph("Contact number cannot be empty", email_body);
    } else {
      RemoveParagraph(email_body);
    }

    if (
      fname == "" &&
      lname !== "" &&
      number !== "" &&
      section !== "" &&
      g_level !== "" &&
      username !== "" &&
      email !== ""
    ) {
      const ajax = new XMLHttpRequest();
      ajax.open("POST", `../ajax/${filename}`);

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
  form.addEventListener("reset", () => {
    RemoveParagraph(fname_body);
    RemoveParagraph(lname_body);
    RemoveParagraph(number_body);
    RemoveParagraph(section_body);
    RemoveParagraph(glevel_body);
    RemoveParagraph(username_body);
    RemoveParagraph(email_body);
  });
}

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
  EditAddSubject(edit_sub, "edit_sub.php");
}

if (add_sub) {
  //ajax for adding subject
  EditAddSubject(add_sub, "add_sub.php");
}

//search subject
if (search_sub) {
  search_sub.addEventListener("input", () => {
    const ajax = new XMLHttpRequest();
    const sub_value = search_sub.value;

    ajax.onload = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const table = document.querySelector("#table-subject");
        table.innerHTML = ajax.responseText;
        ShowDelete();
      }
    };

    ajax.open("GET", `../ajax/search_sub.php?sub=${sub_value}`);
    ajax.send();
  });
}

//prompt the alert delete
function ShowDelete() {
  const btn_delete = document.querySelectorAll(".btn-delete");
  const input_sub = document.querySelector("#sub-id");
  if (btn_delete) {
    btn_delete.forEach((element) => {
      element.addEventListener("click", () => {
        alert_body.classList.toggle("show");
        const sub_id = element.getAttribute("data-id");
        input_sub.value = sub_id;
      });
    });
  }
}

ShowDelete();

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
