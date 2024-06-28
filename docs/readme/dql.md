# DQL (Doctrine Query Language)

## C'est quoi ?
DQL signifie Doctrine Query Language. C'est un langage de requête orienté objet spécifique à l'ORM Doctrine utilisé dans Symfony.

## A quoi ça sert ?
DQL sert à écrire des requêtes très similaire aux requêteq SQL, sauf que en DQL on manipule des entités Doctrine.

## Pourquoi utiliser DQL ?

DQL est très utile lorsqu'on a des requêtes un peu spéciales ( DQL offre une grande portabilité)

C'est flexible

On peut récupérer des entités Doctrine avec des commandes SQL du genre SELECT, ORDER BY, ...

En conclusion, DQL est puissant et nous permet de customiser nos requêtes.

## Comment le mettre en place dans notre projet ?
Pour l'instant, dans notre projet, on a un EventSubscriber qui est abonné à l'évènement kernelController. C'est l'évènment qui se déclenche à chaque fois qu'un contrôleur de notre site est sollicité.
Cet EventSubscriber génère un film au hasard depuis la db et l'envoie en variable global à Twig.
La fonction qui me génère un film random est dans le Repository MovieRepository et la méthode est :
```php 
public function findOneRandomMovie()
{
        // On créer la requete SQL qui va selectionner un film au hasard dans la base de donnée, pour l'intsant cette requête c'est juste une string et on la stock dans la variable $sql
        $sql = "SELECT title
        FROM `movie`
        ORDER BY RAND()
        LIMIT 1";

        // On créer une connexion a la db et cette connexion on la stock dans $conn
        $conn = $this->getEntityManager()->getConnection();
        // On execute la requete sql (la string qu'on a stocker dans $sql)
        $result = $conn->executeQuery($sql)->fetchAssociative();
        // On retourne le résultat, qui est juste le titre du film tiré au hasard grace à la fonction SQL RAND()
        return ($result);
}
```
Cette fonction créée une requête SQL en dur, et elle l'éxécute. Cette fonction ne manipule pas d'entité Doctrine, elle manipule directement la base de donnée. Ici la valeur de retour de la fonction ($result) c'est juste le title du film tiré au hasard. 

### C'est bien mais on veut quelque chose de plus propre

L'objectif maintenant ca va etre de récupérer l'entité Doctrine du film tiré au hasard (au lieu de juste son titre).

## Comment faire ?
Vous l'aurez compris, ici on va se servir de DQL.
Voici la nouvelle requête SQL qu'on va devoir faire :
```php
public function findOneRandomMovie()
{
        // Ci dessous on récupère l'entité manager qui permet de gérer des entités Doctrine, leur persistance dans la db, etc
        $entityManager = $this->getEntityManager();
        // Ci dessous je créer ma requête DQL
        $query = $entityManager->createQuery(
        // Ici je créer une requete DQL (Doctrine Query Language). Ca sert a manipuler de entités doctrine en faisant du SQL
                'SELECT m
                FROM App\Entity\Movie AS m
                ORDER BY RAND()'
        );
        return $query->setMaxResults(1)->getOneOrNullResult();
        }
```
#### Mauvaise nouvelle => ça ne fonctionne pas encore :(

Maintenant on a une erreur => DQL ne connait pas la fonction SQL RAND().
Oui, DQL n'a pas integrés toutes les fonctions SQL comme RAND() ou d'autres.
Ce qu'on va faire maintenant c'est qu'on va dire à DQL que rand c'est le même rand que celui de SQL (il va le recopier en quelques sortes). C'est à nous de mettre ça en place.

Pour se faire, on va installer un bundle qui s'appelle doctrineextension : https://github.com/beberlei/DoctrineExtensions

Pour l'installer dans notre projet Symfony :
```bash
composer require beberlei/doctrineextensions
```

Ensuite on va dans config/packages/doctrine.yaml.
Dans la partie doctrine :
```yaml
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '15'
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App
```

En dessous de orm, on va ajouter notre configurations pour DQL, qui va nous permettre de définir rand dans DQL en disant que rand dans DQL = rand dans SQL.
Voilà comment faire ce qu'il faut rajouter :
```yaml
# Ci dessous je mets en place de la configuration pour DQL
dql:
        # Ci dessous, je créer des fonctions utilisablent pour DQL
        numeric_functions:
        # Ci dessous je dis que la fonction rand en DQL correspond à la fonction rand() en SQL (c'est grâce à l'extension doctrineextensions)
                rand: DoctrineExtensions\Query\Mysql\Rand
```
dql: : Cette section concerne la configuration liée à Doctrine Query Language (DQL) que nous avons discuté précédemment.
numeric_functions: : Doctrine permet de définir des fonctions personnalisées qui peuvent être utilisées dans vos requêtes DQL.
rand: DoctrineExtensions\Query\Mysql\Rand : Ici, une fonction numérique personnalisée est définie => Lorsqu'on on utilisera rand en DQL, c'est comme si on utilisait RAND() en SQL.

Voilà a quoi doit ressemble la partie doctrine de config/packages/doctrine.yaml:
```yaml
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'

        # IMPORTANT: You MUST configure your server version,
        # either here or in the DATABASE_URL env var (see .env file)
        #server_version: '15'
    orm:
        auto_generate_proxy_classes: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            App:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Entity'
                prefix: 'App\Entity'
                alias: App
        # Ci dessous je mets en place de la configuration pour DQL
        dql:
            # Ci dessous, je créer des fonctions utilisablent pour DQL
            numeric_functions:
                # Ci dessous je dis ue la fonction rand en DQL correspond à la fonction rand() en SQL (c'est grâce à l'extension doctrineextensions)
                rand: DoctrineExtensions\Query\Mysql\Rand
```
Maintenant on peut re-tester, et ... tadaa, ça fonctionne !