<?php

namespace App\Controller;




use Attachment;
use Swift_Mailer;
use Swift_Message;
use App\Entity\Don;



use App\Form\DonType;





use Swift_Attachment;
use Swift_SmtpTransport;
use App\Form\RecherchedonType;
use Endroid\QrCode\Color\Color;
use App\Repository\DonRepository;
use App\Repository\UserRepository;
use Endroid\QrCode\Builder\Builder;
use Symfony\Component\Mime\Message;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Mailer\Mailer;
use Endroid\QrCode\Encoding\Encoding;
use Symfony\Component\Mime\RawMessage;
use Endroid\QrCode\Label\Margin\Margin;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\VarDumper\VarDumper;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Naming\PropertyNamer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Naming\SmartUniqueNamer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class DonController extends AbstractController
{
    #[Route('/don', name: 'app_don')]
    public function index(): Response
    {
        return $this->render('don/index.html.twig', [
            'controller_name' => 'DonController',
        ]);
    }
    #[Route('/Affichagedons', name: 'app_dons_index')]
    public function Affichage_dons(Request $request,DonRepository $rep,ManagerRegistry $doctrine,PaginatorInterface $paginator): Response
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
        
        return $this->render('don/list.html.twig', [
            'dons' => $paginatedDons
        ]);
    }


    #[Route('/don/list', name: 'app_list_don', methods: ['GET', 'POST'])]
    public function getAll2(Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator,DonRepository $repo,UserRepository $repu): Response
    {
        $em = $doctrine->getManager();
        // Récupérer toutes les types distinctes
        $types = $em->getRepository(Don::class)->findDistinctTypes();

        // Stocker les types dans un tableau
        $typesDisponibles = [];
        foreach ($types as $type) {
            $typesDisponibles[] = $type['type'];
        }

        // Récupérer les valeurs de filtre de date
        $minDate = $request->query->get('startDate');
        $maxDate = $request->query->get('endDate');
        //var_dump( $minDate);
        // Récupérer la valeur de tri sélectionnée
        $selectedField = $request->query->get('selectedField');

        // Récupérer les types sélectionnées
        
        $selectedtypes = $request->query->get('types');

     
$selectedButtonValue = $request->get('selectedButtonValue');

$searchValue = $request->query->get('search');

/*if ($selectedButtonValue == "1")
{ 
    $donnees = $repo->findPossesed($repu->find(7));
  
}*/
if ($searchValue )
{
    $donnees = $repo->searchdons($searchValue);

}

else if ($selectedButtonValue == "np")
{ $user = $this->getUser();
    $donnees = $repo->findNotPossesed($user);
   
}
       else if (!empty($selectedtypes)) {
            
            $donnees = $repo->findBytypes($selectedtypes);
            
        
        } 
        // Si des valeurs de filtre de prix sont spécifiées, récupérer les dons correspondants
      else if ($minDate && $maxDate) {
            $donnees = $em->getRepository(Don::class)->findByDateRange($minDate, $maxDate);
        } else {
            // Sinon, afficher toutes les dons
            $donnees = $em->getRepository(Don::class)->findAll();
        }


if ($selectedField) {
    // Si un champ est sélectionné, trier les dons en utilisant la méthode correspondante de votre repository
    switch ($selectedField) {
        case 'type':
            $donnees = $em->getRepository(Don::class)->TriPar_type();
            break;
        case 'datecroissant':
            $donnees = $em->getRepository(Don::class)->TriPar_date_de_creation_c();
            break;
            case 'datedecroissant':
                $donnees = $em->getRepository(Don::class)->TriPar_date_de_creation_d();
                break;
        default:
            $donnees = null;
    }
}
            // Paginer les dons triées ou filtrées
            $dons = $paginator->paginate($donnees, $request->query->getInt('page', 1), 8);
            $paginationData = $dons->getPaginationData();
            $pagesInRange = $paginationData['pagesInRange'];
            $lastPageNumber = $dons->getPageCount();

            // Vérifier si la requête est une requête AJAX
            if ($request->isXmlHttpRequest()) {
                // Si oui, retourner la vue des dons triées ou filtrées uniquement
                return $this->render('don/dons.html.twig', [
                    'dons' => $dons,
                    'pagesInRange' => $pagesInRange,
                    'lastPageNumber' => $lastPageNumber,
                    'types' => $typesDisponibles
                ]);
            } else {
                // Sinon, retourner la vue principale
                return $this->render('don/list2.html.twig', [
                    'dons' => $dons,
                    'pagesInRange' => $pagesInRange,
                    'lastPageNumber' => $lastPageNumber,
                    'types' => $typesDisponibles
                ]);
            }
        }
    

       
     


    #[Route('/mesdon', name: 'app_list_mesdon')]
    public function getmesDons(DonRepository $repo, PaginatorInterface $paginator, Request $request, Request $request2, UserRepository $repu): Response
    {  $user = $this->getUser();
        $dons = $repo->findByidUtilisateur($user);
        $paginatedDons = $paginator->paginate(
            $dons,
            $request->query->getInt('page', 1), // numéro de la page à afficher
            6// nombre d'éléments par page
        );


        $searchForm = $this->createForm(RecherchedonType::class);
        $searchForm->handleRequest($request2);
        if ($searchForm->isSubmitted()) {
            $type = $searchForm['type']->getData();

            if ($type == "Tous") {
                $dons = $repo->findAll();
                $paginatedDons = $paginator->paginate(
                    $dons,
                    $request->query->getInt('page', 1), // numéro de la page à afficher
                    6 // nombre d'éléments par page
                );
            } else {
                $resultOfSearch = $repo->findBytype($type);

                $paginatedDons = $paginator->paginate(
                    $resultOfSearch,
                    $request->query->getInt('page', 1), // numéro de la page à afficher
                    6 // nombre d'éléments par page
                );
            }
        }

        return $this->render(
            'don/mesDons.html.twig',
            array('dons' => $paginatedDons, "searchform" => $searchForm->createView())
        );
    }




    #[Route('/don/{id}', name: 'app_don_detail')]
    public function detaill(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $don = $em->getRepository(Don::class)->find($id);

        return $this->render('don/AffichageDon.html.twig', [
            'don' => $don,
        ]);
    }


    #[Route('/add/don', name: 'app_add_don', methods: ['GET', 'POST'])]
    public function adddon(Request $request, ManagerRegistry $doctrine, UserRepository $repo,Swift_Mailer $mailer): Response
    {
    $don = new Don();
    $form = $this->createForm(DonType::class, $don);
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
        $user = $this->getUser();
        $don->setIdUtilisateur($user);
        $don->setImageDon($newFilename);
        $don->setDateAjout(date_create());

        $em->persist($don);
        $em->flush();
        $this->addFlash('success', 'Opération effectuée avec succès !');

        $path = $this->getParameter('qr_directory');

   
        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
        ->setUsername('anis.trabelsi@esprit.tn')
        ->setPassword('zdoubida9');

    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);
    $user =$don->getIdUtilisateur();
$nom=$user->getNom();
$prenom=$user->getPrenom();

$imagePath = $this->getParameter('images_directory') ."/". $don->getImageDon();

    // Create a message
    $message = (new Swift_Message('Don ajouté avec succès'))
    ->setFrom(['anis.trabelsi@esprit.tn' => 'Handiny'])
    ->setTo(['anis.trabelsi@esprit.tn'])
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
                    <p class='success'>Votre don est ajouté avec succès!</p>
                    <p class='don-description'>Type: " . $don->getType() . "</p>
                    <p class='don-description'>Date: " . $don->getDateAjout()->format("y-m-d")  . "</p>
                    <p class='don-description'>Description: ".$don->getDescription()."</p>
                </div>
            </body>
        </html>",
        'text/html'
    )
    ->attach(Swift_Attachment::fromPath($imagePath)->setFilename('image.jpg'));

    ;


        $mailer->send($message);

    //  return $this->redirectToRoute("app_list_mesdon");
    } else {
        $user = $this->getUser();
        $don->setDateAjout(new \DateTime('now'));
        $don->setIdUtilisateur($user);
        $em->persist($don);
        $em->flush();
        $this->addFlash('success', 'Opération effectuée avec succès !');


          
        // Create the Transport
        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
        ->setUsername('anis.trabelsi@esprit.tn')
        ->setPassword('zdoubida9');

    // Create the Mailer using your created Transport
    $mailer = new Swift_Mailer($transport);
    
    $user =$don->getIdUtilisateur();
$nom=$user->getNom();
$prenom=$user->getPrenom();



    // Create a message
    $message = (new Swift_Message('Don ajouté avec succès'))
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
                    <p class='success'>Votre don est ajouté avec succès!</p>
                    <p class='don-description'>Type: " . $don->getType() . "</p>
                    <p class='don-description'>Date: " . $don->getDateAjout()->format("Y-M-D") . "</p>
                    <p class='don-description'>Description: ".$don->getDescription()."</p>
                </div>
            </body>
        </html>",
        'text/html'
    )

    ;


        $mailer->send($message);
    }
}
        return $this->renderForm('don/add.html.twig', [
            'myForm' => $form
        ]);
    
}



    #[Route('/don/remove/{id}', name: 'app_remove_don')]
    public function delete(DonRepository $repo, $id,FlashBagInterface $flashBag): Response
    {   
        $cl = $repo->find($id);
        $repo->remove($cl, true);
        $flashBag->add('error', 'Votre don a été supprimé.');


        return $this->redirectToRoute("app_list_mesdon");
    }



    #[Route('/don/update/{id}', name: 'app_update_don')]
    public function updatedon(Request $request, ManagerRegistry $doctrine, UserRepository $repo,FlashBagInterface $flashBag): Response
    {
        $id = $request->get('id');
        $repo = $doctrine->getRepository(Don::class);

        $don = $repo->find($id);
        $form = $this->createForm(DonType::class, $don);



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
                $flashBag->add('success', 'Votre don a été modifié.');
                return $this->redirectToRoute("app_list_mesdon");
            } else {

                // $don->setDateAjout(new \DateTime('now'));

                $em->persist($don);
                $em->flush();
                $this->addFlash('message', 'le don a bien ete ajouter ');
                $flashBag->add('success', 'Votre don a été modifié.');
                return $this->redirectToRoute("app_list_mesdon");
            }
        }

        return $this->renderForm('don/modifier.html.twig', [
            'myForm' => $form
        ]);
    }

///////////////////////////////////////////////////

#[Route("/AlldonJSON", name: "list")]
//* Dans cette fonction, nous utilisons les services NormlizeInterface et DonRepository, 
//* avec la méthode d'injection de dépendances.
public function getDons(DonRepository $repo, SerializerInterface $serializer)
{
    $dons = $repo->findAll();
    //* Nous utilisons la fonction normalize qui transforme le tableau d'objets 
    //* dons en  tableau associatif simple.
    // $donsNormalises = $normalizer->normalize($dons, 'json', ['groups' => "dons"]);

    // //* Nous utilisons la fonction json_encode pour transformer un tableau associatif en format JSON
    // $json = json_encode($donsNormalises);

    $json = $serializer->serialize($dons, 'json', ['groups' => "dons"]);

    //* Nous renvoyons une réponse Http qui prend en paramètre un tableau en format JSON
    return new Response($json);
}

#[Route("s{id}", name: "don")]
public function DonId($id, NormalizerInterface $normalizer, DonRepository $repo)
{
    $don = $repo->find($id);
    $donNormalises = $normalizer->normalize($don, 'json', ['groups' => "dons"]);
    return new Response(json_encode($donNormalises));
}


#[Route("/AdddonJSON", name: "addDonjson")]
<<<<<<< Updated upstream
public function AdddonJSON(Request $req,   NormalizerInterface $Normalizer,ManagerRegistry $doctrine,UserRepository $repo)
=======
public function AdddonJSON(Request $req,   NormalizerInterface $Normalizer,ManagerRegistry $doctrine,UserRepository $repo,UserRepository $repou)
>>>>>>> Stashed changes
{

    $em =$doctrine->getManager();
    $don = new Don();
    $don->setType($req->get('type'));
    $don->setImageDon($req->get('imageDon'));
    $don->setDescription($req->get('description'));
    $don->setDateAjout(new \DateTime('now'));
<<<<<<< Updated upstream
    $don->setIdUtilisateur($req->get('idUtilisateur'));
=======
    $don->setIdUtilisateur($repou->find($req->get('idUtilisateur')));
>>>>>>> Stashed changes

    $em->persist($don);
    $em->flush();

    $jsonContent = $Normalizer->normalize($don, 'json', ['groups' => 'dons']);
    return new Response(json_encode($jsonContent));
}

#[Route("/UpdatedonJSON/{id}", name: "updateDonJSON")]
<<<<<<< Updated upstream
public function UpdatedonJSON(Request $req, $id, NormalizerInterface $Normalizer,ManagerRegistry $doctrine)
=======
public function UpdatedonJSON(Request $req, $id, NormalizerInterface $Normalizer,ManagerRegistry $doctrine,UserRepository $repou)
>>>>>>> Stashed changes
{

    $em =  $doctrine->getManager();
    $don = $em->getRepository(Don::class)->find($id);
    $don->setType($req->get('type'));
    $don->setImageDon($req->get('imageDon'));
    $don->setDescription($req->get('description'));
    $don->setDateAjout($req->get('dateAjout'));
<<<<<<< Updated upstream
    $don->setIdUtilisateur($req->get('idUtilisateur'));
=======
    $don->setIdUtilisateur($repou->find($req->get('idUtilisateur')));
>>>>>>> Stashed changes

    $em->flush();

    $jsonContent = $Normalizer->normalize($don, 'json', ['groups' => 'dons']);
    return new Response("Don updated successfully " . json_encode($jsonContent));
}

#[Route("/DeletedonJSON/{id}", name: "deleteDonJSON")]
public function DeletedonJSON(Request $req, $id, NormalizerInterface $Normalizer,ManagerRegistry $doctrine)
{

    $em = $doctrine->getManager();
    $don = $em->getRepository(Don::class)->find($id);
    $em->remove($don);
    $em->flush();
    $jsonContent = $Normalizer->normalize($don, 'json', ['groups' => 'dons']);
    return new Response("Don deleted successfully " . json_encode($jsonContent));
}











}
