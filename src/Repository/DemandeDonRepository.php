<?php

namespace App\Repository;

use App\Entity\DemandeDon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DemandeDon>
 *
 * @method DemandeDon|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemandeDon|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemandeDon[]    findAll()
 * @method DemandeDon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeDonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeDon::class);
    }

    public function save(DemandeDon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DemandeDon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchdemandesdons(string $query): array
    {
        $qb = $this->createQueryBuilder('d');
        
        return $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('d.dateDemande', ':query'),
                $qb->expr()->like('d.typeProduitDemande', ':query'),
                 $qb->expr()->like('d.remarques', ':query'),
                 $qb->expr()->like('d.etat', ':query'),
            )
        )
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();
    }


 public function countByDate(){
        // $query = $this->createQueryBuilder('a')
        //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
        //     ->groupBy('dateAnnonces')
        // ;
        // return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.dateDemande, 1, 10) as datededemande, COUNT(a) as count FROM App\Entity\DemandeDon a GROUP BY datededemande
        ");
        return $query->getResult();
    }


//    /**
//     * @return DemandeDon[] Returns an array of DemandeDon objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DemandeDon
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
