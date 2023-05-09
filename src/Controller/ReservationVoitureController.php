<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Component\Mime\Email;
use App\Entity\ReservationVoiture;
use App\Repository\UserRepository;
use Endroid\QrCode\Builder\Builder;
use App\Form\ReservationVoitureType;
use Endroid\QrCode\Writer\PngWriter;
use App\Repository\VoitureRepository;
use Endroid\QrCode\Encoding\Encoding;
use App\Event\ReservationAccepteeEvent;
use Endroid\QrCode\Label\Margin\Margin;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationVoitureRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;



class ReservationVoitureController extends AbstractController
{
    #[Route('/reservationsVoitures_Front_loc', name: 'app_Affichage_Reservations_Voitures_Locataire', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine,ReservationVoitureRepository $reservationVoitureRepository,Request $request, PaginatorInterface $paginator,UserRepository $userRepository): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
      if ($this->isGranted('ROLE_ADMIN')) {
                // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
                return $this->redirectToRoute('app_homback');
            }
        //Le user connecté : 
        $user = $this->getUser();
        //var_dump($user);
       
       // $user = $userRepository->find(14);
    
    // $voitures = $em->getRepository(Voiture::class)->findAll();
    //$donnees = $reservationVoitureRepository->findByUserId($user);
    //$donnees = $doctrine->getManager()->getRepository(ReservationVoiture::class)->Rechercher($user);
    //$user = $this->getUser();
    $donnees = $reservationVoitureRepository->findBy(['id_user' => $user]);
       

      

        
        //$donnees=$reservationVoitureRepository->findAll();
        $reservation_voitures = $paginator->paginate($donnees, $request->query->getInt('page', 1), 5);
        $paginationData = $reservation_voitures->getPaginationData();
        $pagesInRange = $paginationData['pagesInRange'];
        $lastPageNumber = $reservation_voitures->getPageCount();
        return $this->render('reservation_voiture/Affichage_Reservations.html.twig', [
            'reservation_voitures' => $reservation_voitures,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
        ]);
    }
    #[Route('/calendrier', name: 'app_Acalendrier', methods: ['GET','POST'])]
    public function calendrier(VoitureRepository $voitureRepository,UserRepository $userRepository,ReservationVoitureRepository $reservationVoitureRepository,Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
      if ($this->isGranted('ROLE_ADMIN')) {
                // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
                return $this->redirectToRoute('app_homback');
            }
        
        //Le user connecté : 
        $user = $this->getUser();
     
    
// Récupérer l'utilisateur connecté
//$user = $userRepository->find(14);

// Récupérer toutes les voitures associées à cet utilisateur
$voitures = $voitureRepository->findBy(['id_user' => $user]);

// Récupérer toutes les réservations de voitures où l'ID de la voiture appartient à l'utilisateur connecté
$reservations= $reservationVoitureRepository->findBy(['id_voiture' => $voitures]);
        return $this->render('reservation_voiture/calendrier_prop.html.twig', ['reservations' => $reservations,
    ]);
    }
    #[Route('/reservationsVoitures_Front_prop', name: 'app_Affichage_Reservations_Voitures_prop', methods: ['GET'])]
    public function AffichageReservations_prop(VoitureRepository $voitureRepository,UserRepository $userRepository,ReservationVoitureRepository $reservationVoitureRepository,Request $request, PaginatorInterface $paginator): Response
    {
         //Le user connecté : 
        $user = $this->getUser();
     
    
// Récupérer l'utilisateur connecté
//$user = $userRepository->find(14);

// Récupérer toutes les voitures associées à cet utilisateur
$voitures = $voitureRepository->findBy(['id_user' => $user]);

// Récupérer toutes les réservations de voitures où l'ID de la voiture appartient à l'utilisateur connecté
$donnees = $reservationVoitureRepository->findBy(['id_voiture' => $voitures]);

// $reservations contient maintenant toutes les réservations de voiture liées aux voitures de l'utilisateur connecté

        
        
        
        $reservation_voitures = $paginator->paginate($donnees, $request->query->getInt('page', 1), 5);
        $paginationData = $reservation_voitures->getPaginationData();
        $pagesInRange = $paginationData['pagesInRange'];
        $lastPageNumber = $reservation_voitures->getPageCount();
        return $this->render('reservation_voiture/Affichage_Reservations_Proprietaire.html.twig', [
            'reservation_voitures' => $reservation_voitures,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
        ]);
    }
    #[Route("/reservation/reponse/{id}", name:"reservation_reponse", methods:["GET","POST"])]
public function repondreReservation(VoitureRepository $voitureRepository,MailerInterface $mailer,UserRepository $userRepository,Request $request, ReservationVoiture $reservation, ManagerRegistry $doctrine,ReservationVoitureRepository $reservationVoitureRepository, PaginatorInterface $paginator)
{
    $reponse = $request->request->get('reponse');
    $Id_user_locataire = $reservation->getIdUser();
    $user = $userRepository->find($Id_user_locataire); //Amal Ben Salem
    // Générer le QR code
  
    /*Informations du locataire de voiture */
    $nomLocataire = $user->getNom(); 
    $prenomLocataire = $user->getPrenom();
    $email_locataire = $user->getEmail();
    /*Informations du prop de voiture*/
    $immatriculationVoiture=$reservation->getIdVoiture()->getImmatriculation();
    $proprietaireVoiturenom = $reservation->getIdVoiture()->getIdUser()->getNom();
    $proprietaireVoiturePrenom = $reservation->getIdVoiture()->getIdUser()->getPrenom();
    /*Informations du réservation */
    $idReservationVoiture = $reservation->getId();
    $dateDebutReservation = $reservation->getDateDebutReservation();
    $dateFinReservation = $reservation->getDateFinReservation();
    $etatDemandeReservation = $reservation->getEtatDemandeReservation();
    $descriptionReservation = $reservation->getDescriptionReservation();
    $dateDemandeReservation = $reservation->getDateDemandeReservation();
  

    if ($reponse === 'accepter') {
        $reservation->setEtatDemandeReservation('acceptée');
       
      
      $message = "Nom et prenom du locataire: " . $nomLocataire . " " . $prenomLocataire . "\n" .
      "Nom et prenom du proprietaire: " . $proprietaireVoiturenom . " " . $proprietaireVoiturePrenom . "\n" .
      "Immatriculation de la voiture: " . $immatriculationVoiture . "\n" .
      "Etat de demande: " . "Acceptee" . "\n" .
      "Date de debut demande: " . $dateDebutReservation->format('Y-m-d') . "\n" .
      "Date de fin demande: " . $dateFinReservation->format('Y-m-d') . "\n" .
      "Date de demande de reservation: " . $dateDemandeReservation->format('Y-m-d');

$result = Builder::create()
->writer(new PngWriter())
->data($message) // Utilisation du message personnalisé avec les informations de la demande de réservation
->encoding(new Encoding('UTF-8'))
->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
->size(600)
->margin(10)
->labelText("")
->labelAlignment(new LabelAlignmentCenter())
->labelMargin(new Margin(15, 5, 5, 5))
->build();
  
          $namePng = uniqid('', true) . '.png';
          $imagesDirectory = $this->getParameter('kernel.project_dir') . '/public/front/images/voitures/';
          $filesystem = new Filesystem();
          $filesystem->mkdir($imagesDirectory); // Créer le répertoire s'il n'existe pas
          $result->saveToFile($imagesDirectory . $namePng);

$nameImagePng=$reservation->getIdVoiture()->getImageVoiture();
// Chemin complet de l'image à envoyer en pièce jointe
$imagePath = $this->getParameter('kernel.project_dir') . '/public/front/images/voitures/' . $nameImagePng;
      // Envoyer l'e-mail avec le QR code en pièce jointe
      $email = (new Email())
          ->from('chaima.lotfi@esen.tn')
          //->to($reservation->getIdUser()->getEmail()) // Remplacez par l'adresse e-mail du locataire de la voiture
          ->to($email_locataire)
          ->subject('Confirmation de réservation')
         
          ->html("
          <p style='font-size: 18px;'>Bonjour <span style='font-weight: bold; color:green;'>$nomLocataire $prenomLocataire</span>,</p>

          <p style='font-size: 16px; font-weight: bold;'>Votre demande de voiture ayant Immatriculation num : $immatriculationVoiture a été acceptée.</p>
  
          <p style='font-size: 14px;'>Veuillez trouver ci-joint le code QR contenant les informations de votre réservation :</p>
  
          
  
          <p style='font-size: 14px;'>Cordialement,</p>
          <p style='font-size: 14px;'>Equipe de Handiny</p>
      ")
      ->attachFromPath($this->getParameter('kernel.project_dir') . '/public/front/images/voitures/' . $namePng, 'qrcode1.png') // Premier fichier
      ->attachFromPath($imagePath, 'ImageVoiture.png', 'image/png'); // Deuxième fichier
  
          
      $mailer->send($email);
  




    } elseif ($reponse === 'refuser') {
        $reservation->setEtatDemandeReservation('refusée');

         // Envoyer l'e-mail avec le QR code en pièce jointe
      $email = (new Email())
      ->from('chaima.lotfi@esen.tn')
      //->to($reservation->getIdUser()->getEmail()) // Remplacez par l'adresse e-mail du locataire de la voiture
      ->to($email_locataire)
      ->subject('Réponse de votre réservation de voiture')
     
      ->html("
      <p style='font-size: 18px;'>Bonjour <span style='font-weight: bold; color:green;'>$nomLocataire $prenomLocataire</span>,</p>

      <p style='font-size: 16px; font-weight: bold;'>Votre demande de voiture ayant Immatriculation num : $immatriculationVoiture a été refusée.</p>

      <p style='font-size: 14px;'>Merci de votre compréhension. :</p>

      

      <p style='font-size: 14px;'>Cordialement,</p>
      <p style='font-size: 14px;'>Equipe de Handiny</p>
  ");
  

      
  $mailer->send($email);

    } 

    $entityManager = $doctrine->getManager();
    $entityManager->flush();

        $donnees=$reservationVoitureRepository->findAll();
        $reservation_voitures = $paginator->paginate($donnees, $request->query->getInt('page', 1), 5);
        $paginationData = $reservation_voitures->getPaginationData();
        $pagesInRange = $paginationData['pagesInRange'];
        $lastPageNumber = $reservation_voitures->getPageCount();

        return $this->render('reservation_voiture/Affichage_Reservations_Proprietaire.html.twig', 
    ['reservation_voitures' => $reservation_voitures,
    'pagesInRange' => $pagesInRange,
    'lastPageNumber'=>$lastPageNumber,
]);

   
}

      
    #[Route('/reservationsVoitures_Back', name: 'app_reservation_voiture_index')]
public function AffichageReservationsVoitures_Back(Request $request, ReservationVoitureRepository $reservationVoitureRepository): Response
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }
    
    $query = $request->query->get('search');

    if ($query) {
        $reservations = $reservationVoitureRepository->searchReservations($query);
    } else {
        $reservations = $reservationVoitureRepository->findAll();
    }

    return $this->render('AffichageReservationsVoitures_back.html.twig', [
        'reservation_voitures' => $reservations,
    ]);
}
#[Route('/Reservation_Voiture_Back_Detail/{id}', name: 'app_reservation_back_detail')]
public function Affichage_Reservation_Voiture_Back_Detail(int $id, ReservationVoitureRepository $reservationVoitureRepository): Response
{
    
    
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }    
    
    $reservation_voiture = $reservationVoitureRepository->find($id);
    

    return $this->render('DetailReservationVoiture_back.html.twig', [
        'reservation_voiture' => $reservation_voiture
    ]);
}


    

    #[Route("/reservationVoiture_add/{id}", name:"app_reservation_voiture_new", methods: ['GET','POST'])]
    public function new(MailerInterface $mailer,ManagerRegistry $doctrine, Request $request, VoitureRepository $voitureRepository, UserRepository $userRepository, ReservationVoitureRepository $reservationVoitureRepository, int $id): Response
{
    
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
  if ($this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homback');
        }

    $reservationVoiture = new ReservationVoiture();

    // Récupérer la voiture correspondante à l'i
    $user=$this->getUser();
    $voiture = $voitureRepository->find($id);
    $reservationVoiture->setIdVoiture($voiture);

    // Définir l'utilisateur par défaut avec l'id 12
   // $user = $userRepository->find(12);
    $reservationVoiture->setIdUser($user);

    // Définir la date de demande de réservation sur la date système
    $reservationVoiture->setDateDemandeReservation(new \DateTime());

    // Définir l'état de la réservation sur "en cours"
    $reservationVoiture->setEtatDemandeReservation('en cours');

    $form = $this->createForm(ReservationVoitureType::class, $reservationVoiture);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reservationVoitureRepository->save($reservationVoiture, true);
        $this->addFlash('success', 'Votre réservation a été envoyée avec succès ! ');

         // Récupérer les informations nécessaires pour le message
         $proprietaireVoiture = $voiture->getIdUser();
         $email_prop = $proprietaireVoiture->getEmail();
         $immatriculationVoiture = $voiture->getImmatriculation();
         $nomLocataire = $reservationVoiture->getIdUser()->getNom();
         $nameImagePng=$voiture->getImageVoiture();
         // Chemin complet de l'image à envoyer en pièce jointe
$imagePath = $this->getParameter('kernel.project_dir') . '/public/front/images/voitures/' . $nameImagePng;
         
 
     // Générer le contenu HTML du message à partir d'un template Twig
$message = $this->renderView('reservation_voiture/email.html.twig', [
    'proprietaireVoiture' => $proprietaireVoiture,
    'immatriculationVoiture' => $immatriculationVoiture,
    'nomLocataire' => $nomLocataire,
]);

// Envoyer l'e-mail avec le contenu HTML et les pièces jointes
$email = (new Email())
    ->from('chaima.lotfi@esen.tn') // Adresse e-mail de l'expéditeur
    ->to($email_prop) // Adresse e-mail du destinataire
    ->subject('Demande de Réservation de voiture') // Objet de l'e-mail
    ->html($message) // Contenu HTML de l'e-mail
    ->attachFromPath($imagePath, 'ImageVoiture.png', 'image/png'); // Première pièce jointe


$mailer->send($email); // Envoi de l'e-mail
        
        return $this->renderForm('reservation_voiture/Reservation_Succes.html.twig', [
            'reservation_voiture' => $reservationVoiture,
            'form' => $form,
            'voiture'=>$voiture
        ]);
        
    }

    return $this->renderForm('reservation_voiture/new.html.twig', [
        'reservation_voiture' => $reservationVoiture,
        'form' => $form,
    ]);
}

    #[Route('/{id_reservation_voiture}/edit', name: 'app_reservation_voiture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationVoiture $reservationVoiture, ReservationVoitureRepository $reservationVoitureRepository): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
      if ($this->isGranted('ROLE_ADMIN')) {
                // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
                return $this->redirectToRoute('app_homback');
            }

        $form = $this->createForm(ReservationVoitureType::class, $reservationVoiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationVoitureRepository->save($reservationVoiture, true);
            $this->addFlash('success', 'Votre réservation a été modifiée avec succès ! ');

            return $this->redirectToRoute('app_Affichage_Reservations_Voitures_Locataire', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_voiture/ModifierReservation_Voiture.html.twig', [
            'reservation_voiture' => $reservationVoiture,
            'form' => $form,
        ]);
    }

    
    #[Route('/reservation_voiture/remove/{id}', name: 'remove_reservation_voiture')]
public function remove(ManagerRegistry $doctrine, $id, FlashBagInterface $flashBag): Response
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
  if ($this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homback');
        }
    $em = $doctrine->getManager();
    $reservation_voiture = $doctrine->getRepository(ReservationVoiture::class)->find($id);
    
    if ($reservation_voiture) {
        $em->remove($reservation_voiture);
        $em->flush();
        $this->addFlash('success', 'Votre réservation a été annulée avec succès ! ');
        
       
    }
    
    return $this->redirectToRoute('app_Affichage_Reservations_Voitures_Locataire');
}

    
    
    
    
    #[Route('/update/{id}', name: 'edit_reservation_voiture')]
    public function update(ManagerRegistry $doctrine, Request $request, ReservationVoiture $voiture): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
      if ($this->isGranted('ROLE_ADMIN')) {
                // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
                return $this->redirectToRoute('app_homback');
            }
            
        $em = $doctrine->getManager();
    
        $voitures = $em->getRepository(ReservationVoiture::class)->findAll();
    
        $form = $this->createForm(ReservationVoitureType::class, $voiture);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Voiture modifiée avec succès.');
            return $this->redirectToRoute('voitures_prop');
        }
        $errors = $form->getErrors(true, false);
        return $this->render('voiture/garage.html.twig', [
            'form' => $form->createView(),
            'voitures' => $voitures,
             'errors' => $errors
        ]);
    }
}
