<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Model\Movies;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// AbstractController, c'est la classe parente de tous mes controller sur symfony

class MovieController extends AbstractController
{
    /**
     * Page Résultats d'une recherche
     *
     * @return Response
     */
    // Ci dessous, on apelle ca une annotation
    #[Route('/movie/list', name: 'movie_list')]
    public function list(): Response
    {
        return $this->render('front/movie/list.html.twig');
    }

    /**
     * Page de détail d'un film
     *
     * @param string $slug L'id du film
     * @return Response
     */
    #[Route('/movie/show/{slug}', name:'movie_show', methods:['GET'])]
    public function show(Movie $movie, MovieRepository $movieRepository)
    {
        // ETAPE 1 : On recupere l'id via la route {id} => $id dans la methode
        // Exemple : si la route = /movie/show/1 alors $id = 1
        // Pour recuperer un id via le controller : https://symfony.com/doc/current/routing.html#matching-http-methods

        // ETAPE 2 : on stock dans $movie l'objet movie qui a pour id $id
        // doc : https://symfony.com/doc/current/doctrine.html#fetching-objects-from-the-database
        // $movie = $movieRepository->find($id);
        // dump($movie);

        // ETAPE 3 : On retourne la vue en lui passant les données du film qu'on veut afficher (ici $movie)
        return $this->render('front/movie/show.html.twig', [
            // Je passe $movie à ma vue
            'movie' => $movie
        ]);

        // return $this->render('front/movie/show.html.twig', compact('movie'));
    }

       /**
     * Fonction qui se lance quand on cherche un film
     */
    #[Route('/movie/search', name:'movie_search', methods:['POST'])]
    public function search(Request $request, MovieRepository $movieRepository)
    {
        // On check si la requête effectué est une requete en methode POST
        if ($request->isMethod('POST')) {
            // $id = l'id du film
            $id = $request->request->all()["search_movie"]["movie"];
            $movie = $movieRepository->find($id);
            // dd($movie);
            // On redirige l'utilisateur vers la page de detail du film
            return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()]);
        }
        // On recupere le contenu de la requête
        // Dans l'URL le slug du film sera affiché  
    }
}