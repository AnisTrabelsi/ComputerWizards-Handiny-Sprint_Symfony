<?php

namespace App\Repository;

use App\Entity\Sujet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sujet>
 *
 * @method Sujet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sujet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sujet[]    findAll()
 * @method Sujet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SujetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sujet::class);
    }

    public function save(Sujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sujet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLatestSujets(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.dateCreationSujet', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findSujetsByUser(int $id_user): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.user = :id_user')
            ->setParameter('id_user', $id_user)
            ->getQuery()
            ->getResult();
    }

    public function searchSujets(string $query): array
    {
        $qb = $this->createQueryBuilder('s');
        
        return $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('s.titreSujet', ':query'),
                $qb->expr()->like('s.contenuSujet', ':query'),
                $qb->expr()->like('s.nbCommentaires', ':query'),
                $qb->expr()->like('s.etat', ':query'),
                $qb->expr()->like('s.tags', ':query'),            
                
            )
        )
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();
    }

    public function TriParDate_Creation():array
    {
        $qb = $this->createQueryBuilder('s')
        ->orderBy('s.dateCreationSujet', 'DESC')
            ;

        return $qb->getQuery()->getResult();
    }

    public function findByTag($tags)
    {
        return $this->createQueryBuilder('s')
            ->where('s.tags LIKE :tag')
            ->setParameter('tags', '%'.$tags.'%')
            ->getQuery()
            ->getResult();
    }
    public function findTopSujets($limit = 5): array
        {
            return $this->createQueryBuilder('s')
                ->orderBy('s.nbCommentaires', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }
    public function countSujetsByEtat()
        {
            $qb = $this->createQueryBuilder('s')
                ->select('s.etat, COUNT(s) as nbSujets')
                ->groupBy('s.etat')
                ->getQuery();
            
            return $qb->getResult();
        }


//    /**
//     * @return Sujet[] Returns an array of Sujet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sujet
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
