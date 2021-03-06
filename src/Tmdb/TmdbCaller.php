<?php

namespace App\Tmdb;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpClient\HttpClient;

class TmdbCaller
{
    //pour que ce soit facile à changer
    //devrait plutôt être dans le fichier .env.local à vrai dire
    const API_KEY = "f4cdc85408d87dd72a6b81a15f56a31c";

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getMoviesFromTmdb(int $page = 1)
    {
        //crée un client http, capable de faire des requêtes HTTP
        $client = HttpClient::create();

        //on ne peut pas utiliser l'interpolation de variable avec les constantes
        $url = "https://api.themoviedb.org/3/discover/movie?api_key=".self::API_KEY."&language=en-US&sort_by=popularity.desc&include_adult=false&include_video=false&with_genres=878&page=$page";
        //déclenche notre requête à l'api de TMDB
        $response = $client->request('GET', $url);
        //convertie la réponse json (texte) en tableau
        $content = $response->toArray();

        $em = $this->doctrine->getManager();
        $movieRepo = $this->doctrine->getRepository(Movie::class);

        foreach ($content['results'] as $movieData) {
            //on cherche le film dans la bdd pour éviter les doublons
            $foundExistingMovie = $movieRepo->findOneBy(['tmdbId' => $movieData['id']]);
            if ($foundExistingMovie) {
                echo "movie exists !<br>";
                continue;
            }

            //crée un nouveau film et l'hydrate avec les données reçues
            $movie = new Movie();
            $movie->setTitle($movieData['original_title']);
            $movie->setPoster($movieData['poster_path']);
            $movie->setTmdbId($movieData['id']);

            //on doit faire une autre requête pour récupérer les vidéos
            $trailerId = $this->getTrailer($movieData['id']);
            $movie->setTrailerId($trailerId);

            //sauvegarde chaque film
            $em->persist($movie);
        }

        //on exécute une seule fois, à la fin
        $em->flush();
    }

    //recupère la bande-annonce youtube en fonction de l'id du video de tmdb
    public function getTrailer($videoId)
    {
        $url = "https://api.themoviedb.org/3/movie/$videoId?api_key=".self::API_KEY."&append_to_response=videos";
        $client = HttpClient::create();

        //déclenche notre requête à l'api de TMDB
        $response = $client->request('GET', $url);
        $movieDetails = $response->toArray();
        if (!empty($movieDetails['videos'])){
            //on boucle sur toutes les vidéos de ce film
            foreach($movieDetails['videos']['results'] as $video){
                //on cherche un trailer sur youtube
                if ($video['type'] === "Trailer" && $video['site'] === "YouTube"){
                    //si on le trouve, on retourne l'id sur youtube
                    return $video['key'];
                }
            }
        }

        //si on n'a pas trouvé, on arrive ici, et on retourne null
        return null;
    }
}