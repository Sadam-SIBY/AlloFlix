<?php

namespace App\Command;

use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'OflixGetPost',
    description: 'Add a short description for your command',
)]
class OflixGetPostCommand extends Command
{
    private $movieRepository;
    private $entityManager;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Je recupere tous les films
        $movies = $this->movieRepository->findAll();
        // Je vaiq boucler sur TOUS les films récupérés
        // A chaque itération $movie sera un élément de $movies
        foreach($movies as $movie)
        {
            // Je définis l'url vers laquelle je veux faire une requete (pour recuperer le bon poster)
            $url = "https://www.omdbapi.com/?apikey=8437c48f&t=".$movie->getSlug();
            // file_get_contents() permet de faire une requete HTTP en methode GET sur l'urk passé en parametre (ici $url)
            $json = file_get_contents($url);
            // Les resultats sont au format json (normal, c'est un API), on va donc essayer de convertir ca en tableau PHP
            $array = json_decode($json, true);
           
            // On va faire une gestion d'erreur au cas ou le poster d'un film n'est pas trouvé
            // On rentre dans le if si le poster est définit
            if (isset($array['Poster'])) {
                // On redéfinit le poster de $movie
                $movie->setPoster($array["Poster"]);
                // On flush pour mettre a jour en bdd
                $this->entityManager->flush();
            }
        }
        return Command::SUCCESS;
    }
}
