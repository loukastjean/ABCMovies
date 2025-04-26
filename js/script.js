
// Serie: Stat
// Film: Les Cyclades


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

async function placeRecommendedVideos(service, contentType, amountOfVideos = 8) {

    categoriesParent = document.getElementsByTagName("main");
    categoriesParentEl = categoriesParent[0];

    await createCategory(categoriesParentEl, service, contentType);

    let categoryEl = document.getElementById(`${service}-${contentType}`);

    url = `https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/recommendations.php?category=${contentType}&amount=${amountOfVideos}`;
    
    recommendations = await fetchJSON(url);

    placeVideos(categoryEl, recommendations.slice(0, amountOfVideos), service);
}

async function placeSearchVideos(query, service, amountOfVideos = 24) {

    mainEl = document.getElementsByClassName("category-background");
    mainEl = mainEl[0];

    url = `https://st-jean.h25.techinfo420.ca/ABCMovies/services/${service}/search.php?q=${query}&amount=${amountOfVideos}`;
    
    results = await fetchJSON(url);

    placeVideos(mainEl, results.slice(0, amountOfVideos), service);
}

async function placeVideos(parentEl, videos, service) {

    videos.forEach(async video => {
        
        let videoInfo = await getInfo(service, "show", video["id"]);
        
        let episode = videoInfo["seasons"][0]["episodes"][0]; // Pour l'instant, vraiment mauvaise maniere de faire :)

        await createMedia(parentEl, service, episode["id"], videoInfo["title"], episode["description"], episode["image"]);
    });
}

async function fetchJSON(url, data) {
    if (data) {
        // POST
        return await fetch(url, {
            method: "POST",
            // NON NON NON NON POURQUOIIIIIIIIIIIIIII
            body: new URLSearchParams(data)
        }).then(response => response.json());

    } else {
        return await fetch(url).then(response => response.json());
    }
}

async function setPageTitle(episodeTitle) {
    titleEl = document.getElementsByTagName("title")[0];

    titleEl.textContent = episodeTitle + titleEl.textContent;
}

async function getInfo(service, infoType, id) {
    data = await fetchJSON(`services/${service}/info.php?type=${infoType}&id=${id}`, null);
    return data;
}

async function login(service, availability) {
    tokens = await fetchJSON(`services/${service}/login.php?availability=${availability}`, null);
    return tokens;
}

async function download(service, id, tokens) {
    let commandOutput = await fetchJSON(`services/${service}/download.php?id=${id}`, tokens);
    return commandOutput;
}


async function fetchVideo(service, id) {
    let episode = await getInfo(service, "episode", id);
    console.log(JSON.stringify(episode));
    console.log("Got the episode info");

    setPageTitle(episode["title"]);
    console.log("Finished editing page using episode info!");

    let tokens = await login(service, episode["availability"]);
    console.log("Logged in and got the request headers!");

    console.log(episode);

    let downloadOutput = await download(service, episode["id"], tokens);
    console.log(downloadOutput);

    let playlistPath = downloadOutput["output"];

    return playlistPath;
}

async function getVideoInfo(id) {

    let commandOutput = await fetchJSON(`services/${service}/download.php?id=${id}&tokens=${strTokens}`, null);
    return commandOutput;
}

async function addLiked(heartEl, service, id) {

    let commandOutput = await fetchJSON(`like.php?id=${id}&service=${service}`, null);

    if (!commandOutput["success"]) {
        return;
    }

    heartEl.setAttribute("src", "images/heart.svg");
    heartEl.setAttribute("onclick", `removeLiked(this,'${service}','${id}')`);
}

async function removeLiked(heartEl, service, id) {

    let commandOutput = await fetchJSON(`like.php?id=${id}&service=${service}&remove`, null);

    if (!commandOutput["success"]) {
        return;
    }

    heartEl.setAttribute("src", "images/no-heart.svg");
    heartEl.setAttribute("onclick", `addLiked(this,'${service}','${id}')`);
}

async function verifyLiked(heartEl, service, id) {

    let commandOutput = await fetchJSON(`like.php?id=${id}&service=${service}&verify`, null);

    if (!commandOutput["liked"]) {
        console.log(commandOutput)
        heartEl.setAttribute("src", "images/no-heart.svg");
        heartEl.setAttribute("onclick", `addLiked(this,'${service}','${id}')`);
        return;
    }

    heartEl.setAttribute("src", "images/heart.svg");
    heartEl.setAttribute("onclick", `removeLiked(this,'${service}','${id}')`);
}

async function createCategory(parentEl, service, contentType) {

    let categoryNameEl = document.createElement("span");

    categoryNameEl.setAttribute("class", `category-name`);
    categoryNameEl.textContent = `${contentType} ${service}`;

    parentEl.appendChild(categoryNameEl);

    let categoryEl = document.createElement("div");

    categoryEl.setAttribute("class", "category-background");
    categoryEl.setAttribute("id", `${service}-${contentType}`);

    parentEl.appendChild(categoryEl);
}

async function createMedia(parentEl, service, id, title, description, backgroundUrl) {

    let mediaGroupEl = document.createElement("div");

    mediaGroupEl.setAttribute("class", "media-group");

    let mediaLinkEl = document.createElement("div");

    mediaLinkEl.setAttribute("class", "picture-related")

    backgroundUrl = backgroundUrl.replace("_Size_", "384");
    mediaLinkEl.setAttribute("style", `background: url('${backgroundUrl}')`)

    let likedEl = document.createElement("div");

    likedEl.setAttribute("class", "liked");

    let heartEl = document.createElement("img");

    heartEl.setAttribute("class", "heart");

    await verifyLiked(heartEl, service, id);

    let mediaTextBackgroundEl = document.createElement("div");

    mediaTextBackgroundEl.setAttribute("class", "media-text-background");

    let descriptionEl = document.createElement("span");

    descriptionEl.setAttribute("class", "media-text-description");
    descriptionEl.textContent = description;

    let titleEl = document.createElement("a");

    titleEl.setAttribute("class", "media-text-title");
    titleEl.setAttribute("href", `./video.php?service=${service}&id=${id}`)
    titleEl.textContent = title;

    likedEl.appendChild(heartEl);

    mediaTextBackgroundEl.appendChild(descriptionEl);
    mediaTextBackgroundEl.appendChild(likedEl);
    
    mediaLinkEl.appendChild(mediaTextBackgroundEl);

    mediaGroupEl.appendChild(mediaLinkEl);
    mediaGroupEl.appendChild(titleEl);

    parentEl.appendChild(mediaGroupEl);
}


