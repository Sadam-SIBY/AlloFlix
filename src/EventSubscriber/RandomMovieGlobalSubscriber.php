<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class RandomMovieGlobalSubscriber implements EventSubscriberInterface
{
    private $movieRepository;
    private $twig;
    public function __construct(MovieRepository $movieRepository, Environment $twig)
    {
        $this->movieRepository = $movieRepository;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // On recupere un Movie au hasard et on le stock dans $movieRand
        $movieRand = $this->movieRepository->getRandomMovie();
        // Je rajoute une global dans mon environnement Twig
        // Global = variable accessible partout dans Twig
        // Ici je rajoute une global qui s'apellera randomMovie dans Twig et qui a pour valeur $randomMovie
        $this->twig->addGlobal("movieRand", $movieRand);
    }
    public function onKernelResponse(ResponseEvent $event): void
    {
        // dump("kernel.response est déclenché car la response a finit de se charger");
    }

    /**
     * Dans cette méthode, je me mets sur écoute de tous les evenements que je veux écouter
     *
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // Je me mets sur écoute de kernel.controller, au déclenchement de kernel.controller c'est la méthode onKernelController() qui se déclenche
            KernelEvents::CONTROLLER => 'onKernelController',
            // Je me mets sur écoute de kernel.response, au déclenchement de kernel.response c'est la méthode onKernelResponse() qui se déclenche
            // 'kernel.response' => 'onKernelResponse',
            // PAREIL QUE 
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
