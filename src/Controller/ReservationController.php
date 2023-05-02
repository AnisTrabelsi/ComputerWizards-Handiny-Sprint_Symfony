<?php

namespace App\Controller;

use App\Entity\ReservationCovoiturage;
use App\Form\ReservationCovoiturageType;
use App\Repository\ReservationCovoiturageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CovoiturageRepository ; 
use App\Entity\Covoiturage;
use Doctrine\Persistence\ManagerRegistry ; 
use Doctrine\ORM\EntityManagerInterface;
// use App\Controller\EntityManagerInterface ; 
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationCovoiturageRepository $reservationCovoiturageRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservation_covoiturages' => $reservationCovoiturageRepository->findAll(),
        ]);
    }

    #[Route('/resback', name: 'app_reservation_index3', methods: ['GET'])]
    public function indexB(ReservationCovoiturageRepository $reservationCovoiturageRepository): Response
    {
        return $this->render('reservation/indexB.html.twig', [
            'reservation_covoiturages' => $reservationCovoiturageRepository->findAll(),
        ]);
    }
    

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationCovoiturageRepository $reservationCovoiturageRepository): Response
    {
        $reservationCovoiturage = new ReservationCovoiturage();
        $form = $this->createForm(ReservationCovoiturageType::class, $reservationCovoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationCovoiturageRepository->save($reservationCovoiturage, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation_covoiturage' => $reservationCovoiturage,
            'form' => $form,
        ]);
    }


    #[Route('/reservation/new/{id}', name: 'app_reservation_new1', methods: ['GET', 'POST'])]
    public function newc(ManagerRegistry $doctrine , Request $request, CovoiturageRepository $CovoiturageRepository , ReservationCovoiturageRepository $ReservationCovoiturageRepository , int $id):Response
    {
        $em = $doctrine->getManager();
        $covoiturage = $CovoiturageRepository->find($id) ; 
    
        // Check if there are available seats
        if($covoiturage->getNbrplace() <= 0){
            $message = 'Sorry, this covoiturage is already full.';
            return new Response($message, 400);
            // // if($covoiturage->getNbrplace() <= 0){
            // //     $message = 'Sorry, this covoiturage is already full.';
            // //     return new JsonResponse(['message' => $message], 400);
            // }
        }
    
        $reservation = new ReservationCovoiturage();
        $reservation->setIdCov($covoiturage);
        $reservation->setDepart($covoiturage->getDepart());
        $reservation->setDestination($covoiturage->getDestination());
        $reservation->setPrixCovoiturage($covoiturage->getPrix());
    
        // Decrease the number of available seats
        $covoiturage->setNbrplace($covoiturage->getNbrplace() - 1);
        $ReservationCovoiturageRepository->save($reservation, true);
       
        $this->addFlash('success', 'Reservation added successfully.');
        return $this->redirectToRoute('app_reservation_index');
    } 
    





    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(ReservationCovoiturage $reservationCovoiturage): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation_covoiturage' => $reservationCovoiturage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationCovoiturage $reservationCovoiturage, ReservationCovoiturageRepository $reservationCovoiturageRepository): Response
    {
        $form = $this->createForm(ReservationCovoiturageType::class, $reservationCovoiturage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationCovoiturageRepository->save($reservationCovoiturage, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation_covoiturage' => $reservationCovoiturage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationCovoiturage $reservationCovoiturage, ReservationCovoiturageRepository $reservationCovoiturageRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationCovoiturage->getId(), $request->request->get('_token'))) {
            $covoiturage = $reservationCovoiturage->getIdCov();
            $covoiturage->setNbrplace($covoiturage->getNbrplace() + 1);
            $entityManager->persist($covoiturage);
            $entityManager->remove($reservationCovoiturage);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
    


   


}
