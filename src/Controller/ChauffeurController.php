<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Form\ChauffeurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\chauffeurRepository;
use App\Entity\PdfGeneratorChauffeur;



#[Route('/chauffeur')]
class ChauffeurController extends AbstractController
{
    #[Route('/', name: 'app_chauffeur_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $chauffeurs = $entityManager
            ->getRepository(Chauffeur::class)
            ->findAll();

        return $this->render('chauffeur/index.html.twig', [
            'chauffeurs' => $chauffeurs,
        ]);
    }

      #[Route('/front', name: 'front', methods: ['GET'])]
    public function front(EntityManagerInterface $entityManager): Response
    {
      
        return $this->render('baseF.html.twig');
    }


    #[Route('/show_in_map/{id}', name: 'app_chauffeur_map', methods: ['GET'])]
    public function Map(Chauffeur $chauffeur ): Response
    {
        return $this->render('chauffeur/api_arcgis.html.twig', [
            'chauffeurs' => [$chauffeur],
        ]);
    }
    

    #[Route('/pdf', name: 'generator_service3')]
    public function pdfChauffeur(EntityManagerInterface $entityManager): Response
{
    $chauffeur = $entityManager->getRepository(Chauffeur::class)
        ->findAll();


    $html = $this->renderView('pdf/index.html.twig', ['Chauffeur' => $chauffeur ]);
    $pdfGeneratorChauffeur = new PdfGeneratorChauffeur();
    $pdf = $pdfGeneratorChauffeur->generatePdf($html);


    return new Response($pdf, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="document.pdf"',
    ]);
}



    #[Route('/base', name: 'app_chauffeur_indexbase', methods: ['GET','POST'])]
    public function base(EntityManagerInterface $entityManager, chauffeurRepository $chauffeurRepository, Request $request): Response
    {
        $chauffeurs = $entityManager
            ->getRepository(chauffeur::class)
            ->findAll();


            ////////
        $base = null;
       
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                

                    case 'nom':
                        $chauffeurs = $chauffeurRepository->SortBynom();
                        break;


                    case 'adresse':
                        $chauffeurs = $chauffeurRepository->SortByadresse();
                        break;




                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'nom':
                        $chauffeurs = $chauffeurRepository->findBynom($value);
                        break;


                    case 'adresse':
                        $chauffeurs = $chauffeurRepository->findByadresse($value);
                        break;


                    case 'statutDisponibilite':
                        $chauffeurs = $chauffeurRepository->findBystatutDisponibilite($value);
                        break;




                }
            }


            if ( $chauffeurs){
                $base = "success";
            }else{
                $base = "failure";
            }
        }
            ////////


        return $this->render('chauffeur/indexbase.html.twig', [
            'chauffeurs' => $chauffeurs,
        ]);
    }


    #[Route('/new', name: 'app_chauffeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chauffeur = new Chauffeur();
        $form = $this->createForm(ChauffeurType::class, $chauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chauffeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_chauffeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chauffeur/new.html.twig', [
            'chauffeur' => $chauffeur,
            'form' => $form,
        ]);
    }

    #[Route('/{idChauffeur}', name: 'app_chauffeur_show', methods: ['GET'])]
    public function show(Chauffeur $chauffeur): Response
    {
        return $this->render('chauffeur/show.html.twig', [
            'chauffeur' => $chauffeur,
        ]);
    }

    #[Route('/{idChauffeur}/edit', name: 'app_chauffeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chauffeur $chauffeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChauffeurType::class, $chauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chauffeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chauffeur/edit.html.twig', [
            'chauffeur' => $chauffeur,
            'form' => $form,
        ]);
    }

    #[Route('/{idChauffeur}', name: 'app_chauffeur_delete', methods: ['POST'])]
    public function delete(Request $request, Chauffeur $chauffeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chauffeur->getIdChauffeur(), $request->request->get('_token'))) {
            $entityManager->remove($chauffeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chauffeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
