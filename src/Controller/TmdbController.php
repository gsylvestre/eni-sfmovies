<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TmdbController extends AbstractController
{
    /**
     * @Route("/tmdb", name="tmdb")
     */
    public function index()
    {
        $startAtPage = 1;
        for($i = $startAtPage; $i <= ($startAtPage+10); $i++) {
            //sleep(1);
            $this->getMoviesFromTmdb($i);
        }

        return new Response("done");
    }

    private function getMoviesFromTmdb(int $page = 1)
    {
        //crée un client http, capable de faire des requêtes HTTP
        $client = HttpClient::create();

        $url = "https://api.themoviedb.org/3/discover/movie?api_key=f4cdc85408d87dd72a6b81a15f56a31c&language=en-US&sort_by=popularity.desc&include_adult=false&include_video=false&with_genres=878&page=$page";
        //déclenche notre requête à l'api de TMDB
        $response = $client->request('GET', $url);
        $content = $response->toArray();

        $em = $this->getDoctrine()->getManager();
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);

        foreach ($content['results'] as $movieData) {

            $foundExistingMovie = $movieRepo->findOneBy(['tmdbId' => $movieData['id']]);
            if ($foundExistingMovie) {
                echo "movie exists !<br>";
                continue;
            }

            $movie = new Movie();
            $movie->setTitle($movieData['original_title']);
            $movie->setPoster($movieData['poster_path']);
            $movie->setTmdbId($movieData['id']);

            $em->persist($movie);
        }

        $em->flush();
    }
}
