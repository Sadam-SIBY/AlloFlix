<?php
// src/Service/FavoritesManager.php
namespace App\Service;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;

class FavoritesManager
{
    private $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    /**
     * Fonction qui ajoute un film dans la session
     *
     * @return string
     */
    public function add(int $id, Movie $movie)
    {
        // Je recupere la session
        $session = $this->requestStack->getSession();
        // Je recupere favorites de la session, sous forme de tableau 
        $favorites = $session->get('favorites', []);
        // On va ajouter dans ce tableau le $movie ajouté à l'index $id, comme ca on est sûr qu'il n'y ait pas de doublons
        // Ainsi, je n'aurais pas 2 fois le meme film dans mes favoris
        $favorites[$id] = $movie;
        // Je mets à jour $favorites dans la session
        $session->set('favorites', $favorites);
    }
}