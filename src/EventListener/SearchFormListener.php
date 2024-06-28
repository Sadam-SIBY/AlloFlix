<?php

namespace App\EventListener;

use Twig\Environment;
use App\Form\SearchMovieType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchFormListener
{
    private $formFactory;
    private $twig;

    public function __construct(FormFactoryInterface $formFactory, Environment $twig)
    {
        // Pour avoir acces aux methode de Form on peut soit construire comme ca via le construct
        // OU (methode un peu + bourin) on peut extends AbstractController dans la classe (ne le faites pas, c'est pas propre meme si ca marche)
        $this->formFactory = $formFactory;
        $this->twig = $twig; // Pour ajouter des variables global à Twig(accessible partout dans mes vues Twig)
    }

    #[AsEventListener(event: KernelEvents::CONTROLLER)]
    public function onKernelController(ControllerEvent $event): void
    {
        // On construit le form SearchMovieType
        $form = $this->formFactory->create(SearchMovieType::class);
        // Je creer une globale dans l'envrionnement Twig qui sera egal a ce Form
        // Ici on utilise createView car on est pas dans un controlller et surtout car on utilise pas la methode render()
        // La methode render() fait deja createView() automatiquement, mais ici on utilise pas render donc on utilise createView()
        $this->twig->addGlobal("searchForm", $form->createView());
    }
}
