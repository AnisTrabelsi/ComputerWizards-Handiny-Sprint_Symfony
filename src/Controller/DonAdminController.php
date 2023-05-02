<?php

namespace App\Controller;

//use Dompdf\Dompdf;

use App\Entity\Don;
//use Dompdf\Options;
use App\Form\DonAdminType;
use App\Form\RecherchedonType;
use App\Repository\DonRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class DonAdminController extends AbstractController
{
    #[Route('/don_admin', name: 'app_don_admin')]
    public function index(): Response
    {
        return $this->render('don_admin/index.html.twig', [
            'controller_name' => 'DonController',
        ]);
    }


    #[Route('/don_admin/list', name: 'app_list_don_admin')]
    public function getAll(DonRepository $repo, PaginatorInterface $paginator, Request $request, Request $request2, Request $request3): Response
    {
        $dons = $repo->findAll();
        $paginatedDons = $paginator->paginate(
            $dons,
            $request->query->getInt('page', 1), // numéro de la page à afficher
            5 // nombre d'éléments par page
        );


        return $this->render(
            'don_admin/list.html.twig',
            array('dons' => $paginatedDons)
        );
    }






    #[Route('/don_admin/add', name: 'app_add_don_admin')]
    public function adddon(Request $request, ManagerRegistry $doctrine, UserRepository $repo): Response
    {
        $don = new Don();
        $form = $this->createForm(DonAdminType::class, $don);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageDon')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                
                $don->setImageDon($newFilename);
                $don->setDateAjout(date_create());

                $em->persist($don);
                $em->flush();
                $this->addFlash('message', 'le don a bien ete ajouter ');
                return $this->redirectToRoute("app_list_don_admin");
            } else {
                $don->setDateAjout(new \DateTime('now'));
              
                $em->persist($don);
                $em->flush();
                $this->addFlash('message', 'le don a bien ete ajouter ');
                return $this->redirectToRoute("app_list_don_admin");
            }
        }

        return $this->renderForm('don_admin/add.html.twig', [
            'myForm' => $form
        ]);
    }





    #[Route('/don_admin/remove/{id}', name: 'app_remove_don_admin')]
    public function delete(DonRepository $repo, $id): Response
    {
        $cl = $repo->find($id);
        $repo->remove($cl, true);
        return $this->redirectToRoute("app_list_don_admin");
    }



    #[Route('/don_admin/update/{id}', name: 'app_update_don_admin')]
    public function updatedon(Request $request, ManagerRegistry $doctrine, UserRepository $repo): Response
    {
        $id = $request->get('id');
        $repo = $doctrine->getRepository(Don::class);

        $don = $repo->find($id);
        $form = $this->createForm(DonAdminType::class, $don);



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('imageDon')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $don->setImageDon($newFilename);
                // $don->setDateAjout(date_create());

                $em->persist($don);
                $em->flush();
                $this->addFlash('message', 'le don a bien ete ajoute ');
                return $this->redirectToRoute("app_list_don_admin");
            } else {

                // $don->setDateAjout(new \DateTime('now'));

                $em->persist($don);
                $em->flush();
                $this->addFlash('message', 'le don a bien ete ajouter ');
                return $this->redirectToRoute("app_list_don_admin");
            }
        }

        return $this->renderForm('don_admin/modifier.html.twig', [
            'myForm' => $form
        ]);
    }



    #[Route('/Affichagedons_back', name: 'app_dons_index_back')]
    public function Affichagedemandes_dons_back(Request $request,DonRepository $rep,ManagerRegistry $doctrine,PaginatorInterface $paginator): Response
    {
        $em = $doctrine->getManager();
        $query = $request->query->get('search');
    
        if ($query) {
            $dons = $rep->searchdons($query);
        } else {
            $dons = $rep->findAll();
        }
    
        $paginatedDons = $paginator->paginate(
            $dons,
            $request->query->getInt('page', 1), // numéro de la page à afficher
            5 // nombre d'éléments par page
        );        
        return $this->render('don_admin/list.html.twig', [
            'dons' => $paginatedDons
        ]);
    }

   
    #[Route('/stat_don', name: 'stat_don')]
    public function statistiques(DonRepository $Repo){
        // On va chercher toutes les catégories
        $types = $Repo->findDistinctTypes();
    
        $donType = [];
        $TypeCount = [];
    
        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($types as $type){
            
                $donType[] = $type;
    
                $TypeCount[] = $Repo->countByType($type);
            
        }
    
        return $this->render('don_admin/stats.html.twig', [
            'donType' => json_encode($donType),
            'TypeCount' =>  json_encode($TypeCount) ,
        ]);
    }
    

    #[Route('/donstats', name: 'dons_stats')]
    public function stats(DonRepository $repository): Response
    {

        $dons = $repository->findAll();


        $dataParticipants = array();

        foreach ($dons as $don) {
            $dataParticipants[$don->getType()] = $repository->countByType($don->getType());

        }

        return $this->render('don_admin/stats.html.twig', [
            'data_dons' => json_encode($dataParticipants)
        ]);
    }
    
    
    
    
    

    








  /*
    #[Route('/pdf/don', name: 'PDF_dons')]
    public function pdf(DonRepository $donRepository)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('don_admin/pdf.html.twig', [
            'dons' => $donRepository->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("ListeDesDons.pdf", [
            "dons" => true
        ]);
    }*/
}
