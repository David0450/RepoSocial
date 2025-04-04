let header = document.querySelector("header");
let loginButton = document.querySelector("#loginButton");
let signupButton = document.querySelector("#signupButton");
let logoContainer = document.querySelector("#headerLogoContainer");

loginButton.addEventListener("click", function() {
    window.location.href = "?uri=login";
});

logoContainer.addEventListener("click", function() {
    window.location.href = "?uri=home";
});