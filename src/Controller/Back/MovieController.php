<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Toute les routes de cette classe vont commencer par '/back/movie'
 */
#[Route('/back/movie')]
class MovieController extends AbstractController
{
    /**
     * Affiche tous les films dans le backoffice
     * Ici la route sera '/back/movie' car toutes les routes de base de cette 
     * classe commenceront par '/back/movie'
     *
     * @return Response
     */
    #[Route('/', name: 'browse_movie')]
    public function browse(MovieRepository $movieRepository): Response
    {
        // 1ere etape je recupere tous les films
        $movies = $movieRepository->findAll();
        // Je passe tous les films à ma vue
        return $this->render('back/movie/browse.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Affiche un film via son id dans le backoffice
     * Ici la route sera '/back/movie' car toutes les routes de base de cette 
     * classe commenceront par '/back/movie'
     *
     * @return Response
     */
    #[Route('/show/{id}', name: 'show_movie')]
    public function show(MovieRepository $movieRepository, $id): Response
    {
        // 1ere etape je recupere le film via son $id
        $movie = $movieRepository->find($id);
        // Je passe tous les films à ma vue
        return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * Créer un film via un formulaire dans le backoffice
     * Ici la route sera '/back/movie/create' car toutes les routes de base de cette 
     * classe commenceront par '/back/movie'
     *
     * @return Response
     */
    #[Route('/create', name: 'create_movie')]
    public function create(MovieRepository $movieRepository, Request $request, EntityManagerInterface  $entityManager): Response
    {
        // 1ere etape je créer une instance de l'entité Movie
        // Car je suis dans create, je veux donc CREER un film (un nouveau film)
        $movie = new Movie();
        // 2eme etape : je construit mon formulaire qui tourne autour de mon objet $movie (le movie que je viens de créer)
        // 1er param = la classe de formulaire, 2eme param = l'objet qu'on veut manipuler
        $form = $this->createForm(MovieType::class, $movie); 
        // Je passe les infos de lma requete a mon $form pour savoir si le formulaire a été soumit ou non
        $form->handleRequest($request);
        // Je check si le formulaire a été soumit ET si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();
            // On va afficher un 'message flash' qui va permettre d'afficher si oui ou non le film a bien été créée
            $this->addFlash(
                'success',
                'Le film '.$movie->getTitle().'a bien été créée !'
            );
            return $this->redirectToRoute('browse_movie');
        }
        // Je passe tous les films à ma vue
        return $this->render('back/movie/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Modifie un film via son id dans un formulaire dans le backoffice
     * Ici la route sera '/back/movie/edit/{id}' car toutes les routes de base de cette 
     * classe commenceront par '/back/movie'
     *
     * @return Response
     */
    #[Route('/edit/{id}', name: 'edit_movie')]
    public function edit(Movie $movie, MovieRepository $movieRepository, Request $request, EntityManagerInterface  $entityManager): Response
    {
        // Ici je veux MODIFIER un film, ce qui veut dire que ce film est deja EXISTANT, donc ici pas besoin de créer quoique ce soit, ici on va juste récupérer un film
        // Pour récupérer un film il y a plusieurs faocn de faire, soit on fait un find grace a l'id du film
        // Ou soit on recupere directement le $movie qui a pour id l'id donnée en parametre de la route ({id}) en mettant Movie $movie dans les parametres de la methode
        // Donc ici, le $movie donnée en argument de la methode c'est deja egal au $movie qui a pour id {id}

        // 2eme etape : je construit mon formulaire qui tourne autour de mon objet $movie (le movie qui a pour id {id})
        // 1er param = la classe de formulaire, 2eme param = l'objet qu'on veut manipuler
        $form = $this->createForm(MovieType::class, $movie);
        // Ici je construis un formulaire qui manipule un objet $movie qui existe deja, donc qui a deja des valeur (un title, une duration etc), donc dans le form qui va s'afficher, 
        //il y aura deja les valeur preremplis (le title sera deja preremplis, la durée aussi, etc), il ne reste plus qu'a modifier ce qu'on veut (voir dans la page directement)
        // Je passe les infos de la requete a mon $form pour savoir si le formulaire a été soumit ou non
        $form->handleRequest($request);
        // Je check si le formulaire a été soumit ET si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
           // Ici pas besoin de persist (on peut le mettre, mais ne sert a rien) car $movie est deja existant, on persist quand on créer une instance d'entité
           // Pour faire simple : $movie existait deja, il a deja été persist
            $entityManager->flush(); // Par contre, flush est obligatoire mais mettre a jour la donnée en bdd
            $this->addFlash(
                'success',
                'Le film '.$movie->getTitle().' a bien été modifié !'
            );
            return $this->redirectToRoute('browse_movie');
        }
        // Je passe tous les films à ma vue
        return $this->render('back/movie/edit.html.twig', [
            'form' => $form,
            'movie' => $movie
        ]);
    }

    /**
     * Supprime un film via son id dans un formulaire dans le backoffice
     * Ici la route sera '/back/movie/edit/{id}' car toutes les routes de base de cette 
     * classe commenceront par '/back/movie'
     *
     * @return Response
     */
    #[Route('/remove/{id}', name: 'remove_movie')]
    public function remove(Movie $movie, MovieRepository $movieRepository, Request $request, EntityManagerInterface  $entityManager): Response
    {
        // Ici je veux SUPPRIMER un film, ce qui veut dire que ce film est deja EXISTANT, donc ici pas besoin de créer quoique ce soit, ici on va juste récupérer un film
        

        // 2eme etape : je supprime le $movie
        $entityManager->remove($movie);
        $entityManager->flush();
        
        // Je redirige l'utilisateur sur la home
        return $this->redirectToRoute('browse_movie');
    }
}