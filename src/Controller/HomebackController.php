<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomebackController extends AbstractController
{
    #[Route('/homeback', name: 'app_homeback')]
    public function index(): Response
    {
        return $this->render('homeback/index.html.twig', [
            'controller_name' => 'HomebackController',
        ]);
    }
}