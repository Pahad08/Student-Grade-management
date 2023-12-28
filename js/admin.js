const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");
const add_sub = document.querySelector("#sub-form");
const loader = document.querySelector(".loader-body");
const btn_delete = document.querySelectorAll(".btn-delete");
const alert_body = document.querySelector('.alert-body');
const cancel_delete = document.querySelector('#cancel-delete');
const delete_sub = document.querySelector('#delete-sub');

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
if (delete_sub) {
  delete_sub.addEventListener("click", (event) => {

    event.preventDefault();

    const ajax = new XMLHttpRequest();
    const id = delete_sub.getAttribute('data-id');

    ShowLoader();

    ajax.onreadystatechange = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const response = JSON.parse(ajax.responseText);
        HideLoader();

        if (response.status == "OK") {
          alert_body.classList.remove('show');
          alert(response.message);
          location.reload();
        } else {
          alert(response.error);
          alert_body.classList.remove('show');
        }
      }
    };

    ajax.open("GET", `../ajax/delete_sub.php?sub_id=${id}`);
    ajax.send();
  });
}

//delete sub
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

//prompt the alert message
if(btn_delete){
  btn_delete.forEach((element)=>{
    element.addEventListener('click',()=>{
      alert_body.classList.toggle('show')
      let sub_id = element.getAttribute('data-id');
      delete_sub.setAttribute('data-id', sub_id);
    })
  
  })
}

//remove the show class in the alert body
alert_body.addEventListener('click',(event)=>{
  if(event.target.className == 'alert-body show' && alert_body.classList.contains('show')){
    delete_sub.removeAttribute('data-id');
    alert_body.classList.remove('show');
  }
})

cancel_delete.addEventListener('click', ()=>{
  delete_sub.removeAttribute('data-id');
  alert_body.classList.remove('show')
})

