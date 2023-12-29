const dropdown = document.querySelectorAll(".dropdown-text");
const arrow = document.querySelector(".arrow");
const add_sub = document.querySelector("#sub-form");
const loader = document.querySelector(".loader-body");
const btn_delete = document.querySelectorAll(".btn-delete");
const alert_body = document.querySelector('.alert-body');
const cancel_delete = document.querySelector('#cancel-delete');
const delete_sub = document.querySelector('#delete-sub');
const input_container =document.querySelector('.input-container');

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
function CreateParagraph(response, element){

  const paragraph = document.createElement('p');
  const classname = document.createAttribute('class');
  classname.value = 'error-message';
  paragraph.setAttribute('class', classname.value);
  paragraph.innerText = response.message;
  paragraph.style.fontSize = '1rem';
  paragraph.style.color = 'var(--red)';

  element.insertAdjacentElement('beforeend', paragraph);

}

//ajax for adding subject
if (add_sub) {
  add_sub.addEventListener("submit", (event) => {
    event.preventDefault();

    const ajax = new XMLHttpRequest();
    const form_data = new FormData(add_sub);
    const code = document.querySelector('.input-container .input-body:nth-child(1)');
    const subject_name = document.querySelector('.input-container .input-body:nth-child(2)');
    const description = document.querySelector('.input-container .input-body:nth-child(3)');

    ShowLoader();

    ajax.open("POST", "../ajax/add_sub.php");

    ajax.onreadystatechange = () => {
      if (ajax.status == 200 && ajax.readyState == 4) {
        const response = JSON.parse(ajax.responseText);
        HideLoader();

        console.log(response[0][0].message)
        console.log(response[1][0])
        console.log(response[2])

        // if (response.status == "OK") {

        //   alert(response.message);

        // }else if(response.status=='empty_code'){

        //   CreateParagraph(response, code);

        // }else if(response.status=='empty_subject'){

        //   CreateParagraph(response, subject_name);

        // }else if(response.status=='empty_description'){

        //   CreateParagraph(response, description);

        // }else if(response.status=='empty_inputs'){

        //   CreateParagraph(response, code);
        //   CreateParagraph(response, description);
        //   CreateParagraph(response, subject_name);
          
        // }
        // else {
        //   alert(response.error);
        // }
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
      const sub_id = element.getAttribute('data-id');
      const input_sub = document.querySelector('#sub-id');

      input_sub.value = sub_id;
      
    })
  
  })
}

//remove the show class in the alert body
if(alert_body)
{
  alert_body.addEventListener('click',(event)=>{
  if(event.target.className == 'alert-body show' && alert_body.classList.contains('show')){
  delete_sub.removeAttribute('data-id');
  alert_body.classList.remove('show');
  }
  })
}

if(cancel_delete)
{
  cancel_delete.addEventListener('click', ()=>{
  delete_sub.removeAttribute('data-id');
  alert_body.classList.remove('show')
  })
}
