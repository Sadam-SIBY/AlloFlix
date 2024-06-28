<?php

namespace App\Controller\Front;

use App\Entity\Genre;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/genre')]
class GenreController extends AbstractController
{
    /**
     * Affiche tous les films d'un genre donnée
     *
     * @return Response
     */
    #[Route('/{id}', name: 'movie_by_genre')]
    public function movieByGenre(Genre $genre = null)
    {
        // On va récupérer la liste de tous les films du genre donnée en parametre
        $movies = $genre->getMovies();
        return $this->render('front/genre/index.html.twig', [
            'movies' => $movies,
            'genre' => $genre
        ]);
    }
}