<?php


namespace App\Repository;


use App\Entity\Chauffeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ChauffeurEntityRepository<Chauffeur>
 *
 * @method Chauffeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chauffeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chauffeur[]    findAll()
 * @method Chauffeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class chauffeurRepository extends ServiceEntityRepository

{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chauffeur::class);
    }


    public function save(Chauffeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);


        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function remove(Chauffeur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);


        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Chauffeur[] Returns an array of Chauffeur objects
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


//    public function findOneBySomeField($value): ?Chauffeur
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function SortBynom()
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.nom','ASC')
        ->getQuery()
        ->getResult()
        ;
}




public function SortByadresse()
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.adresse','ASC')
        ->getQuery()
        ->getResult()
        ;
}



public function findBynom( $nom)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.nom LIKE :nom')
        ->setParameter('nom','%' .$nom. '%')
        ->getQuery()
        ->execute();
}


public function findByadresse( $adresse)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.adresse LIKE :adresse')
        ->setParameter('adresse','%' .$adresse. '%')
        ->getQuery()
        ->execute();
}




}

