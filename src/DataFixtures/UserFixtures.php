<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cl1=new User();
        $cl1->setNom("chayma");
        $cl1->setPrenom("ben saad");
        $cl1->setCin("88775566");
        $cl1->setEmail("chayma.bensaad@gmail.com");
        $cl1->setTelephone("99663322");
        $cl1->setLogin("chaymaaa");
        $cl1->setMotDePasse("aabbvv");
        $cl1->setDateDeNaissance(new \DateTime('2000-12-20'));
        $cl1->setRegion("Gabes");
        $cl1->setAdresse("tunis");
        $cl1->setCodePostal(5588);
        $cl1->setRoles("locataire");
      
       //2-ajouter mon objet
        //2.1- récupérer le gestionnairede l'entité
       
        $manager->persist($cl1); 

        $manager->flush();
    }
}
