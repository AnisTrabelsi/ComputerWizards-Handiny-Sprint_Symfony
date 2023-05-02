<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HomefrontController extends AbstractController
{
    #[Route('/homefront', name: 'app_homefront')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
      if ($this->isGranted('ROLE_ADMIN')) {
                // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
                return $this->redirectToRoute('app_homback');
            }
    
        return $this->render('baseF.html.twig', [
            'controller_name' => 'HomefrontController',
        ]);
    }
    #[Route('/homeback', name: 'app_homback')]
    public function indexb(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');

            throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('app_homeback');
        }
        return $this->render('baseB.html.twig', [
            'controller_name' => 'HomefrontController',
        ]);
    }

    #[Route('/auth', name: 'authentification_genrale')]
    public function authentificationGe(): Response
    {
       
        return $this->render('homefront/authentificationgenerale.html.twig', [
            'controller_name' => 'HomefrontController',
        ]);
    }
}
