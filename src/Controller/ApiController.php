<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/1/movie/search", methods={"GET"})
     */
    public function searchMovies(Request $request)
    {
        //récupère les mots-clefs dans la request
        $kw = $request->query->get('kw');

        //récupère le repo de Movie
        $movieRepo = $this->getDoctrine()->getRepository(Movie::class);

        //appelle une méthode qu'on a codé nous-même dans le repo
        $movies = $movieRepo->findMoviesByKeywords($kw);

        //renvoie la réponse
        return $this->json([
            "results" => $movies,
            "kw" => $kw,
            "status" => "ok"
        ]);
    }
}