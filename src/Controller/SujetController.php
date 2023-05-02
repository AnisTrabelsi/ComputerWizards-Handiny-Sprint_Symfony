<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Postssauvegardés;
use App\Entity\Sujet;
use App\Form\SujetType;
use App\Repository\SujetRepository;
use App\Repository\PostssauvegardésRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Dompdf\Options;
use Dompdf\Dompdf;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\Loader\Configurator\mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


#[Route('/sujet')]
class SujetController extends AbstractController
{   
    #[Route('/reglements/generate-pdf', name: 'app_reglements_generate_pdf')]
    public function generatePdf(Request $request)
    {
        // Configure Dompdf according to your needs
     $pdfOptions = new Options();
     $pdfOptions->set('defaultFont', 'Arial');
     $pdfOptions->set('defaultFont', 'Arial');
     
     // Instantiate Dompdf with our options
     $dompdf = new Dompdf($pdfOptions);
     // Retrieve the HTML generated in our twig file
     $html = $this->renderView('sujet/ReglementsPDF.html.twig');

     // Load HTML to Dompdf
     $dompdf->loadHtml($html);
     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
     $dompdf->setPaper('A3', 'portrait');

     // Render the HTML as PDF
     $dompdf->render();
     // Get the generated PDF content
     $pdfContent = $dompdf->output();
     // Create a Response object containing the PDF content
     $response = new Response($pdfContent);
     // Set the headers to force the browser to download the PDF
     $response->headers->set('Content-Type', 'application/pdf');
     $response->headers->set('Content-Disposition', 'attachment;filename="Reglements.pdf"');
     
     $this->addFlash('success', 'Réglements téléchargé avec succès.');
    return $response;
     //return $this->redirectToRoute('app_reglements', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reglements', name: 'app_reglements', methods: ['GET'])]
    public function reglements(Request $request)
    {
        return $this->render('sujet/reglements.html.twig'); 
    }
    
    #[Route('/', name: 'app_sujet_index', methods: ['GET'])]
    public function index(Request $request, SujetRepository $sujetRepository, CategorieRepository $categorieRepository, PaginatorInterface $paginator): Response
    {   
        $sujets = $sujetRepository->findAll();
        $latest = $sujetRepository->findLatestSujets();
        $tags = [];
        foreach ($sujets as $sujet) {
            $tags = array_merge($tags, $sujet->getTags2());
        }
        $tags = array_unique($tags);
        
            // Récupérer le tag sélectionné depuis l'URL
        $tagSelected = $request->query->get('tags');

        // Filtrer les sujets par tag sélectionné, s'il y a un tag sélectionné
        if (!empty($tagSelected)) {
            $sujets = $sujetRepository->findByTag($tagSelected);
        }

        //sujets par catégorie
        $categoryId = $request->query->getInt('categoryId', 0);
        if ($categoryId > 0) {
            $sujets = $sujetRepository->findBy(['categorie' => $categoryId]);
        } else {
            $sujets = $sujetRepository->findAll();
        }
        
         // trier les sujets par ordre alphabétique du nom
        // Vérifier si un paramètre de tri est présent dans la requête
        $sort = $request->query->get('sort');

        // Si le paramètre de tri est présent et égal à "date_creation", trier les sujets par date de création
        if ($sort === 'date_creation') {
            $sujets = $sujetRepository->TriParDate_Creation();
        }

        //pagination
        $sujetsPaginated = $paginator->paginate($sujets, $request->query->getInt('page', 1), 3);
        $paginationData = $sujetsPaginated->getPaginationData();
        $pagesInRange = $paginationData['pagesInRange'];
        $lastPageNumber = $sujetsPaginated->getPageCount();

        if ($request->isXmlHttpRequest()) {
            // Si oui, retourner la vue des voitures triées ou filtrées uniquement
            return $this->render('sujet/paginationsujets.html.twig', [
                'sujets' => $sujetsPaginated,
                'categories' => $categorieRepository->findAll(),
                'tags' => $tags,
                'pagesInRange' => $pagesInRange,
                'lastPageNumber'=>$lastPageNumber,
                'latest' =>$latest,
                ]);
        }
        else {
        
            return $this->render('sujet/index.html.twig', [
                'sujets' => $sujetsPaginated,
                'categories' => $categorieRepository->findAll(),
                'tags' => $tags,
                'pagesInRange' => $pagesInRange,
                'lastPageNumber'=>$lastPageNumber,
                'latest' =>$latest,
            ]); 
        }
    }
    
    #[Route('/messujets', name: 'app_sujet_messujets', methods: ['GET'])]
    public function messujets(Request $request, PaginatorInterface $paginator, SujetRepository $sujetRepository, CategorieRepository $categorieRepository): Response
    {   
        $sujets = $sujetRepository->findSujetsByUser(14);
        //pagination
        $sujetsPaginated = $paginator->paginate($sujets, $request->query->getInt('page', 1), 3);
        $paginationData = $sujetsPaginated->getPaginationData();
        $pagesInRange = $paginationData['pagesInRange'];
        $lastPageNumber = $sujetsPaginated->getPageCount();

        if ($request->isXmlHttpRequest()) {
            // Si oui, retourner la vue des voitures triées ou filtrées uniquement
            return $this->render('sujet/paginationsujets.html.twig', [
                'sujets' => $sujetsPaginated,
                'pagesInRange' => $pagesInRange,
                'lastPageNumber'=>$lastPageNumber,
                ]);
        }
        return $this->render('sujet/messujets.html.twig', [
            'sujets' => $sujetsPaginated,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
        ]);
    }
    #[Route('/sujetsListe', name: 'app_sujet_liste', methods: ['GET'])]
    public function listeSujets(SujetRepository $sujetRepository, CategorieRepository $categorieRepository): Response
    {   
        $sujets = $sujetRepository->findAll();
        return $this->render('sujet/listeBack.html.twig', [
            'sujets' => $sujets
        ]);
    }

    #[Route('/new', name: 'app_sujet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SujetRepository $sujetRepository, UserRepository $userRepository): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);
        $sujet->setDateCreationSujet(new \DateTime());
    
        if ($form->isSubmitted() && $form->isValid()) {
            $sujet->getCategorie()->setNbSujets($sujet->getCategorie()->getNbSujets()+1);
            $user = $userRepository->find(14); // or get the user ID from somewhere else
            $sujet->setUser($user);
            $sujet->setNbCommentaires(0);
            $etat = $request->request->get('etat');
            
            if(isset($etat) && !empty($etat)){
                if ($etat === 'ouvert') {
                    $sujet->setEtat('ouvert');
                } elseif ($etat === 'ferme') {
                    $sujet->setEtat('ferme');
                } elseif ($etat === 'bloque') {
                    $sujet->setEtat('bloque');
                }
            }
            $sujetRepository->save($sujet, true);
    
            return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
        }
        
    
        return $this->renderForm('sujet/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);    
    }




    #[Route('/{idSujet}', name: 'app_sujet_show', methods: ['GET'])]
    public function show(Sujet $sujet): Response
    {   $commentaires = $this->getDoctrine()
        ->getRepository(Commentaire::class)
        ->findBy(['sujet' => $sujet]);

        // Récupérer le commentaire le plus aimé
        $mostLikedComment = $this->getDoctrine()->getRepository(Commentaire::class)->findCommentaireWithMaxMentions($sujet);


        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,
            'commentaires' => $commentaires,
            'mostLikedComment' => $mostLikedComment,
        ]);
    }

    #[Route('/{idSujet}/edit', name: 'app_sujet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sujet $sujet, SujetRepository $sujetRepository): Response
    {
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sujetRepository->save($sujet, true);

            return $this->redirectToRoute('app_sujet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form,
        ]);
    }

    #[Route('/{idSujet}', name: 'app_sujet_delete', methods: ['POST'])]
    public function delete(Request $request, Sujet $sujet, SujetRepository $sujetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getIdSujet(), $request->request->get('_token'))) {
            $sujetRepository->remove($sujet, true);
        }

        return $this->redirectToRoute('app_sujet_messujets', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{idSujet}/sauvegarder', name: 'app_sujet_sauvegarder', methods: ['GET'])]
    public function sauvegarder(Request $request, SujetRepository $sujetsRepository, PostssauvegardésRepository $sujetsSauvegardesRepository, $idSujet, UserRepository $userRepository, SessionInterface $session): Response
    {
        $sujet = $sujetsRepository->find($idSujet);

        if (!$sujet) {
            throw $this->createNotFoundException('Sujet non trouvé');
        }
    
        $user = $userRepository->find(14); // or get the user ID from somewhere else
        $sujetSauvegarde = $sujetsSauvegardesRepository->findOneBy(['user' => $user, 'sujet' => $sujet]);

        if ($sujetSauvegarde) {
            $session->getFlashBag()->add('error', 'Ce sujet est déjà sauvegardé.');
        } else {
            $sujetSauvegarde = new Postssauvegardés();
            $sujetSauvegarde->setUser($user);
            $sujetSauvegarde->setSujet($sujet);
            $sujetSauvegarde->setDateAjoutSauvegarde(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sujetSauvegarde);
            $entityManager->flush();
            $session->getFlashBag()->add('success', 'Sujet enregistré avec succès.');
        }

        return $this->redirectToRoute('app_postssauvegard_s_index');
    }

    #[Route('/Rechercher', name: 'app_sujet_search', methods: ['GET'])]
    public function search(PaginatorInterface $paginator, Request $request, SujetRepository $sujetsRepository, ManagerRegistry $doctrine, CategorieRepository $categorieRepository): Response
    {   
        $em = $doctrine->getManager();
        $query = $request->query->get('search');

        if ($query) {
            $sujets = $sujetsRepository->searchSujets($query);
        } else {
            $sujets = $sujetsRepository->findAll();
        }
        
        $latest = $sujetsRepository->findLatestSujets();
        $tags = [];
        foreach ($sujets as $sujet) {
            $tags = array_merge($tags, $sujet->getTags2());
        }
        $tags = array_unique($tags);
        

        if (empty($sujets)) {
            dump($sujets);
            $this->addFlash('warning', 'Aucun résultat trouvé pour votre recherche.');
        }

        //pagination
        $sujetsPaginated = $paginator->paginate($sujets, $request->query->getInt('page', 1), 3);
        $paginationData = $sujetsPaginated->getPaginationData();
        $pagesInRange = $paginationData['pagesInRange'];
        $lastPageNumber = $sujetsPaginated->getPageCount();

        if ($request->isXmlHttpRequest()) {
            // Si oui, retourner la vue des voitures triées ou filtrées uniquement
            return $this->render('sujet/paginationsujets.html.twig', [
                'sujets' => $sujetsPaginated,
                'categories' => $categorieRepository->findAll(),
                'tags' => $tags,
                'pagesInRange' => $pagesInRange,
                'lastPageNumber'=>$lastPageNumber,
                'latest' =>$latest,
                ]);
        }

        return $this->render('sujet/index.html.twig', [
            'sujets' => $sujetsPaginated,
            'categories' => $categorieRepository->findAll(),
            'tags' => $tags,
            'latest' =>$latest,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
        ]);
    }

    #[Route('/Rechercher2', name: 'app_sujet_search2', methods: ['GET'])]
    public function search2(PaginatorInterface $paginator,Request $request, SujetRepository $sujetsRepository, ManagerRegistry $doctrine): Response
    {   
        $em = $doctrine->getManager();
        $query = $request->query->get('search');

        if ($query) {
            $sujets = $sujetsRepository->searchSujets($query);
        } else {
            $sujets = $sujetsRepository->findAll();
        }        

        if (empty($sujets)) {
            dump($sujets);
            $this->addFlash('warning', 'Aucun résultat trouvé pour votre recherche.');
        }
                //pagination
                $sujetsPaginated = $paginator->paginate($sujets, $request->query->getInt('page', 1), 3);
                $paginationData = $sujetsPaginated->getPaginationData();
                $pagesInRange = $paginationData['pagesInRange'];
                $lastPageNumber = $sujetsPaginated->getPageCount();
        
                if ($request->isXmlHttpRequest()) {
                    // Si oui, retourner la vue des voitures triées ou filtrées uniquement
                    return $this->render('sujet/paginationmessujets.html.twig', [
                        'sujets' => $sujetsPaginated,
                        'categories' => $categorieRepository->findAll(),
                        'tags' => $tags,
                        'pagesInRange' => $pagesInRange,
                        'lastPageNumber'=>$lastPageNumber,
                        'latest' =>$latest,
                        ]);
                }

        return $this->render('sujet/messujets.html.twig', [
            'sujets' => $sujetsPaginated,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
        ]);
    }
    #[Route('/Rechercher4', name: 'app_sujet_search4', methods: ['GET'])]
    public function search3(PaginatorInterface $paginator,Request $request, SujetRepository $sujetsRepository, ManagerRegistry $doctrine): Response
    {   
        $em = $doctrine->getManager();
        $query = $request->query->get('search');

        if ($query) {
            $sujets = $sujetsRepository->searchSujets($query);
        } else {
            $sujets = $sujetsRepository->findAll();
        }        

        if (empty($sujets)) {
            dump($sujets);
            $this->addFlash('warning', 'Aucun résultat trouvé pour votre recherche.');
        }
         /*       //pagination
                $sujetsPaginated = $paginator->paginate($sujets, $request->query->getInt('page', 1), 3);
                $paginationData = $sujetsPaginated->getPaginationData();
                $pagesInRange = $paginationData['pagesInRange'];
                $lastPageNumber = $sujetsPaginated->getPageCount();
        
                if ($request->isXmlHttpRequest()) {
                    // Si oui, retourner la vue des voitures triées ou filtrées uniquement
                    return $this->render('sujet/paginationmessujets.html.twig', [
                        'sujets' => $sujetsPaginated,
                        'categories' => $categorieRepository->findAll(),
                        'tags' => $tags,
                        'pagesInRange' => $pagesInRange,
                        'lastPageNumber'=>$lastPageNumber,
                        'latest' =>$latest,
                        ]);
                }
                */
        return $this->render('sujet/listeBack.html.twig', [
            'sujets' => $sujets,
            //'pagesInRange' => $pagesInRange,
            //'lastPageNumber'=>$lastPageNumber,
        ]);
    }
    #[Route('/{idSujet}/signaler', name: 'signaler_sujet', methods: ['GET', 'POST'])]
    public function signaler($idSujet, Request $request, SujetRepository $sujetRepository, MailerInterface $mailer): Response
    {   
        $sujet=$sujetRepository->find($idSujet);
        var_dump($sujet->getUser()->getNom());
            $email = (new Email())
            ->from('benghanemoumaima@gmail.com')
            ->to('oumaima.benghanem@esprit.tn')
            ->subject('Votre sujet est signalé !')
            ->html('<h1>Bonjour, '. $sujet->getUser()->getNom(). '!</h1>
            <p>Votre sujet de titre <h3>'. $sujet->getTitreSujet() .'</h3> est inapproprié.</p>
            <p>Nous avons effacé votre sujet à cause de beaucoups de signals reçus.</p>');

            $mailer->send($email);
            $this->addFlash('success', 'Un email est envoyé au M/Mme '.$sujet->getUser()->getNom().' avec succès.');
        if ($this->isCsrfTokenValid('delete'.$sujet->getIdSujet(), $request->request->get('_token'))) {
            $sujetRepository->remove($sujet, true);
        }
        
        return $this->redirectToRoute('app_sujet_liste', [], Response::HTTP_SEE_OTHER);
    }
}