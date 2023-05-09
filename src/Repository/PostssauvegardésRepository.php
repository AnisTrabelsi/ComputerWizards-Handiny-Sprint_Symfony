<?php

namespace App\Repository;

use App\Entity\Postssauvegardés;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postssauvegardés>
 *
 * @method Postssauvegardés|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postssauvegardés|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postssauvegardés[]    findAll()
 * @method Postssauvegardés[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostssauvegardésRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postssauvegardés::class);
    }

    public function save(Postssauvegardés $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Postssauvegardés $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findSauvByUser(int $id_user): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.user = :id_user')
            ->setParameter('id_user', $id_user)
            ->getQuery()
            ->getResult();
    }

   /* public function searchSauv(string $query): array
    {
        $qb = $this->createQueryBuilder('p');
        
        return $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('p.sujet.titreSujet', ':query'),
                $qb->expr()->like('p.sujet.contenuSujet', ':query')                
            )
        )
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();
    }*/

//    /**
//     * @return Postssauvegardés[] Returns an array of Postssauvegardés objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Postssauvegardés
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
