<?php

namespace App\Controller;

use App\Entity\ReservationChauffeur;

use App\Form\ReservationChauffeurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/reservation/chauffeur')]
class ReservationChauffeurController extends AbstractController
{
    #[Route('/', name: 'app_reservation_chauffeur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
        $reservationChauffeurs = $entityManager
            ->getRepository(ReservationChauffeur::class)
            ->findAll();

        return $this->render('reservation_chauffeur/index.html.twig', [
            'reservation_chauffeurs' => $reservationChauffeurs,
        ]);
    }

    #[Route('/new', name: 'app_reservation_chauffeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
        $reservationChauffeur = new ReservationChauffeur();
        $form = $this->createForm(ReservationChauffeurType::class, $reservationChauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationChauffeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_chauffeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_chauffeur/new.html.twig', [
            'reservation_chauffeur' => $reservationChauffeur,
            'form' => $form,
        ]);
    }

    #[Route('/{idReservationChauffeur}', name: 'app_reservation_chauffeur_show', methods: ['GET'])]
    public function show(ReservationChauffeur $reservationChauffeur): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
        return $this->render('reservation_chauffeur/show.html.twig', [
            'reservation_chauffeur' => $reservationChauffeur,
        ]);
    }

    #[Route('/{idReservationChauffeur}/edit', name: 'app_reservation_chauffeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationChauffeur $reservationChauffeur, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
        $form = $this->createForm(ReservationChauffeurType::class, $reservationChauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_chauffeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_chauffeur/edit.html.twig', [
            'reservation_chauffeur' => $reservationChauffeur,
            'form' => $form,
        ]);
    }

    #[Route('/{idReservationChauffeur}', name: 'app_reservation_chauffeur_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationChauffeur $reservationChauffeur, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
        if ($this->isCsrfTokenValid('delete'.$reservationChauffeur->getIdReservationChauffeur(), $request->request->get('_token'))) {
            $entityManager->remove($reservationChauffeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_chauffeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
