<?php

namespace App\Controller;
use App\Repository\UserRepository;
use App\Repository\ReclamationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/user_par_age', name: 'user_par_age')]
    public function usersByAgeGroup(UserRepository $userRepository)
    {
        // Vérifier si l'utilisateur a les autorisations nécessaires pour accéder à la page
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_homefront');
        }
    
        $userData = $userRepository->getUsersCountByAgeGroup();
    
        $ageGroups = [];
        $userCounts = [];
    
        foreach ($userData as $data) {
            $ageGroups[] = $data['age_group'];
            $userCounts[] = $data['user_count'];
        }
        $userData = $userRepository->countUsersByGender();

        $genre = [];
        $nombre = [];
      
        foreach ($userData as $usersData) {
            $genre[] = $usersData['genre'];
            $nombre[] = $usersData['count'];
           
        }
        
    
        // Passer les tableaux à la vue Twig pour afficher les graphiques
        return $this->render('userdashboard.html.twig', [
            'age_groups' => json_encode($ageGroups),
            'user_counts' => json_encode($userCounts),
            'genre' => json_encode($genre),
            'nombre' => json_encode($nombre),
        ]);
    }
    

    #[Route('/admin/type_reclamation', name: 'type_reclamation')]
    public function type_reclamation(ReclamationRepository $reclamationRepository)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
    
        $reclamationData = $reclamationRepository->countByType();
    
        $type_reclamations = [];
        $reclamation_counts = [];
    
        foreach ($reclamationData as $data) {
            $type_reclamations[] = $data['type_reclamation'];
            $reclamation_counts[] = $data['count'];
        }
    
        // Passer les tableaux à la vue Twig pour afficher les graphiques
        return $this->render('reclamationdashboard.html.twig', [
            'type_reclamations' => json_encode($type_reclamations),
            'reclamation_counts' => json_encode($reclamation_counts),
        ]);
    }
    
}
