<?php

namespace App\Controller;

use App\Entity\Postssauvegardés;
use App\Form\PostssauvegardésType;
use App\Repository\PostssauvegardésRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/Postssauvegardes')]
class PostssauvegardésController extends AbstractController
{
    #[Route('/', name: 'app_postssauvegard_s_index', methods: ['GET'])]
    public function index(PostssauvegardésRepository $postssauvegardésRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $sauv = $postssauvegardésRepository->findBy(['user' => $userId]);
    
        return $this->render('postssauvegardés/index.html.twig', [
            'postssauvegard_s' => $sauv,
        ]);
    }
    
    #[Route('/new', name: 'app_postssauvegard_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PostssauvegardésRepository $postssauvegardésRepository): Response
    {
        $postssauvegardé = new Postssauvegardés();
        $form = $this->createForm(PostssauvegardésType::class, $postssauvegardé);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postssauvegardésRepository->save($postssauvegardé, true);

            return $this->redirectToRoute('app_postssauvegard_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('postssauvegardés/new.html.twig', [
            'postssauvegard_' => $postssauvegardé,
            'form' => $form,
        ]);
    }

    #[Route('/{idPostSauvegarde}', name: 'app_postssauvegard_s_show', methods: ['GET'])]
    public function show(Postssauvegardés $postssauvegardé): Response
    {
        return $this->render('postssauvegardés/show.html.twig', [
            'postssauvegard_' => $postssauvegardé,
        ]);
    }

    #[Route('/{idPostSauvegarde}/edit', name: 'app_postssauvegard_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Postssauvegardés $postssauvegardé, PostssauvegardésRepository $postssauvegardésRepository): Response
    {
        $form = $this->createForm(PostssauvegardésType::class, $postssauvegardé);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postssauvegardésRepository->save($postssauvegardé, true);

            return $this->redirectToRoute('app_postssauvegard_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('postssauvegardés/edit.html.twig', [
            'postssauvegard_' => $postssauvegardé,
            'form' => $form,
        ]);
    }

    #[Route('/{idPostSauvegarde}', name: 'app_postssauvegard_s_delete', methods: ['POST'])]
    public function delete(Request $request, Postssauvegardés $postssauvegardé, PostssauvegardésRepository $postssauvegardésRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$postssauvegardé->getIdPostSauvegarde(), $request->request->get('_token'))) {
            $postssauvegardésRepository->remove($postssauvegardé, true);
        }

        return $this->redirectToRoute('app_postssauvegard_s_index', [], Response::HTTP_SEE_OTHER);
    }

    /*#[Route('/Rechercher', name: 'app_sujet_search3', methods: ['GET'])]
    public function search3(Request $request, PostssauvegardésRepository $postssauvegardésRepository, ManagerRegistry $doctrine): Response
    {   
        $em = $doctrine->getManager();
        $query = $request->query->get('search');

        if ($query) {
            $sauv = $postssauvegardésRepository->searchSauv($query);
        } else {
            $sauv = $postssauvegardésRepository->findAll();
        }        

        if (empty($sauv)) {
            dump($sauv);
            $this->addFlash('warning', 'Aucun résultat trouvé pour votre recherche.');
            return $this->redirectToRoute('app_postssauvegard_s_index');
        }

        return $this->render('postssauvegardés/index.html.twig', [
            'postssauvegard_s' => $sauv,
        ]);
    }*/
}
