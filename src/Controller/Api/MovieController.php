<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    // Ici /api/movie c'est le endpoint, c'est la route mais dans l'univers API c'est comme ca que ca s'appelle
    // Le endpoint est concaténé avec l'url de base
    // Ca donne : http://localhost/promos/falafel/repo/oflix/public/api/movie
    // Cette methode sera accessible en methode GET
    #[Route('/api/movies', name: 'api_get_movies', methods: ['GET'])]
    public function index(MovieRepository $movieRepository)
    {
        // Je recupere tous les films
        $movies = $movieRepository->findAll();
        // La fonction json ci dessous est dans la classe parente AbstractController
        // La méthode $this->json permet de convertir un objet PHP en JSON
        // On dit qu'on serialise (serialiser => convertir un objet PHP en JSON ou en autre format)
        // doc : https://symfony.com/doc/current/controller.html#returning-json-response
        // Elle prend plusieurs parametre:
        // 1er = les données qu'on veut envoyer au format JSON
        // 2eme = status code (liste : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes)
        // 3eme = le header de la reponse 
        // 4eme = les groupes ..
        return $this->json(
            // 1er parametres = ce qu'on veut afficher
            $movies,
            // 2eme parametre : le code status
            // voir liste des code status : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            200,
            // 3eme parametre = le header
            [],
            // 4eme parametre : le/les groupe(s)
            // Les groupes permettent de définir quels propriétés de l'entité on veut afficher
            ['groups' => ['get_movie', 'get_toto']]
        );
    }

    // Ici /api/movie/{id} c'est le endpoint, c'est la route mais dans l'univers API c'est comme ca que ca s'appelle
    // Le endpoint est concaténé avec l'url de base
    // Ca donne : http://localhost/promos/falafel/repo/oflix/public/api/movie/{id}
    // Cette methode sera accessible en methode GET
    // Movie $movie = null => Si aucune film n'a pour id {id}, alors $movie sera = null
    // Cette syntaxte permet de donner une valeur par défaut à $movie, au cas ou il n'y a pas de movie qui a pour id => {id}
    // {id<\d+>} => ici je dis que j'attends un parametre id
    // Mais a quoi sert <\d+> ? Ca sert à dire qu'on s'attend à ce que le paramaetre id soit forcément un nombre positif ('d' de digit et + pour positif)
    // {id<\d+>} force l'utilisateur a ce que l'id en parametre soit un nombre positif => c'est une regex qui dit que l'id doit etre un nombre positif
    #[Route('/api/movies/{id<\d+>}', name: 'api_show_movie', methods: ['GET'])]
    public function show(Movie $movie = null)
    {
        // $movie = $movieRepository->find($id);
        // On check si le $movie n'est pas existant
        if (!$movie) {
            return $this->json(
                "Error : Film inexistant",
                // 2eme parametre : le code status
                // voir liste des code status : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
                404
            );
        }
        // Sinon, on retourne les datas du $movie qui a pour id => $id
        // $movie est deja egal à l'objet movie qui a pour id => {id} 
        return $this->json(
            // 1er parametre = ce qu'on veut afficher
            $movie,
            // 2eme parametre : le code status
            // voir liste des code status : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            200,
            // 3eme parametre = le header
            [],
            // 4eme parametre : le/les groupe(s)
            // Les groupes permettent de définir quels propriétés de l'entité on veut afficher
            ['groups' => ['get_movie']]
        );
    }

    #[Route('/api/movies/random', name: 'api_random_movies', methods: ['GET'])]
    public function random(MovieRepository $movieRepository)
    {
        // On recupere un film au hasard
        $movie = $movieRepository->getRandomMovie();
        return $this->json(
            $movie,
            200,
            [],
            ['groups' => ['get_movie']]
        );
    }
}
