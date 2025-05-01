

// Active ou désactive le bouton de soumission du formulaire
function manageSubmitButton(enable) {
    let submitButton = document.getElementById("submitButton");
    submitButton.setAttribute("disabled", enable ? "false" : "true");
}

// Insère une catégorie de vidéos recommandées dans la page principale
async function placeRecommendedVideos(service, contentType, amountOfVideos = 8) {
    categoriesParent = document.getElementsByTagName("main");
    categoriesParentEl = categoriesParent[0];

    await createCategory(categoriesParentEl, service, contentType);

    let categoryEl = document.getElementById(`${service}-${contentType}`);

    url = `/ABCMovies/services/${service}/recommendations.php?category=${contentType}&amount=${amountOfVideos}`;
    recommendations = await fetchJSON(url);

    placeVideos(categoryEl, recommendations.slice(0, amountOfVideos), service);
}

// Effectue une recherche de vidéos et insère les résultats dans la page
async function placeSearchVideos(query, service, amountOfVideos = 24) {
    mainEl = document.getElementsByClassName("category-background")[0];

    url = `/ABCMovies/services/${service}/search.php?q=${query}&amount=${amountOfVideos}`;
    results = await fetchJSON(url);

    placeVideos(mainEl, results.slice(0, amountOfVideos), service);
}

// Affiche une liste de vidéos sur la page, à partir d’un tableau de vidéos
async function placeVideos(parentEl, videos, service) {
    videos.forEach(async video => {
        let videoInfo = await getInfo(service, "show", video["id"]);
        let episode = videoInfo["seasons"][0]["episodes"][0];
        await createMedia(parentEl, service, episode["id"], videoInfo["title"], episode["description"], episode["image"]);
    });
}

// Fait une requête HTTP GET ou POST et retourne les données JSON
async function fetchJSON(url, data) {
    if (data) {
        return await fetch(url, {
            method: "POST",
            body: new URLSearchParams(data)
        }).then(response => response.json());
    } else {
        return await fetch(url).then(response => response.json());
    }
}

// Met à jour le titre de la page en y ajoutant le titre de l’épisode
async function setPageTitle(episodeTitle) {
    titleEl = document.getElementsByTagName("title")[0];
    titleEl.textContent = episodeTitle + titleEl.textContent;
}

// Récupère les informations d’un élément (show, épisode, etc.) via un service API
async function getInfo(service, infoType, id) {
    data = await fetchJSON(`/ABCMovies/services/${service}/info.php?type=${infoType}&id=${id}`, null);
    return data;
}

// Se connecte à un service pour obtenir les jetons d’authentification
async function login(service, availability) {
    tokens = await fetchJSON(`/ABCMovies/services/${service}/login.php?availability=${availability}`, null);
    return tokens;
}

// Télécharge un épisode via un service en fournissant des jetons
async function download(service, id, tokens) {
    let commandOutput = await fetchJSON(`/ABCMovies/services/${service}/download.php?id=${id}`, tokens);
    return commandOutput;
}

// Charge une vidéo à partir de son identifiant, en met à jour la page, puis retourne le URL
async function fetchVideo(service, id) {
    let episode = await getInfo(service, "episode", id);
    setPageTitle(episode["title"]);

    let videoJs = document.getElementById("my-player");
    videoJs.setAttribute("style", "background-image: url('" + episode["image"] + "')");

    let tokens = await login(service, episode["availability"]);
    let downloadOutput = await download(service, episode["id"], tokens);

    return downloadOutput["output"];
}

// (Fonction incomplète ?) Télécharge une vidéo, probablement obsolète ou erronée
async function getVideoInfo(id) {
    let commandOutput = await fetchJSON(`/ABCMovies/services/${service}/download.php?id=${id}&tokens=${strTokens}`, null);
    return commandOutput;
}

// Ajoute une vidéo aux liked (changement d’icône + changement BD)
async function addLiked(heartEl, service, id) {
    let commandOutput = await fetchJSON(`/ABCMovies/like.php?id=${id}&service=${service}`, null);

    if (!commandOutput["success"]) return;

    heartEl.setAttribute("src", "/ABCMovies/images/heart.svg");
    heartEl.setAttribute("onclick", `removeLiked(this,'${service}','${id}')`);
}

// Retire une vidéo des liked
async function removeLiked(heartEl, service, id) {
    let commandOutput = await fetchJSON(`/ABCMovies/like.php?id=${id}&service=${service}&remove`, null);

    if (!commandOutput["success"]) return;

    heartEl.setAttribute("src", "/ABCMovies/images/no-heart.svg");
    heartEl.setAttribute("onclick", `addLiked(this,'${service}','${id}')`);
}

// Vérifie si une vidéo est likée et met à jour l’icône en conséquence
async function verifyLiked(heartEl, service, id) {
    let commandOutput = await fetchJSON(`/ABCMovies/like.php?id=${id}&service=${service}&verify`, null);

    if (!commandOutput["liked"]) {
        heartEl.setAttribute("src", "/ABCMovies/images/no-heart.svg");
        heartEl.setAttribute("onclick", `addLiked(this,'${service}','${id}')`);
        return;
    }

    heartEl.setAttribute("src", "/ABCMovies/images/heart.svg");
    heartEl.setAttribute("onclick", `removeLiked(this,'${service}','${id}')`);
}

// Crée dynamiquement une catégorie de vidéos (titre + conteneur)
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

// Crée dynamiquement une carte de média à afficher dans une catégorie
async function createMedia(parentEl, service, id, title, description, backgroundUrl) {
    let mediaGroupEl = document.createElement("div");
    mediaGroupEl.setAttribute("class", "media-group");

    let mediaLinkEl = document.createElement("div");
    mediaLinkEl.setAttribute("class", "picture-related");
    backgroundUrl = backgroundUrl.replace("_Size_", "384");
    mediaLinkEl.setAttribute("style", `background-image: url('${backgroundUrl}')`);

    let likedEl = document.createElement("div");
    likedEl.setAttribute("class", "liked");

    let heartEl = document.createElement("img");
    heartEl.setAttribute("class", "heart");
    verifyLiked(heartEl, service, id); // Ajoute le bon état (liké ou non)

    let mediaTextBackgroundEl = document.createElement("div");
    mediaTextBackgroundEl.setAttribute("class", "media-text-background");

    let descriptionEl = document.createElement("a");
    descriptionEl.setAttribute("class", "media-text-description");
    descriptionEl.setAttribute("href", `/ABCMovies/video.php?service=${service}&id=${id}`);
    descriptionEl.textContent = description;

    let titleEl = document.createElement("a");
    titleEl.setAttribute("class", "media-text-title");
    titleEl.setAttribute("href", `/ABCMovies/video.php?service=${service}&id=${id}`);
    titleEl.textContent = title;

    // Construction de la hiérarchie des éléments
    likedEl.appendChild(heartEl);
    mediaTextBackgroundEl.appendChild(descriptionEl);
    mediaTextBackgroundEl.appendChild(likedEl);
    mediaLinkEl.appendChild(mediaTextBackgroundEl);
    mediaGroupEl.appendChild(mediaLinkEl);
    mediaGroupEl.appendChild(titleEl);
    parentEl.appendChild(mediaGroupEl);
}
