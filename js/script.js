


function verifyUsername(e) {
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


function verifyPassword(e) {
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

function aaa() {
    console.log("This is working");
}

async function placeVideos(service, contentType) {

    categoriesParent = document.getElementsByTagName("main");
    categoriesParent = categoriesParent[0];
    categoryName = `${contentType} ${service}`;

    console.log(categoriesParent);

    await createCategory(categoriesParent, categoryName);



    url = `https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/recommendations.php?category=`;
    
    content = getRequestAsync(url + contentType);
    content = JSON.parse(content);

    for (let i = 0; i < 4; i++) {
        let episode = series[i]["seasons"][0]["episodes"][0]; // Pour l'instant, vraiment mauvaise maniere de faire :)
        await createMedia(categoryEl, episode["id"], episode["title"], episode["description"], episode["image"]);
    }
}

async function fetchJSON(url, data) {
    if (data) {
        // POST
        return await fetch(url, {
            method: "POST",
            body: JSON.stringify(data),
        }).then(response => response.json());

    } else {
        return await fetch(url).then(response => response.json());
    }
}

async function setPageTitle(episodeTitle) {
    titleEl = document.getElementsByTagName("title")[0];

    titleEl.textContent = episodeTitle + titleEl.textContent;
}

async function episodeInfo(service, id) {
    data = await fetchJSON(`https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/info.php?type=episode&id=${id}`, null);

    return data;

}

async function login(service, availability) {
    tokens = await fetchJSON(`https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/login.php?availability=${availability}`, null);

    return tokens;
}

async function download(service, id, tokens) {
    let strTokens = JSON.stringify(tokens);
    let commandOutput = await fetchJSON(`https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/download.php?id=${id}&tokens=${strTokens}`, null);
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

async function getVideoInfo(id) {

    let commandOutput = await fetchJSON(`https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/download.php?id=${id}&tokens=${strTokens}`, null);
    return commandOutput;

}


async function createCategory(parentEl, categoryName) {

    let categoryNameEl = document.createElement("span");

    categoryNameEl.setAttribute("class", "category-name");
    categoryNameEl.textContent = categoryName;

    let categoryEl = document.createElement("div");

    categoryEl.setAttribute("class", "category-background");

    parentEl.AppendChild(categoryNameEl);
    parentEl.AppendChild(categoryEl);
}

async function createMedia(parentEl, id, title, description, backgroundUrl) {

    let mediaGroupEl = document.createElement("div");

    mediaGroupEl.setAttribute("class", "media-group");

    let mediaLinkEl = document.createElement("a");

    mediaLinkEl.setAttribute("href", `../video.php?id=${id}`) // OK, DEMANDER A CLAUDE POUR LIENS QUI SONT GERNE DYNAMIQUE PAR RAPPORT A SA POSITION
    mediaLinkEl.setAttribute("class", "picture-related")
    mediaLinkEl.setAttribute("style", `background: url('${backgroundUrl}')`)

    let likedEl = document.createElement("div");

    likedEl.setAttribute("class", "liked");

    let heartEl = document.createElement("div");

    heartEl.setAttribute("class", "heart");

    let mediaTextBackgroundEl = document.createElement("div");

    mediaTextBackgroundEl.setAttribute("class", "media-text-background");

    let descriptionEl = document.createElement("span");

    descriptionEl.setAttribute("class", "lorem-ipsum-dolor");
    descriptionEl.textContent = description;

    let titleEl = document.createElement("span");

    titleEl.setAttribute("class", "lorem-ipsum");
    titleEl.textContent = title;

    likedEl.AppendChild(heartEl);

    mediaLinkEl.AppendChild(likedEl);
    mediaLinkEl.AppendChild(mediaTextBackgroundEl);
    mediaLinkEl.AppendChild(descriptionEl);

    mediaGroupEl.AppendChild(mediaLinkEl);
    mediaGroupEl.AppendChild(titleEl);

    parentEl.AppendChild(mediaGroupEl);
}

