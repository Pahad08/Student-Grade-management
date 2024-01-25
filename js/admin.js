const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");
const add_sub = document.querySelector("#sub-form");
const loader = document.querySelector(".loader-body");
const alert_body = document.querySelector(".alert-body");
const cancel_delete = document.querySelector("#cancel-delete");
const delete_data = document.querySelector(".delete");
const input_container = document.querySelector(".input-container");
const code_body = document.querySelector("#code-body");
const subname_body = document.querySelector("#sub-body");
const description_body = document.querySelector("#description-body");
const edit_sub = document.querySelector("#edit-subform");
const search_sub = document.querySelector("#search-sub");
const input_sub = document.querySelector("#sub-id");
const search_student = document.querySelector("#search-student");
const search_teacher = document.querySelector("#search-teacher");
const add_student = document.querySelector("#student-form");
const add_teacher = document.querySelector("#teacher-form");
const fname_body = document.querySelector("#fname-body");
const lname_body = document.querySelector("#lname-body");
const gender_body = document.querySelector("#gender-body");
const number_body = document.querySelector("#number-body");
const section_body = document.querySelector("#section-body");
const glevel_body = document.querySelector("#glevel-body");
const username_body = document.querySelector("#username-body");
const email_body = document.querySelector("#email-body");
const input_student = document.querySelector("#student-id");
const input_teacher = document.querySelector("#teacher-id");
const edit_student = document.querySelector("#edit-studentform");
const edit_teacher = document.querySelector("#edit-teacherform");
const grade_form = document.querySelector("#add-grade");
const grades_container = document.querySelector(".input");

//check if the form is empty
function EmptyInput(array) {
  for (let i = 0; i < array.length; i++) {
    let value = array[i][1];
    if (value.toString().trim() !== "") {
      return false;
    }
  }
  return true;
}

//check if all inputs have values
function CompleteForm(array) {
  for (let i = 0; i < array.length; i++) {
    let value = array[i][1];
    if (value.toString().trim() === "") {
      return false;
    }
  }

  return true;
}

//function for adding and editing subject
function EditAddSubject(
  form,
  filename,
  CreateParagraph,
  RemoveParagraph,
  ShowLoader,
  HideLoader
) {
  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(form);
    const arrayform = Array.from(form_data);
    const code = form_data.get("code").trim();
    const subname = form_data.get("subject").trim();
    const description = form_data.get("description").trim();

    if (EmptyInput(arrayform) === true) {
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

    if (CompleteForm(arrayform)) {
      const ajax = new XMLHttpRequest();
      ajax.open("POST", `../ajax/${filename}`);
      ShowLoader();

      ajax.onreadystatechange = () => {
        if (ajax.status == 200 && ajax.readyState == 4) {
          const response = JSON.parse(ajax.responseText);
          HideLoader();

          if (response.status == "OK") {
            form.reset();
            alert(response.message);
          } else if (response.status == "edited") {
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

if (edit_sub) {
  //ajax for edit subject
  EditAddSubject(
    edit_sub,
    "edit_sub.php",
    CreateParagraph,
    RemoveParagraph,
    ShowLoader,
    HideLoader
  );
}

if (add_sub) {
  //ajax for adding subject
  EditAddSubject(
    add_sub,
    "add_sub.php",
    CreateParagraph,
    RemoveParagraph,
    ShowLoader,
    HideLoader
  );
}

//adding and editing Student
function EditAddStudent(
  form,
  filename,
  CreateParagraph,
  RemoveParagraph,
  ShowLoader,
  HideLoader
) {
  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(form);
    const formarray = Array.from(form_data);
    const fname = form_data.get("fname").trim();
    const lname = form_data.get("lname").trim();
    const gender = form_data.get("gender").trim();
    const number = form_data.get("number").trim();
    const section = form_data.get("section").trim();
    const g_level = form_data.get("g-level").trim();
    const username = form_data.get("username").trim();
    const email = form_data.get("email").trim();

    if (EmptyInput(formarray) === true) {
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
      CreateParagraph("Last name cannot be empty", lname_body);
    } else {
      RemoveParagraph(lname_body);
    }

    if (gender == "") {
      CreateParagraph("Gender cannot be empty", gender_body);
    } else {
      RemoveParagraph(gender_body);
    }

    if (number == "") {
      RemoveParagraph(number_body);
      CreateParagraph("Contact number cannot be empty", number_body);
    } else if (number.length > 11) {
      RemoveParagraph(number_body);
      CreateParagraph("Contact number only contain 11 numbers", number_body);
    } else if (number.length < 11) {
      RemoveParagraph(number_body);
      CreateParagraph("Contact number must contain 11 numbers", number_body);
    } else {
      RemoveParagraph(number_body);
    }

    if (section == "") {
      CreateParagraph("Section cannot be empty", section_body);
    } else {
      RemoveParagraph(section_body);
    }

    if (g_level == "") {
      CreateParagraph("Grade level cannot be empty", glevel_body);
    } else {
      RemoveParagraph(glevel_body);
    }

    if (username == "") {
      CreateParagraph("Username cannot be empty", username_body);
    } else {
      RemoveParagraph(username_body);
    }

    if (email == "") {
      CreateParagraph("Email cannot be empty", email_body);
    } else {
      RemoveParagraph(email_body);
    }

    if (CompleteForm(formarray) === true) {
      const ajax = new XMLHttpRequest();
      ajax.open("POST", `../ajax/${filename}`);
      ShowLoader();
      ajax.onreadystatechange = () => {
        if (ajax.status == 200 && ajax.readyState == 4) {
          const response = JSON.parse(ajax.responseText);
          HideLoader();
          if (response.status == "OK") {
            form.reset();
            alert(response.message);
          } else if (response.status == "edited") {
            alert(response.message);
          } else if (response.status == "duplicate") {
            alert(response.message);
          } else if (response.status == "fail") {
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
    RemoveParagraph(gender_body);
    RemoveParagraph(number_body);
    RemoveParagraph(section_body);
    RemoveParagraph(glevel_body);
    RemoveParagraph(username_body);
    RemoveParagraph(email_body);
  });
}

//Add Student
if (add_student) {
  EditAddStudent(
    add_student,
    "../ajax/add_student.php",
    CreateParagraph,
    RemoveParagraph,
    ShowLoader,
    HideLoader
  );
}

//Edit Student
if (edit_student) {
  EditAddStudent(
    edit_student,
    "../ajax/edit_student.php",
    CreateParagraph,
    RemoveParagraph,
    ShowLoader,
    HideLoader
  );
}

//Edit and adding teacher
function EditAddTeacher(
  form,
  filename,
  CreateParagraph,
  RemoveParagraph,
  ShowLoader,
  HideLoader
) {
  const emailbody = document.querySelector("#emailbody");
  form.addEventListener("submit", (event) => {
    event.preventDefault();

    const form_data = new FormData(form);
    const formarray = Array.from(form_data);
    const fname = form_data.get("fname").trim();
    const lname = form_data.get("lname").trim();
    const username = form_data.get("username").trim();
    const email = form_data.get("email").trim();

    if (EmptyInput(formarray) === true) {
      CreateParagraph("First Name cannot be empty", fname_body);
      CreateParagraph("Last Name cannot be empty", lname_body);
      CreateParagraph("Username cannot be empty", username_body);
      CreateParagraph("Email cannot be empty", emailbody);
      return;
    }

    if (fname == "") {
      CreateParagraph("First Name cannot be empty", fname_body);
    } else {
      RemoveParagraph(fname_body);
    }

    if (lname == "") {
      CreateParagraph("Last name cannot be empty", lname_body);
    } else {
      RemoveParagraph(lname_body);
    }

    if (username == "") {
      CreateParagraph("Username cannot be empty", username_body);
    } else {
      RemoveParagraph(username_body);
    }

    if (email == "") {
      CreateParagraph("Email cannot be empty", emailbody);
    } else {
      RemoveParagraph(emailbody);
    }

    if (CompleteForm(formarray) === true) {
      const ajax = new XMLHttpRequest();
      ajax.open("POST", `../ajax/${filename}`);
      ShowLoader();
      ajax.onreadystatechange = () => {
        if (ajax.status == 200 && ajax.readyState == 4) {
          const response = JSON.parse(ajax.responseText);
          HideLoader();
          if (response.status == "OK") {
            form.reset();
            alert(response.message);
          } else if (response.status == "edited") {
            alert(response.message);
          } else if (response.status == "duplicate") {
            alert(response.message);
          } else if (response.status == "fail") {
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
    RemoveParagraph(username_body);
    RemoveParagraph(emailbody);
  });
}

//add teacher
if (add_teacher) {
  //ajax for adding subject
  EditAddTeacher(
    add_teacher,
    "add_teacher.php",
    CreateParagraph,
    RemoveParagraph,
    ShowLoader,
    HideLoader
  );
}

//edit teacher
if (edit_teacher) {
  //ajax for adding subject
  EditAddTeacher(
    edit_teacher,
    "edit_teacher.php",
    CreateParagraph,
    RemoveParagraph,
    ShowLoader,
    HideLoader
  );
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

//search subject
if (search_sub) {
  search_sub.addEventListener("input", () => {
    const ajax = new XMLHttpRequest();
    const sub_value = search_sub.value;

    ajax.onload = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const table = document.querySelector("#table");
        table.innerHTML = ajax.responseText;
        ShowDelete(input_sub);
      }
    };

    ajax.open("GET", `../ajax/search_sub.php?sub=${sub_value}`);
    ajax.send();
  });
}

//search students
if (search_student) {
  search_student.addEventListener("input", () => {
    const ajax = new XMLHttpRequest();
    const student_value = search_student.value;

    ajax.onload = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const table = document.querySelector("#table");
        console.log(ajax.responseText);
        table.innerHTML = ajax.responseText;
        ShowDelete(input_student);
      }
    };

    ajax.open("GET", `../ajax/search_student.php?student=${student_value}`);
    ajax.send();
  });
}

//search teachers
if (search_teacher) {
  search_teacher.addEventListener("input", () => {
    const ajax = new XMLHttpRequest();
    const teacher_value = search_teacher.value;

    ajax.onload = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const table = document.querySelector("#table");
        console.log(ajax.responseText);
        table.innerHTML = ajax.responseText;
        ShowDelete(input_student);
      }
    };

    ajax.open("GET", `../ajax/search_teacher.php?teacher=${teacher_value}`);
    ajax.send();
  });
}

//prompt the alert delete
function ShowDelete(id) {
  const btn_delete = document.querySelectorAll(".btn-delete");
  if (btn_delete) {
    btn_delete.forEach((element) => {
      element.addEventListener("click", () => {
        alert_body.classList.toggle("show");
        const sub_id = element.getAttribute("data-id");
        id.value = sub_id;
      });
    });
  }
}

if (input_sub) {
  ShowDelete(input_sub);
}

if (input_student) {
  ShowDelete(input_student);
}

if (input_teacher) {
  ShowDelete(input_teacher);
}

//remove the show class in the alert body
if (alert_body) {
  alert_body.addEventListener("click", (event) => {
    if (
      event.target.className == "alert-body show" &&
      alert_body.classList.contains("show")
    ) {
      delete_data.removeAttribute("data-id");
      alert_body.classList.remove("show");
    }
  });
}

//close the delete alert
if (cancel_delete) {
  cancel_delete.addEventListener("click", () => {
    delete_data.removeAttribute("data-id");
    alert_body.classList.remove("show");
  });
}

//Check grades input
if (grade_form) {
  grade_form.addEventListener("submit", (event) => {
    const subject = document.querySelector("#select-sub");
    const grade = document.querySelector("#grade");
    const grade_input = document.querySelector("#grade-container");
    const subject_input = document.querySelector("#subject-container");

    if (subject.value.trim() == "" && grade.value == "") {
      CreateParagraph("Subject Cannot be Empty", subject_input);
      CreateParagraph("Grade Cannot be Empty", grade_input);
      event.preventDefault();
    }

    if (subject.value.trim() == "") {
      CreateParagraph("Subject Cannot be Empty", subject_input);
      event.preventDefault();
    } else {
      RemoveParagraph(subject_input);
    }

    if (grade.value == "") {
      CreateParagraph("Grade Cannot be Empty", grade_input);
      event.preventDefault();
    } else {
      RemoveParagraph(grade_input);
    }
  });
}
