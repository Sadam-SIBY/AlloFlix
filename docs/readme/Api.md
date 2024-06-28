# API

### C'est quoi une API

Une API Application Programming Interface (Interface de programmation d'application).

Une API c'est un ensemble de "règles" et de protocoles à respecter qui permettent à différents systèmes de communiquer entre eux.

C'est grâce à elle que 2 systèmes peuvent communiquer.

Une API n'est qu'un intermédiaire.

On se sert d'une API pour pouvoir avoir accès a des données exterieurs.

Par exemple si je veux récuperer la liste des 10 musiques les plus écoutées en France dans la semaine, Spotify a une API qui propose toutes ces données la (Spotify est un exemple parmis tant d'autres).

### On veut développer l'API de oflix
Pour mettre en place l'API de oflix, on va faire en sorte que lorsqu'on utilise l'API, on puisse avoir accès aux données de la bdd ... Mais sans directement utiliser la BDD (d'ou l'interet des API).
Pour développer une API avec symfony c'est super simple : 
Il suffit de créer des controllers avec des routes et les methodes de ces controllers vont retourner de la data ... au format JSON (car c'est le format de retour d'une API)
Une API retourne des données au format JSON.

Le format JSON : tableaux en javascript, ca ressemble a ca :
{
    "titre":"spiderman",
    "durée":"2h",
    "acteurs": {
        "id":2,
        "nom":"tobey macguire"
    },
    {
        "id":3,
        "nom":"kev adams"
    }
}

### On va développer l'API etape par etape

#### 1er etape : on créer le controller ApiController
Ce controller sera le controleur qui va gérer les routes de l'api. Toutes les routes de l'api doivent commencer par /api/...

A ce stade, on a dans notre dossier Controller un fichier ApiController qui gère deja les routes de notre api (il n'y en a qu'une pour l'instant)

Ce qu'on va faire c'est qu'on va modifier ce fichier.

```php
<?php

namespace App\Controller;

use App\Model\Movies;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api/movie', name: 'get_movie', methods: ['GET'])]
    public function index()
    {
        // on va chercher les données depuis le Model
        $movies = Movies::getMovies();

        // on les retourne en JSON
        // https://symfony.com/doc/5.4/controller.html#returning-json-response
        // /!\ ne pas renvoyer un tableau indexé !
        // => ajouter une clé pour retourner le tableau en question
        // @see https://github.com/OWASP/CheatSheetSeries/blob/master/cheatsheets/AJAX_Security_Cheat_Sheet.md#always-return-json-with-an-object-on-the-outside
        return $this->json(['movies' => $movies]);
    }
}
```

On voit qu'on a une méthode moviesGet(), on va la modifier complètement.

On change le nom de la fonction (par convention, rien de technique) et on la renomme par :
```php
public function index()
```
Ensuite, plutôt que de récuperer les données en dur depuis le model Movie, on va utiliser le MovieRepository (pour manipuler nos entités, ici récuperer la liste de tous les films), on va donc créer un parametre a notre fonction MovieREposiotry comme ca :

```php 
public function index(MovieRepository $movieRepository)
```
 Ensuite, dans la fonction, on stock dans $movies la liste des films présents dans ma db :
```php
// on va chercher les données depuis la base de données garce au movie reposiotry
// MovieREpository car c'est le repository qui est lié à notre entité Movie, ce qui veut dire que grace a cette classe je vais pouvoir manipuler les données sur ma DB.
$movies = $movieRepository->findAll();
```
Maintenant pour que ce soit envoyé en format JSON on va "serialiser" $movies.
Serialiser => convertir au format JSON (c'est tout).
Pour serialsier $movies on se sert de la methode json() et on lui ajoute 3 autres parmetres (voir les commentaires pour voir leur utilités)
```php
return $this->json(
            // 1er parametres = ce qu'on veut afficher
            $movies,
            // 2eme parametre : le code status
            // voir liste des code status : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
            200,
            // 3eme parametre = le header
            [],
            // 4eme parametre : le/les groupe(s)
            // Les groupes permettent de définir quels éléments de l'entité on veut afficher
            ['groups' => 'get_movie']
            );
```
1er param : ce qu'on veut serialiser (convertir en json)
2eme param : le code status
3eme param : le header (ici vide car pas besoin pour l'instant)
4eme param : le groupe de serialisation

Le groupe de serialisation c'est un moyen pour nous (développeur d'API) de définir quelles sont les données qu'on veut rendre accessible dans la requête courante (la requete qui a été faite ici c'est http://localhost/.../api/movies => methode getCollection dans le apiController).
Pour définir quelles sont les données qu'on veut rendre accessible, on va devoir mettre le nom du groupe en 4eme parametre de json() ici dans le ApiController, et ensuite on va définir tous les attributs de mon entité (ici Movie) que je veux afficher, et pour se faire, on va basculer sur Entity/Movie.php
Et au dessus de chaque propriété de la classe (```php$private machin;```), on va rajouter ca :
```php
#[Groups(['get_movie'])]
```
Ce qui doit donner un truc du genre (par exemple si on veut rendre affichable le titre du film) :
```php
#[ORM\Column(length: 255)]
    #[Assert\Length(
        max: 100,
        maxMessage: 'Le titre doit faire moins de {{ limit }} characters',
    )]
    #[Groups(['get_movie'])]
    private ?string $title = null;
```
Et enfaite on va rajouter ca pour TOUTES les propriétés de Movie qu'on veut rendre accessible.
Une fois que c'est fait, il faut installer le composant symfony serializer en tapant cette commande (dans le terminal) :
```bash
composer require symfony/serializer
```
Ensuite dans Movie.php on rajoute ca dans les use :
```php
use Symfony\Component\Serializer\Annotation\Groups;
```

Maintenant on peut tester dans insomnia la requete HTTP qui se termine par le endpoint /api/movies (la methode ```php index(MovieRepository $movieRepository) ``` dans le controller APIController) :
```
GET => http://localhost/promos/vulcanium/E17/oflix-jc-oclock/public/api/movies
```

Et voila le résultat :
```js
	{
		"id": 84,
		"title": "Shallow Hal",
		"duration": 46,
		"releaseDate": "2024-01-19T00:00:00+00:00",
		"type": "Film",
		"summary": "M. Homais de tous ces articles et qu'il lui reprochait, c'était d'écouter continuellement les trois chantres qui psalmodiaient. Le serpent soufflait à pleine bouche, ou c'étaient de petits fichus de.",
		"synopsis": "Rodolphe et Emma revenait à elle l'humus de la cire des cierges, avec une sonorité cristalline et qui portait un habit bleu, «comme si l'on ne jurerait pas qu'elle va se lever tout à l'heure, tantôt, demain, il n'en saurait pas moins à lui faire la demande de l'apothicaire, M. Canivet regardait vaguement sur la table, entre les lignes, comme si l'on sortait des ténèbres à la grille du notaire, portait un énorme et qui certainement sont morts dans les bras ouverts. Jamais aucun homme ne lui.",
		"poster": "https:\/\/m.media-amazon.com\/images\/M\/MV5BMTcwMzY2NDE0NF5BMl5BanBnXkFtZTYwNjg2Njc2._V1_SX300.jpg",
		"rating": "0.8"
	},
	{
		"id": 85,
		"title": "Drop Dead Fred",
		"duration": 60,
		"releaseDate": "2024-01-19T00:00:00+00:00",
		"type": "Film",
		"summary": "Aussi poussa-t-il un grand rayon de soleil. Mais l'enfant se mettait à la glace, entre deux chandeliers qui brûlaient. -- Emma! dit-il. -- Quoi? dit-elle vivement; jette-le! Il la traita sans façon.",
		"synopsis": "Une fois, au milieu des efforts qu'il faisait pour jeter les cartes, sa robe du côté des femmes; attends pour te faire plaisir! Et Charles avoua qu'elle avait achetées à Rouen, pour une dame, de faire des préparatifs de la garde-robe. Ce fut le comble! -- C'est un brave homme, reprit Emma. -- Oui! -- Mais je n'en finirais pas, s'il fallait énumérer les uns après les autres instruments se taisaient; on entendait le gros murmure de fontaine ou comme des balles fulminantes en s'aplatissant, et.",
		"poster": "https:\/\/m.media-amazon.com\/images\/M\/MV5BMDNkZWIzZjktYWNkMi00MTQ2LWIyMTgtMmJhOGRiZDZlNmU4XkEyXkFqcGdeQXVyNTUyMzE4Mzg@._V1_SX300.jpg",
		"rating": "3.4"
	},
	{
		"id": 86,
		"title": "The Cider House Rules (1999)",
		"duration": 57,
		"releaseDate": "2024-01-19T00:00:00+00:00",
		"type": "Série",
		"summary": "Le patron était absent; il jeta un cri aigu. Il se trouvait prise, tandis qu'au moindre mouvement qu'ils faisaient, il se leva pour partir. Il la subjuguait. Elle en avait bien d'autres, jusqu'à lui.",
		"synopsis": "Eh bien, moi aussi, reprit l'ecclésiastique. Ces premières chaleurs, quand les quadrilles tourbillonnaient dans sa vie: elle fut prise tout à l'heure, disait: «J'aime Lucie et je ne ferai pas de saillies, rien de particulier ne surgissait; les jours, tous magnifiques, se ressemblaient comme des fleurs de leur fuite, Emma, tout exprès, avait retiré la clef au buffet, Félicité, chaque soir dans un cottage écossais, avec un scalpel, comme si des colibris, en volant, eussent éparpillé leurs.",
		"poster": "https:\/\/via.placeholder.com\/300x480.png\/0055ee?text=The+Cider+House+Rules+%281999%29+ipsa",
		"rating": "0.1"
	},
    // ...

```
Et voilà !
## PUT & POST
Ces méthodes sont différentes des autres : elles attendent un body !
En effet vu que dans POST on veut CREER un movie, on va s'attendre à recevoir les données du movie qu'on veut créer.
Les données seront passées au format JSON :
```json
{
		"title": "Film 1",
		"duration": 60,
		"releaseDate": "2024-01-19T00:00:00+00:00",
		"type": "Film",
		"summary": "Aussi poussa-t-il un grand rayon de soleil. Mais l'enfant se mettait à la glace, entre deux chandeliers qui brûlaient. -- Emma! dit-il. -- Quoi? dit-elle vivement; jette-le! Il la traita sans façon.",
		"synopsis": "Une fois, au milieu des efforts qu'il faisait pour jeter les cartes, sa robe du côté des femmes; attends pour te faire plaisir! Et Charles avoua qu'elle avait achetées à Rouen, pour une dame, de faire des préparatifs de la garde-robe. Ce fut le comble! -- C'est un brave homme, reprit Emma. -- Oui! -- Mais je n'en finirais pas, s'il fallait énumérer les uns après les autres instruments se taisaient; on entendait le gros murmure de fontaine ou comme des balles fulminantes en s'aplatissant, et.",
		"poster": "https:\/\/m.media-amazon.com\/images\/M\/MV5BMDNkZWIzZjktYWNkMi00MTQ2LWIyMTgtMmJhOGRiZDZlNmU4XkEyXkFqcGdeQXVyNTUyMzE4Mzg@._V1_SX300.jpg",
		"rating": "3.4"
}
```
En méthode POST sur le endpoint donnée ('/api/movie'), on va envoyer ça a notre api.
Sur notre controller, dans notre méthode qui gère cette route, on va devoir :
1. Récupérer ce JSON (Request ?)
2. Convertir ce JSON en une instance de l'entité Movie(Voir : https://symfony.com/doc/current/components/serializer.html#converting-property-names-when-serializing-and-deserializing) [```php $movie = $serializer->deserialize($json, Movie::class, 'json'); ```]
3. Envoyer ça en BDD (persist, flush & co)

#### Pour la méthode PUT ? 
Même principe que POST, sauf qu'il faudra modifier une entité...