
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


function loadVideoSite() {
    
}


function playVideo(e) {

    if (document.getElementById("my-player")) {
        return;
    }

    let video = document.createElement("video");
    video.setAttribute("id", "my-player");
    video.setAttribute("class", "video-js");
    video.setAttribute("controls", "");
    video.setAttribute("data-setup", "{}");

    let streamSource = document.createElement("source");
    streamSource.setAttribute("src", "https://rcavmedias1.akamaized.net/out/v1/c1dc7984c65d43dda32032029495a289/462c5efac1cd4f16a3b09d3b4584e64a/7dc7dbfefd7a4ba9a800d2885e73a8a2/index.m3u8");
    streamSource.setAttribute("type", "application/x-mpegURL");
    
    video.appendChild(streamSource);

    //video.setAttribute("");

    e.appendChild(video)
    let player = videojs("my-player");
    console.log("ALSF")
}










