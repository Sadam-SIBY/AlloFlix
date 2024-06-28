<?php

namespace App\Controller\Back;

use App\Model\Movies;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Page d'accueil du backoffice
     *
     * @return Response
     * Ci dessous je définis la route '/' => lorsque l'utilisateur ira dans la route /, la methode home() ci dessous s'executera
     */
    #[Route('/back', name: 'main_back')]
    public function home(MovieRepository $movieRepository): Response
    {
        return $this->render('back/main/home.html.twig');
    }
}