<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/insert', name: 'app_insert_user')]
    public function Add(ManagerRegistry $doctrine):Response
    {
        //1- préparer l'objet à ajouter
        $cl1=new User();
        $cl1->setNom("chaima");
        $cl1->setPrenom("lotfi");
        $cl1->setCin("12345678");
        $cl1->setEmail("lotfi@gmail.com");
        $cl1->setTelephone("25837256");
        $cl1->setLogin("chichi");
        $cl1->setMotDePasse("rfer");
        $cl1->setDateDeNaissance(new \DateTime('2000-03-27'));
        $cl1->setRegion("sfax");
        $cl1->setAdresse("tunis");
        $cl1->setCodePostal(2045);
        $cl1->setRole("locataire");
        $cl1->setCode("f2045");
      
       //2-ajouter mon objet
        //2.1- récupérer le gestionnairede l'entité
        $em=$doctrine->getManager();
        $em->persist($cl1); 
        $em->flush();
        return new Response("ok");
    
    }
}
