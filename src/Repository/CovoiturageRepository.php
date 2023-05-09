<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Covoiturage>
 *
 * @method Covoiturage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Covoiturage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Covoiturage[]    findAll()
 * @method Covoiturage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

    public function save(Covoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Covoiturage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // public function findByDep( $depart)
    // {
    //     return $this-> createQueryBuilder('e')
    //         ->andWhere('e.depart LIKE :depart')
    //         ->setParameter('depart','%' .$depart. '%')
    //         ->getQuery()
    //         ->execute();
    // }

    public function findAllSortedByName($sort = 'asc')
    {
        $queryBuilder = $this->createQueryBuilder('e')
            ->orderBy('e.depart', $sort);
    
        return $queryBuilder->getQuery()->getResult();
    }

    public function findBydateAsc()
    {
        return $this->createQueryBuilder('p')
          
            ->orderBy('p.date_covoiturage', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findBydateDsc()
    {
        return $this->createQueryBuilder('p')
          
            ->orderBy('p.date_covoiturage', 'DESC')
            ->getQuery()
            ->getResult();
    }



    // public function findByKeyword($query)
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.depart LIKE :query OR c.destination LIKE :query')
    //         ->setParameter('query', '%'.$query.'%')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function countByDate(){

        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.date_covoiturage, 1, 10) as date_covoiturage, COUNT(a) as count FROM App\Entity\Covoiturage a GROUP BY date_covoiturage
        ");
        return $query->getResult();
    }


//    /**
//     * @return Covoiturage[] Returns an array of Covoiturage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Covoiturage
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
