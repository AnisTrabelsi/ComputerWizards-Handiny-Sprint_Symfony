<?php

namespace App\Controller;

use App\Entity\Don;
use App\Entity\DonsFavoris;
use App\Repository\DonRepository;
use App\Repository\UserRepository;
use App\Repository\DonsFavorisRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DonsFavorisController extends AbstractController
{
    #[Route('/AffichagedonsFavoris', name: 'Favoris_dons')]
    public function AffichageFavoris(DonRepository $DonRepository,ManagerRegistry $doctrine): Response
    {
        $favoris = $doctrine
        ->getRepository(DonsFavoris::class)
        ->findAll();

    $dons = [];

    foreach ($favoris as $fav) {
        $don = $DonRepository->find($fav->getIddon());
        if ($don) {
            $dons[] = $don;
        }
    }

    return $this->render('dons_favoris/AffichagedonsFavoris.html.twig', [
        'dons'=>$dons

    ]);
    }

    #[Route("/favoris/dons/delete/{id}", name:"app_favoris_dons_delete")]
        public function delete(UserRepository $userRepository, Request $request, DonsFavorisRepository $DonsFavorisRepository, ManagerRegistry $doctrine,DonRepository $repo,$id)
        {
            $don=$repo->find($id);
            // Récupérer l'utilisateur actuellement connecté
            $user = $userRepository->find(7);
            
            
        
            // Vérifier si la don existe
            if (!$don) {
                throw $this->createNotFoundException('La don n\'existe pas.');
            }
        
            // Récupérer l'objet DonsFavoris correspondant à cette don et à l'utilisateur actuel
            $favorisdon = $DonsFavorisRepository->findOneBy(['id_utilisateur' => $user, 'id_don' => $don]);
        
            // Vérifier si la don est dans les favoris de l'utilisateur
            if (!$favorisdon) {
                throw $this->createNotFoundException('La don n\'est pas dans les favoris.');
            }
        
            // Supprimer l'objet DonsFavoris et la don elle-même
            $doctrine->getManager()->remove($favorisdon);
            $doctrine->getManager()->flush();
        
            $this->addFlash('error', 'La don a été supprimée de votre liste de favoris');

      

        return $this->redirectToRoute('Favoris_dons');
    }
    #[Route('/favoris/dons/add{id}', name: 'app_favoris_dons_add')]
    public function ajouterFavoris(ManagerRegistry $doctrine,UserRepository $userRepository,$id,DonRepository $DonRepository): Response
    {
        
$don=$DonRepository->find($id);

    $user = $userRepository->find(7);
    

        if (!$don) {
            // Si la don n'existe pas, afficher une erreur
            throw $this->createNotFoundException('La don n\'existe pas');
        }

        // Vérifier si l'utilisateur a déjà ajouté cette don à ses favoris
        $favoris = $doctrine->getRepository(DonsFavoris::class)->findOneBy([
            'id_utilisateur' => $user,
            'id_don' => $don,
        ]);

        if (!$favoris) {
            // Si l'utilisateur n'a pas encore ajouté cette don à ses favoris, créer une nouvelle entrée dans la table favoris_dons
            $favoris = new DonsFavoris();
            $favoris->setIdUser($user);
            $favoris->setIdDon($don);
            $favoris->setDateAjout(new \DateTime());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($favoris);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de la don avec un message de confirmation
            $this->addFlash('success', 'La don a été ajoutée à vos favoris.');
            return $this->redirectToRoute('app_list_don');
        } else {
            // Si l'utilisateur a déjà ajouté cette don à ses favoris, afficher un message d'erreur
            $this->addFlash('warning', 'La don est déjà dans vos favoris.');
            return $this->redirectToRoute('app_list_don');
        }
    }
}
