<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Service\FavoritesManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Gère les favoris
 */
class FavoritesController extends AbstractController
{
    /**
     * Affiche les films favoris de la session
     *
     * $request => sert à avoir des informations sur la requête
     * @return Response
     */
    #[Route('/favorites/list', name: 'favorites_list')]
    public function list(Request $request): Response
    {
        // Je recupere les données de la session grace a $request->getSession()
        $session = $request->getSession();
        // Je recupere la valeur associés à la clé 'favorites' dans ma session
        // $session->set('favorites', ['toto', 'tata']);
        $favorites = $session->get('favorites');

        // dd($favorites);
        return $this->render('front/favorites/list.html.twig', [
            // Je passe la liste des favoris à ma vue
            'favorites' => $favorites
        ]);
    }

    /**
     * Ajoute un film dans la liste des favoris
     *
     * @return void
     */
    #[Route('/favorites/add/{id}', name: 'favorites_add')]
    public function add(Request $request, Movie $movie, FavoritesManager $favoritesManager)
    {
        // Je recupere l'id du $movie
        $id = $movie->getId();

        // $session->remove('favorites');
        $favoritesManager->add($id, $movie);
        // On redirige vers la liste des films
        // J'envoie un message flash qui dit que le film a bien été ajouté aux favoris
        $this->addFlash(
            'success',
            'Le film '.$movie->getTitle().' a bien été ajouté dans les favoris !'
        );
        return $this->redirectToRoute('favorites_list');
    }

    /**
     * Fonction qui vide la liste des favoris
     *
     * @return void
     */
    #[Route('/favorites/clear', name: 'favorites_clear')]
    public function clear(Request $request)
    {
        // Je recupere les données de la session grace a $request->getSession()
        $session = $request->getSession();
        // Je vide l'element qui a pour nom de clé 'favorites' (pour vider les favoris)
        $session->remove('favorites');
        // Je redirige sur la liste des films (vide maintenant)
        return $this->redirectToRoute('favorites_list');
    }

     /**
     * Fonction qui supprime un film de la liste des favoris
     *
     * @return void
     */
    #[Route('/favorites/remove/{id}', name: 'favorites_remove')]
    public function remove(Request $request, $id)
    {
        // Je recupere les données de la session grace a $request->getSession()
        $session = $request->getSession();
        // Je recupere favorites de la session, sous forme de tableau 
        $favorites = $session->get('favorites', []);
        // J'enleve l'element qui a pour index $id
        // unset : https://stackoverflow.com/questions/369602/deleting-an-element-from-an-array-in-php
        unset($favorites[$id]);
        // Je mets a jour favorites avec le movie en moins (qu'on a supprimé)
        $session->set('favorites', $favorites);
        // Je redirige sur la liste des films (vide maintenant)
        return $this->redirectToRoute('favorites_list');
    }
}
