<?php

namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemoSessionController extends AbstractController
{
    /**
     * Vue qui affiche les données de la session
     *
     * @param Request $request
     * @return void
     */
    #[Route('/demo/session', name: 'demo_session_index')]
    public function index(Request $request)
    {
        // 4eme étape: on Recupere la session
        // Ci dessous je recupere toute la session et je la stock dans $session
        $session = $request->getSession();
        // 5eme étape: on recupere l'element dans la session qui a pour clé "username"
        // Ci dessous je récupere l'element qui a pour clé "username" dans ma session
        $username = $session->get('username');
        dump($username);
        dump('toto');
        return $this->render('front/demo_session/index.html.twig', [
            'username' => $username
        ]);
    }

    /**
     * Fonction qui ajoute un element dans la session
     *
     * @return void
     */
    #[Route('/demo/session/add/{username}', name: 'demo_session_add')]
    public function add(Request $request, $username)
    {
        // 1ere étape: Récupérer la session
        $session = $request->getSession();

        // 2eme étape: Ajouter une donnée dans la session
        // Voyez la session comme un grand tableau associatif ou on va avoir des clés => valeurs associés
        // Pour ajouter une donnée dans la session, on utilise set() qui prend 2 parametre :
        // 1er param : la clé ici username
        // 2eme param : la valeur associé à la clé (du 1er param), ici toto 
        $session->set('username', $username);

        // 3eme étape: Je vais redirigier l'utilisateur sur la route de la méthode index (plus haut)
        return $this->redirectToRoute("demo_session_index");
    }
}