<?php

namespace App\Repository;

use App\Entity\ReservationVoiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Func;



/**
 * @extends ServiceEntityRepository<ReservationVoiture>
 *
 * @method ReservationVoiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationVoiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationVoiture[]    findAll()
 * @method ReservationVoiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationVoitureRepository extends ServiceEntityRepository
{ 
   

    public function __construct(ManagerRegistry $registry, )
    {
        parent::__construct($registry, ReservationVoiture::class);
       
    }

    public function findByUserId($userId)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
    // Logique de calcul de commission
    function calculerCommission($prixLocation, $dureeReservation) {
        $commission = 0;
    
        switch (true) {
            case ($dureeReservation >= 1 && $dureeReservation <= 7):
                if ($prixLocation <= 70) {
                    $commission += ($prixLocation * $dureeReservation * 0.1); // 10% de commission pour les réservations courtes avec un prix de location <= 70 dt
                } elseif ($prixLocation > 70 && $prixLocation <= 110) {
                    $commission += ($prixLocation * $dureeReservation * 0.15); // 15% de commission pour les réservations courtes avec un prix de location entre 70 et 110 dt
                } else {
                    $commission += ($prixLocation * $dureeReservation * 0.2); // 20% de commission pour les réservations courtes avec un prix de location > 110 dt
                }
                break;
            case ($dureeReservation >= 8 && $dureeReservation <= 15):
                if ($prixLocation <= 70) {
                    $commission += ($prixLocation * $dureeReservation * 0.12); // 12% de commission pour les réservations moyennes avec un prix de location <= 70 dt
                } elseif ($prixLocation > 70 && $prixLocation <= 110) {
                    $commission += ($prixLocation * $dureeReservation * 0.17); // 17% de commission pour les réservations moyennes avec un prix de location entre 70 et 110 dt
                } else {
                    $commission += ($prixLocation * $dureeReservation * 0.22); // 22% de commission pour les réservations moyennes avec un prix de location > 110 dt
                }
                break;
            case ($dureeReservation >= 16 && $dureeReservation <= 30):
                if ($prixLocation <= 70) {
                    $commission += ($prixLocation * $dureeReservation * 0.15); // 15% de commission pour les réservations longues avec un prix de location <= 70 dt
                } elseif ($prixLocation > 70 && $prixLocation <= 110) {
                    $commission += ($prixLocation * $dureeReservation * 0.2); // 20% de commission pour les réservations longues avec un prix de location entre 70 et 110 dt
                } else {
                    $commission += ($prixLocation * $dureeReservation * 0.25); // 25% de commission pour les réservations longues avec un prix de location > 110 dt
                }
                break;
            case ($dureeReservation >= 31):
                if ($prixLocation <= 70) {
                    $commission += ($prixLocation * $dureeReservation * 0.18); // 18% de commission pour les réservations très longues avec un prix de location <= 70 dt
                } elseif ($prixLocation > 70 && $prixLocation <= 110) {
                    $commission += ($prixLocation * $dureeReservation * 0.21); // 21% de commission pour les réservations très longues avec un prix de location entre 70 et 110 dt
                } else {
                    $commission += ($prixLocation * $dureeReservation * 0.26); // 26% de commission pour les réservations très longues avec un prix de location > 110 dt
    }
    break;
    default:
    // Cas par défaut pour gérer les erreurs de saisie de durée de réservation
    echo "Erreur : Durée de réservation invalide.";
    break;
    }
    return $commission;
    }
    
    public function getTotalProfitReservationsAcceptees()
{
    // Récupérer le QueryBuilder
    $qb = $this->createQueryBuilder('r');

    // Sélectionner les réservations acceptées
    $qb->andWhere('r.etat_demande_reservation = :etat')
        ->setParameter('etat', 'acceptée');

    // Récupérer les résultats
    $reservations = $qb->getQuery()->getResult();

    // Calculer le total de profit
   
    $totalProfit = 0;
    foreach ($reservations as $reservation) {
        $prixLocation = $reservation->getIdVoiture()->getPrixLocation();
        $dureeReservation = $reservation->getDateDebutReservation()->diff($reservation->getDateFinReservation())->days;
        $commission = $this->calculerCommission($prixLocation, $dureeReservation);
        $totalProfit += $commission;
    }

    return $totalProfit;
}


    public function save(ReservationVoiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReservationVoiture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function searchReservations(string $query): array
{
    $qb = $this->createQueryBuilder('r');
    
    return $qb->andWhere(
        $qb->expr()->orX(
            $qb->expr()->like('r.description_reservation', ':query'),
            $qb->expr()->like('r.etat_demande_reservation', ':query'),
            $qb->expr()->like('r.date_debut_reservation', ':query'),
            $qb->expr()->like('r.date_fin_reservation', ':query'),
            $qb->expr()->like('r.date_demande_reservation', ':query'),
           
            
        )
    )
    ->setParameter('query', '%'.$query.'%')
    ->getQuery()
    ->getResult();
}

public function findVoituresPopulairesReservations($limit = 5)
{
    return $this->createQueryBuilder('r')
        ->select('v.id_voiture, v.marque, v.modele, COUNT(r.id_reservation_voiture) as reservations')
        ->leftJoin('r.id_voiture', 'v')
        ->groupBy('v.id_voiture')
        ->orderBy('reservations', 'DESC')
        ->setMaxResults($limit)
        ->getQuery()
        ->getResult();
}



// Dans votre méthode du repository
public function reservationsCountByDuration()
{
    // Obtenir toutes les réservations
    $reservations = $this->createQueryBuilder('r')
        ->getQuery()
        ->getResult();
    
    // Initialiser les compteurs pour chaque catégorie de durée
    $reservationsCourtes = 0;
    $reservationsMoyennes = 0;
    $reservationsLongues = 0;
    $reservationsTresLongues = 0;
    
    // Parcourir toutes les réservations et les catégoriser en fonction de leur durée
    foreach ($reservations as $reservation) {
        $dateDebut = $reservation->getDateDebutReservation(); // Remplacer 'getDateDebutReservation()' par la méthode qui retourne la date de début de la réservation dans votre entité
        $dateFin = $reservation->getDateFinReservation(); // Remplacer 'getDateFinReservation()' par la méthode qui retourne la date de fin de la réservation dans votre entité
        
        $diff = $dateFin->diff($dateDebut); // Calculer la différence entre les deux dates
        
        $duree = $diff->days; // Obtenir la durée en jours
        
        if ($duree >= 1 && $duree <= 7) {
            $reservationsCourtes++;
        } elseif ($duree >= 8 && $duree <= 15) {
            $reservationsMoyennes++;
        } elseif ($duree >= 16 && $duree <= 30) {
            $reservationsLongues++;
        } elseif ($duree >= 31) {
            $reservationsTresLongues++;
        }
    }
    
    // Retourner un tableau associatif avec les compteurs pour chaque catégorie de durée
    return [
        'réservations courtes' => ['nombre' => $reservationsCourtes, 'jours' => 7],
        'réservations moyennes' => ['nombre' => $reservationsMoyennes, 'jours' => 15],
        'réservations longues' => ['nombre' => $reservationsLongues, 'jours' => 30],
        'réservations très longues' => ['nombre' => $reservationsTresLongues, 'jours' => '31+'],
    ];
}




/*
la méthode orX() crée une expression de type "ou" entre plusieurs conditions de recherche, définies chacune par un appel à la méthode like() sur un champ de la table de la base de données, passant comme paramètres la chaîne de caractères recherchée et un paramètre nommé ':query'.*/ 
/* $qb->expr() est une méthode de l'objet QueryBuilder qui permet de construire des expressions pour les requêtes. C'est une abréviation pour "expression".*/ 

//    /**
//     * @return ReservationVoiture[] Returns an array of ReservationVoiture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservationVoiture
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
