<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
     * Récupère tous les films dans l'ordre alphabétique
     *
     * @return void
     */
    public function findAllOrderByTitleAsc()
    {
        // On utilise le QueryBuilder et on créer l'alias 'm' qui represente l'entité movie
        return $this->createQueryBuilder('m')
        // Dans l'ordre ASCendant du champ title (le titre des films)
        ->orderBy('m.title', 'ASC')
        // On execute la requete
        ->getQuery()
        // On recupere le resultat
        ->getResult();
    }

    /**
     * Récupère tous les films dans l'ordre alphabétique via DQL
     *
     * @return void
     */
    public function findAllOrderByTitleAscDql()
    {
        // Étape 1 : nous appelons le manager d'entités
        $manager = $this->getEntityManager();
        // Etape 2 : on construit la requete DQL
        $query = $manager->createQuery(
            'SELECT m
            FROM App\Entity\Movie m
            ORDER BY m.title ASC'
        );

        // Etape 3 : j'execute la requête DQL et je retourne le resultat
        return $query->getResult();
    }

    /**
     * Génère un Movie au hasard
     *
     * @return void
     */
    public function getRandomMovie()
    {
        $sql = "SELECT * FROM movie
        ORDER BY RAND()
        LIMIT 1";
        $conn = $this->getEntityManager()->getConnection();
        $resultSet = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAssociative();
    }
//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
