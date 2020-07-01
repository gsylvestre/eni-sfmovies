console.log("couc");

//affiche l'iframe youtube (déclenchée au clic sur un <article> de film)
function showTrailer(trailerId) {
    trainerContainer.innerHTML = `
        <iframe width="560" height="315" src="https://www.youtube.com/embed/${trailerId}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope;picture-in-picture" allowfullscreen></iframe>
    `;
}

//déclenché quand on tape ou quand on clique sur le bouton
function handleChange(evt){
    //récupère ce qui est écrit dans le champs
    let keywords = searchInput.value;
    console.log(keywords);
    if (keywords.length <3){
        //vide les résultats précédents
        trainerContainer.innerHTML = "";
        resultsDiv.innerHTML = "";
        //ne fait pas la requête ajax du coup
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
            //ajoute dans le HTML les titres des films et les posters
            resultsDiv.innerHTML +=
                //attention, ce sont des backticks ` (accent grave) ci-dessous, template strings !
                `<article data-trailer-id="${movie.trailerId}">
                    <img src="https://image.tmdb.org/t/p/w200${movie.poster}" alt="${movie.title} poster">
                    <div>${movie.title}</div>
                </article>`;
        }

        //met sous écoute du click chacun des films
        resultsDiv.querySelectorAll("article").forEach(function(videoArticle){
            videoArticle.addEventListener("click", function(){
                let trailerId = videoArticle.dataset.trailerId;
                showTrailer(trailerId);
            })
        });
    });
}

//cible une seule fois notre div qui contiendra les résultats
const resultsDiv = document.getElementById("results");
//et l'éventuel bande annonce...
const trainerContainer = document.getElementById("trailer-container");

//cible et met sous écoute le champs de recherche
const searchInput = document.getElementById("search-input");
//input est un événement qui se déclenche quand la valeur change
searchInput.addEventListener("input", handleChange);

//cible et met sous écoute le bouton
const searchButton = document.getElementById("search-button");
searchButton.addEventListener("click", handleChange);