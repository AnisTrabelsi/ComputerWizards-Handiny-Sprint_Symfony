<?php

namespace App\Repository;

use App\Entity\FavorisVoitures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavorisVoitures>
 *
 * @method FavorisVoitures|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavorisVoitures|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavorisVoitures[]    findAll()
 * @method FavorisVoitures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisVoituresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavorisVoitures::class);
    }

    public function save(FavorisVoitures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FavorisVoitures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUserId($userId)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id_user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return FavorisVoitures[] Returns an array of FavorisVoitures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavorisVoitures
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
