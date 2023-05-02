<?php

namespace App\Controller;
use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;
use App\Entity\Don;
use Swift_Attachment;
use App\Entity\User;
use App\Entity\DemandeDon;
use App\Form\DemandeDonType;
use App\Repository\DonRepository;
use App\Repository\UserRepository;
use Endroid\QrCode\Builder\Builder;
use Symfony\Component\Mime\Message;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Mailer\Mailer;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Margin\Margin;
use App\Repository\DemandeDonRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class DemandeDonController extends AbstractController
{
    #[Route('/demande/don', name: 'app_demande_don')]
    public function index(): Response
    {
        return $this->render('demande_don/index.html.twig', [
            'controller_name' => 'DemandeDonController',
        ]);
    }



    
    #[Route('/demandedon/list', name: 'app_list_demandedon')]
    public function getAll(DemandeDonRepository $repo,PaginatorInterface $paginator,Request $request) :Response
    {      $user =new User();
        //$user->setId(7);
        $user = $this->getUser();
        $demandedons = $repo->findByidUtilisateur($user);
        $paginatedDemandeDons = $paginator->paginate(
            $demandedons,
            $request->query->getInt('page', 1), // numéro de la page à afficher
            3 // nombre d'éléments par page
        );

        return $this->render('demande_don/list.html.twig', [
            'demandedons' => $paginatedDemandeDons,
        ]);
    }

    #[Route('/qr/{id}', name: 'qr')]
    public function qr($id,DemandeDonRepository $rep)
    {
        $dd=$rep->find($id);
        if (!$dd) {
            throw $this->createNotFoundException('Destination not found');
        }
        $user= $dd->getIdUtilisateur();
        $date = $dd->getDateDemande();
$date_string = date_format($date, 'Y-m-d');
$pathqr = $this->getParameter('kernel.project_dir').'/public/front/images';

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data( "Nom et prénom : " . $user->getNom() ." " . $user->getPrenom() . "\n Date de demande: ".  $date_string ."\n Type de produit demandé: ". $dd->getTypeProduitDemande() . " \n Remarques: " . $dd->getRemarques() . "\n état :" .$dd->getEtat()  )
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->logoPath($pathqr."/handinyLogo.png")
            ->labelText("")
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->logoResizeToWidth('100')
            ->logoResizeToHeight('100')
            ->build();
        
        $namePng = uniqid('', true) . '.png';
        $result->saveToFile($this->getParameter('qr_directory').$namePng);
        
        $response = new Response();
        $response->headers->set('Content-Type', 'image/png');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$namePng.'"');
        $response->setContent (file_get_contents($this->getParameter('qr_directory').$namePng));
        return $response;
    }
    
    #[Route('/demandedon/add/{id}', name: 'app_add_demandedon')]
    public function adddemandedon(Request $request, ManagerRegistry $doctrine,UserRepository $repo,$id,DonRepository $repdon): Response
    {
        $demandedon = new DemandeDon();
        $form = $this->createForm(DemandeDonType::class, $demandedon);
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
                    $etat="en cours";
                    $demandedon->setEtat("en cours");
                    $demandedon->setEtat($etat);
                    $user = $this->getUser();
                    $demandedon->setIdUtilisateur($user);
                    $demandedon->setJustificatifHandicap($newFilename);
                    $demandedon->setDateDemande(date_create());
                    $demandedon->setIdDon($repdon->find($id));
                    $demandedon->setTypeProduitDemande($repdon->find($id)->getType()); 
                    $em->persist($demandedon);
                    $em->flush();
                    $this->addFlash('success', 'le demandedon a bien ete ajouter ');
                  

 // Create the Transport
 $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
 ->setUsername('anis.trabelsi@esprit.tn')
 ->setPassword('zdoubida9');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

$user =$demandedon->getIdUtilisateur();
$nom=$user->getNom();
$prenom=$user->getPrenom();

$imagePath = $this->getParameter('images_directory') ."/". $demandedon->getJustificatifHandicap();

// Create a message
$message = (new Swift_Message('Demande de Don ajouté avec succès'))
->setFrom(['anis.trabelsi@esprit.tn' => 'Handiny'])
->setTo([$user->getEmail()])
->setBody("
 <html>
     <head>
         <style>
             /* Your CSS code here */
             body {
                 font-family: Arial, sans-serif;
                 font-size: 14px;
                 color: #333;
                 background-color: #f2f2f2;
                 padding: 20px;
             }
             h1 {
                 font-size: 24px;
                 color: #007bff;
                 margin-top: 0;
             }
             p {
                 margin: 10px 0;
             }
             .success {
                 background-color: #d4edda;
                 border-color: #c3e6cb;
                 color: #155724;
                 padding: 15px;
                 border-radius: 5px;
             }
             .don-info {
                 background-color: #fff;
                 border: 1px solid #ddd;
                 padding: 10px;
                 border-radius: 5px;
             }
             .don-type {
                 font-size: 18px;
                 font-weight: bold;
             }
             .don-description {
                 font-style: italic;
             }
         </style>
     </head>
     <body>
         
         <div class='don-info'>
             <p class='don-type'>Monsieur,Madame ". $nom ." ".$prenom ."</p>
             <p class='success'>Votre demande de don est ajouté avec succès!</p>
             <p class='don-description'>Type de produit demandé: " . $demandedon->getTypeProduitDemande() . "</p>
             <p class='don-description'>Date de demande: " . $demandedon->getdateDemande()->format("Y-M-D")  . "</p>
             <p class='don-description'>Remarques: ".$demandedon->getRemarques()."</p>
         </div>
     </body>
 </html>",
 'text/html'
)
->attach(Swift_Attachment::fromPath($imagePath)->setFilename('image.jpg'));

;


 $mailer->send($message);






                    //return $this->redirectToRoute("app_list_demandedon");
                } else {
              
                    $demandedon->setDateDemande(new \DateTime('now'));
                    $user = $this->getUser();
                    $demandedon->setIdUtilisateur($user);
                    $demandedon->setEtat("en cours");
                    $demandedon->setIdDon($repdon->find($id));
                    $demandedon->setTypeProduitDemande($repdon->find($id)->getType() ); 
                    $em->persist($demandedon);
                    $em->flush();
                    $this->addFlash('success', 'la demande don a bien ete ajouter ');
                   
                    //return $this->redirectToRoute("app_list_demandedon");
                    // Create the Transport
 $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
 ->setUsername('anis.trabelsi@esprit.tn')
 ->setPassword('zdoubida9');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

$user =$demandedon->getIdUtilisateur();
$nom=$user->getNom();
$prenom=$user->getPrenom();


// Create a message
$message = (new Swift_Message('Demande de Don ajouté avec succès'))
->setFrom(['anis.trabelsi@esprit.tn' => 'Handiny'])
->setTo([$user->getEmail()])
->setBody("
 <html>
     <head>
         <style>
             /* Your CSS code here */
             body {
                 font-family: Arial, sans-serif;
                 font-size: 14px;
                 color: #333;
                 background-color: #f2f2f2;
                 padding: 20px;
             }
             h1 {
                 font-size: 24px;
                 color: #007bff;
                 margin-top: 0;
             }
             p {
                 margin: 10px 0;
             }
             .success {
                 background-color: #d4edda;
                 border-color: #c3e6cb;
                 color: #155724;
                 padding: 15px;
                 border-radius: 5px;
             }
             .don-info {
                 background-color: #fff;
                 border: 1px solid #ddd;
                 padding: 10px;
                 border-radius: 5px;
             }
             .don-type {
                 font-size: 18px;
                 font-weight: bold;
             }
             .don-description {
                 font-style: italic;
             }
         </style>
     </head>
     <body>
         
         <div class='don-info'>
             <p class='don-type'>Monsieur,Madame ". $nom ." ".$prenom ."</p>
             <p class='success'>Votre demande de don est ajouté avec succès!</p>
             <p class='don-description'>Type de produit demandé: " . $demandedon->getTypeProduitDemande() . "</p>
             <p class='don-description'>Date de demande: " . $demandedon->getdateDemande()->format("Y-M-D")  . "</p>
             <p class='don-description'>Remarques: ".$demandedon->getRemarques()."</p>
         </div>
     </body>
 </html>",
 'text/html'
)


;


 $mailer->send($message);

                }
            }
        
        return $this->renderForm('demande_don/add.html.twig', [
            'myForm' => $form
        ]);
    }





    #[Route('/demandedon/remove/{id}', name: 'app_remove_demandedon')]
    public function delete(DemandeDonRepository $repo, $id,FlashBagInterface $flashBag): Response
    {
        $cl = $repo->find($id);
        $repo->remove($cl, true);
        $flashBag->add('error', 'Votre demande de don a été supprimé.');
        return $this->redirectToRoute("app_list_demandedon");
    }



    #[Route('/demandedon/update/{id}', name: 'app_update_demandedon')]
    public function updatedon(Request $request, ManagerRegistry $doctrine,UserRepository $repo,DemandeDonRepository $repd,$id,DonRepository $repdon,FlashBagInterface $flashBag): Response
    {
        $id = $request->get('id');
        $repo = $doctrine->getRepository(DemandeDon::class);

        $demandedon = $repo->find($id);
        $form = $this->createForm(DemandeDonType::class, $demandedon);

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
                  // $demandedon->setIdUtilisateur($repo->find(7));
                   $demandedon->setJustificatifHandicap($newFilename);
                   $demandedon->setEtat("en cours");
                   $demandedon->setIdDon($repdon->find($repd->find($id)->getIdDon()));
                   $demandedon->setTypeProduitDemande($repd->find($id)->getTypeProduitDemande()); 
                
                
                   $em->persist($demandedon);
                   $em->flush();
                   $this->addFlash('message', 'le demandedon a bien ete modifiée ');
                   $flashBag->add('success', 'Votre demande de don a été modifiée.');
                   return $this->redirectToRoute("app_list_demandedon");
               } else {
                   $demandedon->setDateDemande(new \DateTime('now'));
                  // $demandedon->setIdUtilisateur($repo->find(7));
                   $demandedon->setEtat("en cours");
                   $demandedon->setIdDon($repdon->find($repd->find($id)->getIdDon()));
                   $em->persist($demandedon);
                   $em->flush();
                   $this->addFlash('message', 'la demandedon a bien ete ajouter ');
                   $flashBag->add('success', 'Votre demande de don a été modifiée.');
                   return $this->redirectToRoute("app_list_demandedon");
               }
           }
       
       return $this->renderForm('demande_don/modifier.html.twig', [
           'myForm' => $form
       ]);
    }







}
