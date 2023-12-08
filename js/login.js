const submit = document.querySelector("#send-btn");
const form = document.querySelector("#login-form");

console.log(form)

if (form) {
    form.addEventListener("submit", (event) => {

        event.preventDefault();
        const ajax = new XMLHttpRequest();
        const loader = document.querySelector(".loader-body");


        loader.classList.toggle("loader-body");
        loader.classList.toggle("loader-body-show");
    })

}