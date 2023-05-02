<?php

namespace App\Repository;

use App\Entity\Don;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Don>
 *
 * @method Don|null find($id, $lockMode = null, $lockVersion = null)
 * @method Don|null findOneBy(array $criteria, array $orderBy = null)
 * @method Don[]    findAll()
 * @method Don[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Don::class);
    }

    public function save(Don $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Don $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findDistinctTypes()
    {
        return $this->createQueryBuilder('d')
            ->select('DISTINCT d.type')
            ->getQuery()
            ->getResult();
    }

   

    public function findBytypes(array $types)
    {
        return $this->createQueryBuilder('d')
            ->where('d.type  IN (:types)')
            ->setParameter('types', $types)
            ->getQuery()
            ->getResult()
        ;
    }

  
    public function  findPossesed(User $iduser)
    {
        return $this->createQueryBuilder('d')
            ->where('d.idUtilisateur  == :id')
            ->setParameter('id', $iduser)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function  findNotPossesed(User $iduser)
    {
        return $this->createQueryBuilder('d')
            ->where('d.idUtilisateur != :id')
            ->setParameter('id', $iduser)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByDateRange($minDate, $maxDate)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.dateAjout >= :minDate')
            ->andWhere('d.dateAjout <= :maxDate')
            ->setParameter('minDate', $minDate)
            ->setParameter('maxDate', $maxDate)
            ->orderBy('d.dateAjout', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function TriPar_type():array
    {
        return $this->createQueryBuilder('d')
        ->orderBy('d.type', 'ASC')
        ->getQuery()
        ->getResult();
        
    
    }
    
    public function TriPar_date_de_creation_c():array
    {
        return $this->createQueryBuilder('d')
        ->orderBy('d.dateAjout', 'ASC')->getQuery()->getResult();
         
    }

    public function TriPar_date_de_creation_d():array
    {
       return $this->createQueryBuilder('d')
        ->orderBy('d.dateAjout', 'DESC')->getQuery()->getResult();
         
    
      
    }


    
    public function countByType($type)
    {
        return $this->createQueryBuilder('d')
                    ->select('COUNT(d.idDon)')
                    ->andWhere('d.type = :type')
                    ->setParameter('type', $type)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function searchdons(string $query): array
    {
        $qb = $this->createQueryBuilder('d');
        
        return $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('d.type', ':query'),
                $qb->expr()->like('d.dateAjout', ':query'),
                 $qb->expr()->like('d.description', ':query'),

            )
        )
        ->setParameter('query', '%'.$query.'%')
        ->getQuery()
        ->getResult();
    }


   
    
      
    
    

//    /**
//     * @return Don[] Returns an array of Don objects
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

//    public function findOneBySomeField($value): ?Don
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
