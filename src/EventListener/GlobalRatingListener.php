<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use App\Entity\Review;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Review::class)]
final class GlobalRatingListener
{
    /**
     * Méthode qui s'execute au postPersist d'une Review
     */
    public function postPersist(Review $review, PostPersistEventArgs $event): void
    {
        // 1ere etape : on recupere le movie via $review
        $movie = $review->getMovie();
        // 2eme etape : on calcule la note global de toutes les reviews du film
        // Je créer une variable $allNotes egal a 0
        // Cette variable sera egal à la somme de toutes les notes d'un film
        $allNotes = 0;
        // Je boucle sur toutes les reviews d'un film
        foreach ($movie->getReviews() as $review) {
            $allNotes = $allNotes + $review->getRating();
        }
        // Pour une moyenne on divise le nombre total par le nombre de note
        $average = $allNotes / count($movie->getReviews());
        // $average c'est la note moyenne d'un film
        // dd("Note moyenne = ".$average);
        // On va redéfinir la valeur de rating de l'objet $movie via le setter setRating
        $movie->setRating($average);
        // Pour récupérer l'entityManager : https://symfony.com/doc/current/doctrine/events.html#doctrine-lifecycle-listeners
        $entityManager = $event->getObjectManager();
        // Et grace a l'entityManager, je peux flush
        $entityManager->flush();
    }
}
