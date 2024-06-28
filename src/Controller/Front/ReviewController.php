<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    // le debut de la methode add() codé plus bas fait exactement la meme chose sauf que ci dessous on recupere le $movie grace a la methode find du MovieRepository
    // public function add($id, MovieRepository $movieRepository): Response
    // {
    //     // On recuper le film qu'on veut critiquer
    //     $movie = $movieRepository->find($id);
    //     dd($movie);
    // }

    /**
     * Ajout d'une critique sur un film
     *
     * @return Response
     */
    #[Route('/review/{id}/add', name: 'app_review_add')]
    public function add(Movie $movie, Request $request, EntityManagerInterface $entityManager): Response
    {
        // On créer l'instance de Review
        $review = new Review();
        // On construit le formulaire qui va construire notre $objet $review
        $form = $this->createForm(ReviewType::class, $review);
        // On donne les infos de la requete au formulaire pour savoir si il a été soumis ou non
        $form->handleRequest($request);
        // On check si le formulaire a été soumis et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($review);
            // J'associe ma critique $review au film $movie
            $review->setMovie($movie);
            // On persist $review (enregistre)
            $entityManager->persist($review);
            // On envoie ca en bdd
            $entityManager->flush();
            // dd($review);
            // Lorsque le formulaire sera soumit, je vais rediriger l'utilisateur vers la page de detail du film en passant l'id du film ($movie->getTitle())
            return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()]);
        }
        return $this->render('front/review/add.html.twig', [
            'form' => $form,
            'movie' => $movie
        ]);
    }
}