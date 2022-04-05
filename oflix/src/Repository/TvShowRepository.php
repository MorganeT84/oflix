<?php

namespace App\Repository;

use App\Entity\TvShow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TvShow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TvShow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TvShow[]    findAll()
 * @method TvShow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TvShowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TvShow::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TvShow $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TvShow $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
     * Récupère toutes les informations liées au tvShow demandé
     * @return TvShow
     */
    public function findOneWithAllInfos(int $id): TvShow
    {
        $entityManager = $this->getEntityManager();

        // on va utiliser le DQL ( Doctrine Query Language)
        $query = $entityManager->createQuery(
            'SELECT t, s, e
            -- dans le select il faut penser à ajouter les objets que l on veut récupérer
            -- car le SELECT t équivaut à SELECT tv_show.*
            -- En DQL on requête des objets ! donc on fournit le FQCN de l objet à récupérer
            FROM App\Entity\TvShow t
            -- le join permet de faire le inner join et de récupérer directement les informations de la table reliée
            JOIN t.seasons s
            -- vous remarquerez que l on passe par les propriétés de l objet // ON PENSE OBJET ET NON SQL !
            JOIN s.episodes e

            -- un petit paramètre pour éviter les injections DQL !
            WHERE t.id = :id'
        )->setParameter('id', $id);

        // returns the selected TvShow Object
        return $query->getOneOrNullResult();
    }
    /**
     * Récupère toutes les informations liées au tvShow demandé
     * @return TvShow
     */
    public function findOneWithInfosDQL(int $id, bool $withSeason = false, bool $withEpisode = false): TvShow
    {
        $entityManager = $this->getEntityManager();

        $select = " SELECT t ";
        $from = " FROM App\Entity\TvShow t ";
        $join = " ";
        $where = " WHERE t.id = :id ";

        if ($withSeason) {
            // équivalent à 
            // $select = $select . ", s";
            $select .= ", s";
            $join .= " LEFT JOIN t.seasons s ";
        }

        if ($withEpisode) {
            $select .= ", e ";
            $join .= " LEFT JOIN s.episodes e ";
        }


        $join .= " LEFT JOIN t.categories c ";
        $select .= ", c";
        $join .= " LEFT JOIN t.rolePlays r ";
        $select .= ", r";
        $join .= " LEFT JOIN r.personage p ";
        $select .= ", p";

        
        $dqlQuery = $select . $from . $join . $where;
        
        // on va utiliser le DQL ( Doctrine Query Language)
        $query = $entityManager->createQuery(
            $dqlQuery
        )->setParameter('id', $id);

        // returns the selected TvShow Object
        return $query->getOneOrNullResult();
    }


    // /**
    //  * @return TvShow[] Returns an array of TvShow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TvShow
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
