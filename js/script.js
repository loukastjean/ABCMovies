
function getRequestAsync(url) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);
    xhr.onload = (e) => {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          return xhr.responseText;
        } else {
          console.error(xhr.statusText);
        }
      }
    };
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

function PlaceVideos() {

    url = "https://st-jean.h25.techinfo420.ca/ABCMovies/services/toutv/recommendations.php?category=";
    
    series = getRequestAsync(url + "serie");
    series = JSON.parse(series);

    pictureElements = document.getElementsByClassName("picture-related");

    for (let i = 0; i < 4; i++) {
        pictureElements[i].setAttribute("href", "https://st-jean.h25.techinfo420.ca/ABCMovies/video.php?service=toutv&id=" + series[i]["seasons"][0]["episodes"][0]["id"])
    }

    pictureElements.forEach(capsule => {
        capsule.setAttribute("href", "https://st-jean.h25.techinfo420.ca/ABCMovies/")
    });


}

function fetchJSON(url, data) {
    if (data) {
        // POST
        return fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
        }).then(response => response.json());

    } else {
        return fetch(url).then(response => response.json());
    }
}

async function editPage(episodeTitle) {
    titleEl = document.getElementsByTagName("title")[0];

    titleEl.textContent = episodeTitle + titleEl.textContent;
}

async function episodeInfo() {
    data = await fetchJSON("https://st-jean.h25.techinfo420.ca/ABCMovies/services/<?php echo $service ?>/info.php?type=episode&id=<?php echo $id ?>", null);

    return data;

}

async function login(availability) {
    tokens = await fetchJSON(`https://st-jean.h25.techinfo420.ca/ABCMovies/services/<?php echo $service ?>/login.php?availability=${availability}`, null);

    return tokens;
}

async function download(id, tokens) {
    let strTokens = JSON.stringify(tokens);
    let commandOutput = await fetchJSON(`https://st-jean.h25.techinfo420.ca/ABCMovies/services/<?php echo $service ?>/download.php?id=${id}&tokens=${strTokens}`, null);
    return commandOutput;
}


async function fetchVideo() {
    let episode = await episodeInfo();
    console.log(JSON.stringify(episode));
    console.log("Got the episode info");

    editPage(episode["title"]);
    console.log("Finished editing page using episode info!");

    let tokens = await login(episode["availability"]);
    console.log("Logged in and got the request headers!");

    let downloadOutput = await download(episode["id"], tokens);

    let playlistPath = downloadOutput["output"];
    return playlistPath;
}





