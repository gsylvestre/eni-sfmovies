console.log("couc");

//déclenché quand on tape ou quand on clique sur le bouton
function handleChange(evt){
    //récupère ce qui est écrit dans le champs
    let keywords = searchInput.value;
    console.log(keywords);
    if (keywords.length <3){
        resultsDiv.innerHTML = "";
        return;
    }

    //lance une requête ajax au serveur !
    let url = rootUrl + "/api/1/movie/search";
    axios.get(url, {
        params: {
            //envoie nos mots-clefs avec la requête
            //on crée un good ol' paramètre d'URL s'appelant kw
            kw: keywords
        }
    })
    //ici, on reçoit les résultats de l'appel !
    .then(function(response){
        resultsDiv.innerHTML = "";
        console.log(response);
        for(let i = 0; i < response.data.results.length; i++){
            //juste pour simplifier le nom de la variable
            let movie = response.data.results[i];
            //ajoute dans le HTML les titres des films
            resultsDiv.innerHTML += '<article>' + movie.title + '</article>';
        }
    });
}

//cible une seule fois notre div qui contiendra les résultats
const resultsDiv = document.getElementById("results");

//cible et met sous écoute le champs de recherche
const searchInput = document.getElementById("search-input");
searchInput.addEventListener("keyup", handleChange);

//cible et met sous écoute le bouton
const searchButton = document.getElementById("search-button");
searchButton.addEventListener("click", handleChange);