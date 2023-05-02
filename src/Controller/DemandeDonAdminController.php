<?php

namespace App\Controller;

use PHPExcel;

use IOFactory;
use Dompdf\Dompdf;
use App\Entity\Don;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\DemandeDon;
use App\Form\DemandeDonAdminType;
use App\Repository\DonRepository;
use App\Repository\UserRepository;
use App\Repository\DemandeDonRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DemandeDonAdminController extends AbstractController
{
    #[Route('/demande/don', name: 'app_demande_don')]
    public function index(): Response
    {
        return $this->render('demande_don/index.html.twig', [
            'controller_name' => 'DemandeDonController',
        ]);
    }





    #[Route('/Affichagedd', name: 'app_demandes_dons_back')]
    public function Affichagedemandesdons_back(Request $request, PaginatorInterface $paginator, DemandeDonRepository $rep, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $query = $request->query->get('search');

        if ($query) {
            $demandedons = $rep->searchdemandesdons($query);
        } else {
            $demandedons = $rep->findAll();
        }
        $paginatedDemandeDons = $paginator->paginate(
            $demandedons,
            $request->query->getInt('page', 1), // numéro de la page à afficher
            5 // nombre d'éléments par page
        );


        return $this->render('demande_don_admin/list.html.twig', [
            'demandedons' => $paginatedDemandeDons
        ]);
    }

    #[Route('/demandedon_admin/list', name: 'app_list_demandedon_admin')]
    public function getAll(DemandeDonRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $demandedons = $repo->findAll();
        $paginatedDemandeDons = $paginator->paginate(
            $demandedons,
            $request->query->getInt('page', 1), // numéro de la page à afficher
            5 // nombre d'éléments par page
        );

        return $this->render('demande_don_admin/list.html.twig', [
            'demandedons' => $paginatedDemandeDons,
        ]);
    }


    #[Route('/demandedon_admin/add/{id}', name: 'app_add_demandedon_admin')]
    public function adddemandedon(Request $request, ManagerRegistry $doctrine, UserRepository $repo, $id, DonRepository $repdon): Response
    {
        $demandedon = new DemandeDon();
        $form = $this->createForm(DemandeDonAdminType::class, $demandedon);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('justificatifHandicap')->getData();

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

                $demandedon->setJustificatifHandicap($newFilename);
                $demandedon->setDateDemande(date_create());
                $demandedon->setEtat("en cours");
                $demandedon->setIdDon($repdon->find($id));
                $demandedon->setTypeProduitDemande($repdon->find($id)->getType());
                $em->persist($demandedon);
                $em->flush();
                $this->addFlash('message', 'le demandedon a bien ete ajouter ');
                return $this->redirectToRoute("app_list_demandedon_admin");
            } else {
                $demandedon->setDateDemande(new \DateTime('now'));

                $demandedon->setEtat("en cours");
                $demandedon->setIdDon($repdon->find($id));
                $demandedon->setTypeProduitDemande($repdon->find($id)->getType());
                $em->persist($demandedon);
                $em->flush();
                $this->addFlash('message', 'la demande don a bien ete ajouter ');
                return $this->redirectToRoute("app_list_demandedon_admin");
            }
        }

        return $this->renderForm('demande_don_admin/add.html.twig', [
            'myForm' => $form
        ]);
    }





    #[Route('/demandedon_admin/remove/{id}', name: 'app_remove_demandedon_admin')]
    public function delete(DemandeDonRepository $repo, $id): Response
    {
        $cl = $repo->find($id);
        $repo->remove($cl, true);
        return $this->redirectToRoute("app_list_demandedon_admin");
    }



    #[Route('/demandedon_admin/update/{id}', name: 'app_update_demandedon_admin')]
    public function updatedon(Request $request, ManagerRegistry $doctrine, UserRepository $repo, DemandeDonRepository $repd, $id, DonRepository $repdon): Response
    {
        $id = $request->get('id');
        $repo = $doctrine->getRepository(DemandeDon::class);

        $demandedon = $repo->find($id);
        $form = $this->createForm(DemandeDonAdminType::class, $demandedon);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            /** @var UploadedFile $imageFile */

            //$demandedon->setDateDemande(new \DateTime('now'));
            // $demandedon->setIdUtilisateur($repo->find(7));
            // $demandedon->setEtat("en cours");
            // $demandedon->setIdDon($repdon->find($repd->find($id)->getIdDon()));
            $em->persist($demandedon);
            $em->flush();
            $this->addFlash('message', 'la demandedon a bien ete ajouter ');
            return $this->redirectToRoute("app_list_demandedon_admin");
        }

        return $this->renderForm('demande_don_admin/modifier.html.twig', [
            'myForm' => $form
        ]);
    }




    /*
    #[Route('/pdf2', name: 'app_pdf2')]
        public function PDF(DemandeDonRepository $repd, \Knp\Snappy\Pdf $pdf)
            {
                // Récupérez les données à afficher depuis votre source de données
                $data = $repd->findAll();
            
                // Générez le code HTML pour afficher les données
                $html = $this->renderView('demande_don_admin/pdf.html.twig', [
                    'demandedons' => $data,
                ]);
            
                // Générez une réponse PDF à partir du code HTML
                $response = new PdfResponse(
                    $pdf->getOutputFromHtml($html),
                    'Demandes_dons.pdf'
                );
            
                return $response;
            }
        */
    #[Route('/pdf/dons', name: 'PDF_demandesdons')]
    public function pdf(DemandeDonRepository $rep)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('fontDir', '/path/to/fonts'); // replace with the path to your font files
        $pdfOptions->set('fontCache', '/path/to/font/cache'); // replace with the path to your font cache directory

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('demande_don_admin/pdf.html.twig', [
            'demandedons' => $rep->findAll(),
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("ListeDesDemandesDons.pdf", [
            "demandedons" => true
        ]);
    }



    
    #[Route('/excel/dons', name: 'excel_demandesdons')]
    public function generateExcelFile(DemandeDonRepository $rep): Response
    {
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir le titre de la feuille
        $sheet->setTitle('Données de mon application');

        // Définir les en-têtes de colonnes
        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Prenom');
        $sheet->setCellValue('C1', 'Date de demande');
        $sheet->setCellValue('D1', 'Type de produit demandé');
        $sheet->setCellValue('E1', 'Remarques');
        $sheet->setCellValue('F1', 'état');

        // Récupérer les données de votre application
        $data = $rep->findAll();

        // Boucler sur les données et ajouter les valeurs aux cellules de la feuille
        $row = 2;
        foreach ($data as $item) {
            $user= $item->getIdUtilisateur();
            $date = $item->getDateDemande();
        $date_string = date_format($date, 'Y-m-d');

            $sheet->setCellValue('A' . $row,   $user->getNom() );
            $sheet->setCellValue('B' . $row,   $user->getPrenom() );
             $sheet->setCellValue('C' . $row, $date_string);
             $sheet->setCellValue('D' . $row, $item->getTypeProduitDemande());
             $sheet->setCellValue('E' . $row, $item->getRemarques());
             $sheet->setCellValue('F' . $row, $item->getEtat());
            $row++;
        }

        // Create a new XLsx writer and save the spreadsheet to a file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Demandes.xlsx';
        $writer->save($filename);
        $response = new Response(file_get_contents($filename));
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );
        $response->headers->set('Content-Disposition', $disposition);
        unlink($filename); // Delete the temporary file
        return $response;
    }


    #[Route('/donsstats2', name: 'dons_stats2')]
    public function stats(DonRepository $repository): Response
    {

        $dons = $repository->findAll();


        $dataParticipants = array();

        foreach ($dons as $don) {
            $dataParticipants[$don->getType()] = $repository->countByType($don->getType());

        }

        return $this->render('demande_don_admin/stats.html.twig', [
            'data_dd' => json_encode($dataParticipants)
        ]);
    }
    
}
