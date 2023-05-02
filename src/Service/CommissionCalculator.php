<?php

namespace App\Service;

class CommissionCalculator
{
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
    
}
