<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OflixProvider;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\MySlugger;

class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Methode qui s'execute au moment ou on lance les fixtures
     * Cette méthode va créer de la data en paquet dans un peu toutes nos tables
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Je vais initialiser faker en créant une instance de Faker
        $faker = Factory::create('fr_FR');
        // Je créer une instance du provider OflixProvider
        $provider = new OflixProvider();

        // Je créer un tableau
        $genreList = [];
        // Créer 10 genres
        for ($i = 0; $i < 10; $i++) {
            $genre = new Genre();
            $genre->setName($provider->genre_rand());
            $genreList[] = $genre; // Je rajoute l'objet $genre a mon tableau
            $manager->persist($genre);
        }
        // Créer 100 personnes (acteurs)
        for ($i = 0; $i < 100; $i++) {
            $person = new Person();
            $person->setFirstname($faker->firstName()); // Je définis un prenom fake grace a faker
            $person->setLastname($faker->lastName()); // Je définis un nom fake grace a faker
            $personList[] = $person; // Je rajoute l'objet $person a mon tableau (je sauvegarde tous les acteurs car j'en aurai besoin plus tard)
            $manager->persist($person); // Enregistre l'objet $person
        }

        // Créer 20 films
        for ($i = 0; $i < 20; $i++) {
            $movie = new Movie();
            // Ci dessous je genere un titre de film au hasard via le Provider 
            $movie->setTitle($provider->movie_rand());
            // On sluggifie le titre du film
            // On définit la propriété $slg de Movie en lui donnant la valeur de title dans Movie, mais sluggifié
            // ->lower() sert à rendre toute la string en minuscule
            // https://symfony.com/doc/current/components/string.html#methods-to-change-case
            $movie->setSlug($this->slugger->slugify($movie->getTitle()));
            // On sluggifie le titre du film via le service a créer 
            // $movie->setSlug($movie->getTitle());
            $movie->setDuration(rand(45, 140));
            // On définit une date de sortie (releaseDate est un champ de type date donc il aura pour valeur une instance de DateTime)
            $movie->setReleaseDate(new DateTime()); // Si aucun parametre a DateTime() alors la date courante (la date actuelle)
            // randomElement est une methode de Faker qui prend en parametre un tableau et retourne une valeur de ce tableau au hasard
            // doc : https://fakerphp.github.io/formatters/numbers-and-strings/#randomelement
            $movie->setType($faker->randomElement(['Film', 'Série']));
            // On utilise la methode imageUrl de faker pour generer une image
            $movie->setPoster($faker->imageUrl(300, 480, $movie->getTitle(), true));
            // On fake un float 1er param : 1 chiffre apres la virgule
            // 2eme param : Valeur minimum
            // 3eme param : Valeur maximum
            $movie->setRating($faker->randomFloat(1, 0, 5));
            $movie->setSummary($faker->realText(200)); // Genere un text de 200 caracteres
            $movie->setSynopsis($faker->realText(500)); // Genere un text de 500 caracteres
            // On associe 2 genres au film
            $movie->addGenre($genreList[rand(0,4)]);
            $movie->addGenre($genreList[rand(5,9)]);
            // On va associer une saison à $movie SEULEMENT SI $movie est une série
            if ($movie->getType() === "Série") {
                $season = new Season(); // Je créer une instance de Season
                $season->setNumber(rand(0, 5)); // Je définis un numero de saison
                $season->setEpisodesNumber(rand(5,12)); // Je définis un nombre d'épisodes
                $season->setMovie($movie); // J'associe $season à $movie
                $manager->persist($season); // On enregistre $season
            }
            // On va ajouter des castings au film (on va ajouter des roles interpetés par des acteurs dans ce film)
            // Je veux ajouter 10 role a mon film
            for ($c = 0; $c <= 10; $c++) {
                $casting = new Casting(); // Je créer une instance de l'entité Casting
                $casting->setRole($faker->name()); // Je définis un role interprété
                $casting->setCreditOrder($c + 1); // Je définis le creditOrder (l'ordre d'importance d'un personnage dans un film)
                $casting->setMovie($movie); // J'associe le film $movie au casting
                // Fonctionne aussi comme ca
                //$movie->addCasting($casting);// J'associe le $casting au $movie
                $casting->setPerson($personList[rand(0,99)]); // J'associe le casting a un acteur au hasard
                $manager->persist($casting); // On enregistre $casting
            }
            $manager->persist($movie);
        }
        // Je créer 20 casting
        // On créer 3 utilisateurs

        // 1er : utilisateur admin
        $user = new User(); // On créer l'user
        $user->setEmail("admin@admin.fr"); // On lui donne un email
        $user->setRoles(['ROLE_ADMIN']); // On donne le role admin a cet user
        $user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
        $manager->persist($user); // On persist

        // 2eme : utilisateur manager
        $user = new User(); // On créer l'user
        $user->setEmail("manager@manager.fr"); // On lui donne un email
        $user->setRoles(['ROLE_MANAGER']); // On donne le role manager a cet user
        $user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
        $manager->persist($user); // On persist

        // 3eme : utilisateur user (classique)
        $user = new User(); // On créer l'user
        $user->setEmail("user@user.fr"); // On lui donne un email
        $user->setRoles(['ROLE_USER']); // On donne le role user a cet user
        $user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
        $manager->persist($user); // On persist

        $manager->flush();
    }
}
