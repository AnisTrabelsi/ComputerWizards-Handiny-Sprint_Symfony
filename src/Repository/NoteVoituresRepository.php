<?php

namespace App\Repository;

use App\Entity\NoteVoitures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NoteVoitures>
 *
 * @method NoteVoitures|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteVoitures|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteVoitures[]    findAll()
 * @method NoteVoitures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteVoituresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteVoitures::class);
    }

    public function save(NoteVoitures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NoteVoitures $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNoteMoyenneByVoiture(): array
    {
        $qb = $this->createQueryBuilder('nv')
            ->select('(nv.id_voiture) as idVoiture, avg(nv.note) as noteMoyenne')
            ->groupBy('nv.id_voiture');
        
        $result = $qb->getQuery()->getResult();
        
        $noteMoyennes = array();
        foreach ($result as $row) {
            $voitureId = $row['idVoiture'];
            $noteMoyenne = round($row['noteMoyenne']);
            $noteMoyennes[$voitureId] = $noteMoyenne;
        }
        
        return $noteMoyennes;
    }


    public function getVoitureWithHighestAverageNote($limit = 5)
    {
        return $this->createQueryBuilder('n')
        ->select('v.id_voiture, v.marque, v.modele, AVG(n.note) as notes')
        ->leftJoin('n.id_voiture', 'v') // Relation entre la table note_voitures et la table des voitures
        ->groupBy('v.id_voiture') // Regrouper par ID de voiture
        ->orderBy('notes', 'DESC') // Trier par moyenne de note décroissante
        ->setMaxResults($limit)// Récupérer uniquement le premier résultat

        ->getQuery()
        ->getResult();
    }
    



//    /**
//     * @return NoteVoitures[] Returns an array of NoteVoitures objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NoteVoitures
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
