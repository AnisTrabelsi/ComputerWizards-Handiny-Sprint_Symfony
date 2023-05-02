<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomefrontController extends AbstractController
{
    #[Route('/homefront', name: 'app_homefront')]
    public function index(): Response
    {
        return $this->render('baseF.html.twig', [
            'controller_name' => 'HomefrontController',
        ]);
    }
}
