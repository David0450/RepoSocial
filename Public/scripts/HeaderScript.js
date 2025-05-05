let header = document.querySelector("header");
let loginButton = document.querySelector("#loginButton");
let signupButton = document.querySelector("#signupButton");
let logoContainer = document.querySelector("#headerLogoContainer");
let dropdownIcon = document.querySelector("#dropdownIcon");

logoContainer.addEventListener("click", function() {
    window.location.href = "/programacion/PFC/home";
});



if (!loginButton && !signupButton) {
    dropdownIcon.addEventListener("click", function() {
        let dropdown = document.querySelector("#dropdown");
        dropdown.classList.toggle("show");
        dropdownIcon.classList.toggle("rotate");
    });
    window.addEventListener("click", function(event) {
        if (!event.target.matches("#dropdownIcon") && !event.target.matches("#dropdownIcon *")) {
            let dropdown = document.querySelector("#dropdown");
            if (dropdown.classList.contains("show")) {
                dropdown.classList.remove("show");
                dropdownIcon.classList.remove("rotate");
            }
        }
    });
}