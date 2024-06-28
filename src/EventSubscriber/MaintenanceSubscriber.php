<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MaintenanceSubscriber implements EventSubscriberInterface
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
    // Il se passe quoi si tl event se declenche
    public function onKernelResponse(ResponseEvent $event)
    {
        // Pour checker si il y a bien une maintenance de prévu, je check si "MAINTENANCE" a bien une valeur dans le .env
        // Etant donnée que dans services.yaml app.maintenance c'est le pont entre la variable d'environnement MAINTENANCE et mon app symfony
        if (!$this->params->get('app.maintenance')) {
            // On rentre dans le if si il n'y a pas de maintenance de prévue
            return null;
        }
        // Je créer une variable qui est egal a la date de maintenance prévue
        $date_maintenance = $this->params->get('app.maintenance');
        // Je stock dans $response le contenu du html de la reponse
        $response = $event->getResponse();
        // dump($response);
        // Je créer une variable qui va s'apeller $newContent
        // $newContent sera le nouveau contenu HTML de la reponse 
        // str_replace() prend en 1er param : ce que je cherche; 2eme param : par quoi je vais le remplacer ?; 3eme param : où ?
        $newContent = str_replace('<body>', '<body><div class="alert alert-danger">Maintenance prévue '.$date_maintenance.'</div>', $response->getContent());
        // Je remplace le contenu de base par mon nouveau contenu html $newContent
        $response->setContent($newContent);
    }

    // On se met sur ecoute de quel events ?
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
