<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\FavorisVoitures;
use App\Repository\UserRepository;
use App\Repository\VoitureRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\FavorisVoituresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureFavorisController extends AbstractController
{
    #[Route('/AffichageVoituresFavoris', name: 'Favoris_voitures')]
    public function AffichageFavoris(UserRepository $userRepository,VoitureRepository $voitureRepository,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

          //Le user connecté : 
    //$user = $this->getUser();
    $user = $userRepository->find(14);
    $favoris = $em->getRepository(FavorisVoitures::class)->findByUserId($user);

    $voitures = [];

    foreach ($favoris as $fav) {
        $voiture = $voitureRepository->find($fav->getIdVoiture());
        if ($voiture) {
            $voitures[] = $voiture;
        }
    }

    return $this->render('voiture_favoris/AffichageVoituresFavoris.html.twig', [
        'voitures'=>$voitures
       
        
        
    ]);
    }

    #[Route("/favoris/voitures/delete/{id}", name:"app_favoris_voitures_delete")]
        public function delete(UserRepository $userRepository, Voiture $voiture, Request $request, FavorisVoituresRepository $favorisVoituresRepository, ManagerRegistry $doctrine)
        {
            
            // Récupérer l'utilisateur actuellement connecté
                
            //$user = $this->getUser();
            $user = $userRepository->find(14);
            
            
        
            // Vérifier si la voiture existe
            if (!$voiture) {
                throw $this->createNotFoundException('La voiture n\'existe pas.');
            }
        
            // Récupérer l'objet FavorisVoitures correspondant à cette voiture et à l'utilisateur actuel
            $favorisVoiture = $favorisVoituresRepository->findOneBy(['id_user' => $user, 'id_voiture' => $voiture]);
        
            // Vérifier si la voiture est dans les favoris de l'utilisateur
            if (!$favorisVoiture) {
                throw $this->createNotFoundException('La voiture n\'est pas dans les favoris.');
            }
        
            // Supprimer l'objet FavorisVoitures et la voiture elle-même
            $doctrine->getManager()->remove($favorisVoiture);
            $doctrine->getManager()->flush();
        
            $this->addFlash('success', 'La voiture a été supprimée de votre liste de favoris');

      

        return $this->redirectToRoute('Favoris_voitures');
    }
    #[Route('/favoris/voitures/add{id}', name: 'app_favoris_voitures_add')]
    public function ajouterFavoris(ManagerRegistry $doctrine, Voiture $voiture,UserRepository $userRepository,VoitureRepository $voitureRepository): Response
    {
        


    // Définir l'utilisateur par défaut avec l'id 7
    //Le user connecté : 
    //$user = $this->getUser();
    $user = $userRepository->find(14);
    

        // Vérifier si l'utilisateur est connecté
        /*if (!$user) {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
            return $this->redirectToRoute('app_login');
        }*/

        // Vérifier si la voiture existe
        if (!$voiture) {
            // Si la voiture n'existe pas, afficher une erreur
            throw $this->createNotFoundException('La voiture n\'existe pas');
        }

        // Vérifier si l'utilisateur a déjà ajouté cette voiture à ses favoris
        $favoris = $doctrine->getRepository(FavorisVoitures::class)->findOneBy([
            'id_user' => $user,
            'id_voiture' => $voiture,
        ]);

        if (!$favoris) {
            // Si l'utilisateur n'a pas encore ajouté cette voiture à ses favoris, créer une nouvelle entrée dans la table favoris_voitures
            $favoris = new FavorisVoitures();
            $favoris->setIdUser($user);
            $favoris->setIdVoiture($voiture);
            $favoris->setDateAjoutFavoris(new \DateTime());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($favoris);
            $entityManager->flush();

            // Rediriger l'utilisateur vers la page de la voiture avec un message de confirmation
            $this->addFlash('success', 'La voiture a été ajoutée à vos favoris.');
            return $this->redirectToRoute('app_voiture');
        } else {
            // Si l'utilisateur a déjà ajouté cette voiture à ses favoris, afficher un message d'erreur
            $this->addFlash('warning', 'La voiture est déjà dans vos favoris.');
            return $this->redirectToRoute('app_voiture');
        }
    }
}
