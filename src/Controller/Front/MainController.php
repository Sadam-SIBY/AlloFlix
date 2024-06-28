<?php

namespace App\Controller\Front;

use App\Model\Movies;
use App\Service\MessageGenerator;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du site
     *
     * @return Response
     * Ci dessous je définis la route '/' => lorsque l'utilisateur ira dans la route /, la methode home() ci dessous s'executera
     */
    #[Route('/', name: 'main_home')]
    public function home(MovieRepository $movieRepository, MessageGenerator $messageGenerator, GenreRepository $genreRepository): Response
    {
        // On veut afficher une citation random de kamelott en message flash
        $this->addFlash('success', $messageGenerator->getRandomMessage());
        // 1ere etape : On récupère les films depuis la bdd via la methode findAll() du MovieRepository
        // $movies = $movieRepository->findAll();
        $movies = $movieRepository->findAllOrderByTitleAscDql();

        // On recupere la liste des genres
        $genres = $genreRepository->findAll();

        // 2eme etape : Je retourne la vue qui est dans templates/main/home.html.twig en lui passant $movies
        return $this->render('front/main/home.html.twig', [
            // Je passe $movies à ma vue
            // a gauche = comment va se nommer la variable dans twig
            // a droite = la valeur de cette variable 
            'movies' => $movies,
            'genres' => $genres
        ]);
    }
}
