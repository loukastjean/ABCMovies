
function getRequestAsync(url) {
    let request = new XMLHttpRequest();
    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {

        }
    }
    request.open("GET", url, true);

    return JSON.parse(request.response);
}


function VerifyUsername(e) {
    let usernameRegex = /^[\w-]{3,15}$/;
    let username = e.value;

    let usernameValid = document.getElementById("usernameValid");

    if (usernameValid == null) {
        usernameValid = document.createElement("p");
        usernameValid.setAttribute("id", "usernameValid");
    }
    

    if (usernameRegex.test(username)) {
        usernameValid.innerHTML = "Nom d'utilisateur valide";
        manageLoginButton(true);
    }
    else {
        usernameValid.innerHTML = "Nom d'utilisateur invalide";
        manageLoginButton(false);
    }

    let usernameInput = document.getElementById("labelInputUsername");
    let fieldset = document.getElementById("loginFieldSet");

    fieldset.insertBefore(usernameValid, usernameInput);

    return usernameRegex.test(username);
}


function VerifyPassword(e) {
    // Au moins une maj, min, chiffre et charactere special avec au moins 8 characteres
    let passwordRegex = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/;
    let password = e.value;

    let passwordValid = document.getElementById("passwordValid");

    if (passwordValid == null) {
        passwordValid = document.createElement("p");
        passwordValid.setAttribute("id", "passwordValid");
    }
    

    if (passwordRegex.test(password)) {
        passwordValid.innerHTML = "Mot de passe securitaire";
        manageLoginButton(true);
    }
    else {
        passwordValid.innerHTML = "Mot de passe trop simple";
        manageLoginButton(false);
    }

    let passwordInput = document.getElementById("labelInputPassword");
    let fieldset = document.getElementById("loginFieldSet");

    fieldset.insertBefore(passwordValid, passwordInput);

    return passwordRegex.test(password);
}



function manageSubmitButton(enable) {
    let submitButton = document.getElementById("submitButton");

    if (enable) {
        submitButton.setAttribute("disabled", "false")
    }
    else {
        submitButton.setAttribute("disabled", "true")
    }
}


function loadVideoPage(e) {
    document.location.href = "https://st-jean.h25.techinfo420.ca/ABCMovies/video.php?id=" + e.getAttribute("id");
}









