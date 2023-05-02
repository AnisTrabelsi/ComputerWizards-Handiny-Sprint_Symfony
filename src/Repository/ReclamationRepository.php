<?php

namespace App\Repository;

use App\Entity\Reclamation;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id_reclamation
 * , $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findByUserId()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function countByType(): array
{
    return $this->createQueryBuilder('r')
        ->select('r.type_reclamation, COUNT(r.id_reclamation) as count')
        ->groupBy('r.type_reclamation')
        ->getQuery()
        ->getResult();
}

    public function findById($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id_reclamation  = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id_reclamation
            ', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
/**
 * @param int $userId
 * @return Reclamation[] Returns an array of Reclamation objects for a specific user
 */

public function findByUserId($userId)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.id_utilisateur = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('r.id_utilisateur', 'DESC')
        ->getQuery()
        ->getResult();
}

public function findByUser2(User $user)
{
    return $this->createQueryBuilder('r')
        ->andWhere('r.id_utilisateur  = :user')
        ->setParameter('user', $user)
        ->orderBy('r.id_reclamation', 'DESC')
        ->getQuery()
        ->getResult();
}

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
