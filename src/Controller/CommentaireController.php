<?php

namespace App\Controller;
use App\Entity\Sujet;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Repository\SujetRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use JCrowe\BadWordFilter\BadWordFilter;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {   
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }
    #[Route('/sujet/{id}/commentaire/new', name: 'app_commentaire_new' , methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $entityManager, Request $request, CommentaireRepository $commentaireRepository, SujetRepository $sujetRepository, $id,SluggerInterface $slugger, UserRepository $userRepository): Response
    {   
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        dump($commentaire); 
        $sujet = $sujetRepository->find($id);
        
        if (!$sujet) {
            throw $this->createNotFoundException('Sujet non trouvé');
        }
    
        $commentaire->setSujet($sujet);
        $commentaire->setDatePublication(new \DateTime());
        
        //$user = $userRepository->find(14); // ou récupérez l'ID de l'utilisateur à partir d'un formulaire ou de la session
        $user = $this->getUser();

        $commentaire->setUser($user);
        $commentaire->setNbMentions(0);

        if ($form->isSubmitted() && $form->isValid()) {
            $sujet->setNbCommentaires($sujet->getNbCommentaires() + 1);
            $entityManager->persist($sujet);
            $entityManager->flush();
            $commentaire = $form->getData();
            $contenuCommentaire = $commentaire->getContenuCommentaire();
            // Ajoutez cette ligne pour voir si elle est exécutée
            var_dump('Contenu du commentaire avant nettoyage : ' . $commentaire->getNbMentions());
            $badWords = ['badword1', 'badword2', 'badword3'];
            $badWordFilter = new BadWordFilter($badWords);
            //$filteredText = $badWordFilter->cleanString($commentaire->getContenuCommentaire(),"*");
            //var_dump('Contenu du commentaire après nettoyage : ' . $filteredText);
            $commentaire->setContenuCommentaire($badWordFilter->cleanString($commentaire->getContenuCommentaire(),"*"));
            
            $imageFile = $form->get('piecejointe')->getData();
            
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dd("erreur " +  $e->toString());
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $commentaire->setPiecejointe($newFilename);
            }
            
            $commentaireRepository->save($commentaire, true);
            return $this->redirectToRoute('app_sujet_show', ['idSujet' => $commentaire->getSujet()->getIdSujet()], Response::HTTP_SEE_OTHER);
        }else {
            // Le formulaire n'a pas été soumis ou les données ne sont pas valides, on peut afficher les erreurs
            $errors = $form->getErrors(true, false);
        }
        return $this->renderForm('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]); 
    }

    #[Route('/{idCommentaire}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{idCommentaire}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository, SluggerInterface $slugger): Response
    {   
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire = $form->getData();
            $badWords = ['badword1', 'badword2', 'badword3'];
            $badWordFilter = new BadWordFilter($badWords);
            //$filteredText = $badWordFilter->cleanString($commentaire->getContenuCommentaire(),"*");
            //var_dump('Contenu du commentaire après nettoyage : ' . $filteredText);
            $commentaire->setContenuCommentaire($badWordFilter->cleanString($commentaire->getContenuCommentaire(),"*"));
            $imageFile = $form->get('piecejointe')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    dd("erreur " +  $e->toString());
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $commentaire->setPiecejointe($newFilename);
            }
    
            $commentaireRepository->save($commentaire, true);

            return $this->redirectToRoute('app_sujet_show', ['idSujet' => $commentaire->getSujet()->getIdSujet()], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{idCommentaire}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {  
        $sujet = $commentaire->getSujet();
        if ($this->isCsrfTokenValid('delete'.$commentaire->getIdCommentaire(), $request->request->get('_token'))) {
            $commentaireRepository->remove($commentaire, true);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commentaire);
        $entityManager->flush();
        $sujet->decrementNbCommentaires();
        $entityManager->persist($sujet);
        $entityManager->flush();

        
           
        return $this->redirectToRoute('app_sujet_show', ['idSujet' => $commentaire->getSujet()->getIdSujet()], Response::HTTP_SEE_OTHER);
    }

    public function isLiked(Commentaire $commentaire, CommentaireRepository $commentaireRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new Response(null, 401);
        }

        $isLiked = $commentaireRepository->getIsLiked($commentaire, $user);

        return $this->json(['isLiked' => $isLiked]);
    }

    #[Route('/{idCommentaire}/like', name: 'like_commentaire', methods: ['POST'])]
    public function likeCommentaire(Request $request, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager, int $idCommentaire): Response
    {
        // Trouvez le commentaire correspondant à l'id donné
        $commentaire = $commentaireRepository->find($idCommentaire);
        if (!$commentaire) {
            return new Response('Le commentaire n\'existe pas', 404);
        }

        // Incrémentez le nombre de likes et définissez isLiked sur true
        $commentaire->setNbMentions($commentaire->getNbMentions() + 1);

        // Enregistrez les changements dans la base de données
        $entityManager->flush($commentaire);

        return new Response('Merci d\'avoir aimé ce commentaire');
    }
    #[Route('/{idCommentaire}/dislike', name: 'dislike_commentaire', methods: ['POST'])]
    public function dislikeCommentaire(Request $request, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager, int $idCommentaire): Response
    {
        // Trouvez le commentaire correspondant à l'id donné
        $commentaire = $commentaireRepository->find($idCommentaire);
        if (!$commentaire) {
            return new Response('Le commentaire n\'existe pas', 404);
        }

        // Décrémentez le nombre de likes et définissez isLiked sur false
        $commentaire->setNbMentions($commentaire->getNbMentions() - 1);

        // Enregistrez les changements dans la base de données
        $entityManager->flush($commentaire);

        return new Response('Le like a été retiré avec succès');
    }


}