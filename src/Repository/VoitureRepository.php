<?php

namespace App\Repository;
use App\DoctrineExtensions\Query\MySql\Round;
use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;



/**
 * @extends ServiceEntityRepository<Voiture>
 *
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function save(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Voiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByUserId($userId)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.id_user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findOneById($id)
    {
        return $this->createQueryBuilder('v')
            ->where('v.id_voiture = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findDistinctMarques()
    {
        return $this->createQueryBuilder('v')
            ->select('DISTINCT v.marque')
            ->getQuery()
            ->getResult();
    }
    public function findByMarques(array $marques)
    {
        return $this->createQueryBuilder('v')
            ->where('v.marque IN (:marquess)')
            ->setParameter('marquess', $marques)
            ->getQuery()
            ->getResult()
        ;
    }
    
   /* public function findByNotes(array $notes)
{
    $qb = $this->createQueryBuilder('v');
    $qb->select('v', 'AVG(n.note) as avg_note')
       ->join('v.noteVoitures', 'n')
       ->where($qb->expr()->in('n.note', $notes))
       ->groupBy('v.id_voiture')
       ->having('COUNT(n.id_note_voitures) >= :count')
       ->setParameter('count', count($notes));

    return $qb->getQuery()->getResult();
}*/
public function findByNotes(array $notes)
{
    $qb = $this->createQueryBuilder('v');
    $qb->join('v.noteVoitures', 'n')
       ->groupBy('v.id_voiture')
       ->having($qb->expr()->in('ROUND(AVG(n.note))', $notes));

      

    ;
    return $qb->getQuery()->getResult();
}


    public function findCarsByRatings($notes)
    {
        $qb = $this->createQueryBuilder('v')
            ->innerJoin('v.noteVoitures', 'n');
    
        if (!empty($notes)) {
            $qb->andWhere('n.note IN (:notes)')
                ->setParameter('notes', $notes);
        }
    
        $voitures = $qb->orderBy('v.marque', 'ASC')
            ->getQuery()
            ->getResult();
    
        $result = array();
        foreach ($voitures as $voiture) {
            $noteVoitures = $voiture->getNoteVoitures();
            $notes = array();
            foreach ($noteVoitures as $noteVoiture) {
                $notes[] = $noteVoiture->getNote();
            }
            $averageNote = count($notes) > 0 ? round(array_sum($notes) / count($notes), 1) : null;
    
            if (!isset($result[$averageNote])) {
                $result[$averageNote] = array();
            }
            $result[$averageNote][] = $voiture;
        }
        return $result;
    }
    
    
    
    
    

    public function derniersVoitures():array
    {
        return $this->createQueryBuilder('v')
            
            ->orderBy('v.id_voiture', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }

    public function searchVoitures(string $query): array
{
    $qb = $this->createQueryBuilder('v');
    
    return $qb->andWhere(
        $qb->expr()->orX(
            $qb->expr()->like('v.immatriculation', ':query'),
            $qb->expr()->like('v.marque', ':query'),
            $qb->expr()->like('v.modele', ':query'),
            $qb->expr()->like('v.boite_vitesse', ':query'),
            $qb->expr()->like('v.kilometrage', ':query'),
            $qb->expr()->like('v.prix_location', ':query')
           
            
        )
    )
    ->setParameter('query', '%'.$query.'%')
    ->getQuery()
    ->getResult();
}
    


    


//    /**
//     * @return Voiture[] Returns an array of Voiture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Voiture
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByPrixRange($minPrice, $maxPrice)
{
    return $this->createQueryBuilder('v')
        ->andWhere('v.prix_location >= :minPrice')
        ->andWhere('v.prix_location <= :maxPrice')
        ->setParameter('minPrice', $minPrice)
        ->setParameter('maxPrice', $maxPrice)
        ->orderBy('v.prix_location', 'ASC')
        ->getQuery()
        ->getResult();
}


public function TriParPrix_Location():array
{
    $qb = $this->createQueryBuilder('v')
    ->orderBy('v.prix_location', 'DESC')
        ;

    return $qb->getQuery()->getResult();
}

public function TriParMarque():array
{
    $qb = $this->createQueryBuilder('v')
    ->orderBy('v.marque', 'ASC')
        ;

    return $qb->getQuery()->getResult();
}
public function TriParModele():array
{
    $qb = $this->createQueryBuilder('v')
    ->orderBy('v.modele', 'ASC')
        ;

    return $qb->getQuery()->getResult();
}


    


}
