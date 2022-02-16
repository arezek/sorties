<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function rechercherSortieFlitree (int $idCampus){
        //$queryBuilder = $this->createQueryBuilder('sortie');
        //$queryBuilder->from('sortie', 's');




        /*
         * $queryBuilder = $this->createQueryBuilder('sortie');
        $queryBuilder->select('c.nom, s.nom, s.id');
        $queryBuilder->from('App\Entity\Campus', 'c');
        $queryBuilder->from('App\Entity\Sortie', 's');
        $queryBuilder->where('c.id = 3');

        //$queryBuilder->where('sortie.id = :id');
        //$queryBuilder->setParameter('id', $idCampus); //remplace le :id
        //$queryBuilder->groupBy('s.id');
        $query = $queryBuilder->getQuery();

        $resultats = $query->getResult();
        dd($resultats);
         */

        //$queryBuilder->where('s.id = :id');
        //$queryBuilder->setParameter('id', $idCampus); //remplace le :id
        //$queryBuilder->groupBy('');
        //$query = $queryBuilder->getQuery();

        //$resultats = $query->getResult();
        //dd($resultats);

        //return $resultats;
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
