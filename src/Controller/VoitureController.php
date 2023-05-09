<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use DateTimeInterface;
use League\Csv\Writer;
use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\NoteVoitures;
use App\Entity\FavorisVoitures;
use App\Entity\ReservationVoiture;
use App\Repository\UserRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NoteVoituresRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Naming\PropertyNamer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationVoitureRepository;

use Vich\UploaderBundle\Naming\SmartUniqueNamer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;





class VoitureController extends AbstractController
{

   
  

#[Route('/AffichagevoituresFront', name: 'app_voiture', methods: ['GET','POST'])]
public function index(Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator): Response
{
    $em = $doctrine->getManager();
    $notes_moy = $em->getRepository(NoteVoitures::class)->getNoteMoyenneByVoiture();
  
    // Récupérer toutes les marques distinctes
    $marques = $em->getRepository(Voiture::class)->findDistinctMarques();
    
    // Stocker les marques dans un tableau
    $marquesDisponibles = [];
    foreach ($marques as $marque) {
        $marquesDisponibles[] = $marque['marque'];
    }

    // Récupérer les valeurs de filtre de prix
    $minPrice = $request->query->get('minVal');
    $maxPrice = $request->query->get('maxVal');
    
    // Récupérer la valeur de tri sélectionnée
    $selectedField = $request->query->get('selectedField');

   // Récupérer les marques sélectionnées
   $marques = $request->request->get('marques');
    
   // Récupérer toutes les voitures de la base de données
   $voi = $em->getRepository(Voiture::class)->findAll();
   
   // Si des marques ont été sélectionnées, filtrer les voitures
   
   

    
    // Récupérer les marques sélectionnées
    //$selectedMarques = $request->query->get('marques');
    //var_dump($selectedMarques);
    // Récupérer les voitures ayant les marques sélectionnées
    
    // Si aucune marque n'est sélectionnée, afficher toutes les voitures
    
    $searchValue = $request->query->get('search');
     // Récupérer les notes sélectionnées
     $notes = $request->query->get('note', []);

    

    if ($searchValue) {
        $donnees = $em->getRepository(Voiture::class)->searchVoitures($searchValue);
    }
    else  if (!empty($notes)) {
        $donnees = $em->getRepository(Voiture::class)->findByNotes($notes);
    }
     else{
    // Si des valeurs de filtre de prix sont spécifiées, récupérer les voitures correspondantes
    if ($minPrice && $maxPrice) {
        $donnees = $em->getRepository(Voiture::class)->findByPrixRange($minPrice, $maxPrice);
    } else {
        // Sinon, afficher toutes les voitures
        $donnees = $em->getRepository(Voiture::class)->findAll();
    }

    if ($selectedField) {
        // Si un champ est sélectionné, trier les voitures en utilisant la méthode correspondante de votre repository
        switch($selectedField) {
            case 'prix':
                $donnees = $em->getRepository(Voiture::class)->TriParPrix_Location();
                break;
            case 'marque':
                $donnees = $em->getRepository(Voiture::class)->TriParMarque();
                break;
            case 'modele':
                $donnees = $em->getRepository(Voiture::class)->TriParModele();
                break;
            default:
                $donnees = null;
        }
    }
    if ($marques) {
        $donnees = $em->getRepository(Voiture::class)->findByMarques($marques);
        
           
       }
    }

      
   

     // Récupérer les notes sélectionnées
    


    // Paginer les voitures triées ou filtrées
    $voitures = $paginator->paginate($donnees, $request->query->getInt('page', 1), 8);
    $paginationData = $voitures->getPaginationData();
    $pagesInRange = $paginationData['pagesInRange'];
    $lastPageNumber = $voitures->getPageCount();

    // Vérifier si la requête est une requête AJAX
    if ($request->isXmlHttpRequest()) {
        // Si oui, retourner la vue des voitures triées ou filtrées uniquement
        return $this->render('voiture/toutes_voitures.html.twig', [
            'voitures' => $voitures,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
            'marques'=>$marquesDisponibles,
            'notes_moy' => $notes_moy,
           
        ]);
    } else {
        // Sinon, retourner la vue principale
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
            'marques'=>$marquesDisponibles,
            'notes_moy' => $notes_moy,
           
        ]);
    }}

   

    
    
    


   
    
    #[Route('/AffichagevoituresFront2', name: 'app_voiture2', methods: ['GET','POST'])]
public function index22(Request $request, ManagerRegistry $doctrine, PaginatorInterface $paginator): Response
{
    $em = $doctrine->getManager();
    

     // Récupérer les notes sélectionnées
     $notes = $request->query->get('note', []);

     if (empty($notes)) {
        $donnees = $em->getRepository(Voiture::class)->findAll();
     } else {
        $donnees = $em->getRepository(Voiture::class)->findByNotes($notes);
     }

     $searchValue = $request->query->get('search');
     

     if ($searchValue) {
         $donnees = $em->getRepository(Voiture::class)->searchVoitures($searchValue);
     }


    // Paginer les voitures triées ou filtrées
    $voitures = $paginator->paginate($donnees, $request->query->getInt('page', 1), 8);
    $paginationData = $voitures->getPaginationData();
    $pagesInRange = $paginationData['pagesInRange'];
    $lastPageNumber = $voitures->getPageCount();

    // Vérifier si la requête est une requête AJAX
    if ($request->isXmlHttpRequest()) {
        // Si oui, retourner la vue des voitures triées ou filtrées uniquement
        return $this->render('voiture/toutes_voitures.html.twig', [
            'voitures' => $voitures,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
            
           
        ]);
    } else {
        // Sinon, retourner la vue principale
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
            'pagesInRange' => $pagesInRange,
            'lastPageNumber'=>$lastPageNumber,
           
           
        ]);
    }}



   
#[Route('/AffichageVoitures_back', name: 'app_voitures_index_back')]
public function AffichageVoitures_back(Request $request,VoitureRepository $voitureRepository,ManagerRegistry $doctrine): Response
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }
    $em = $doctrine->getManager();
    $query = $request->query->get('search');

    if ($query) {
        $voitures = $voitureRepository->searchVoitures($query);
    } else {
        $voitures = $voitureRepository->findAll();
    }

    
    
    return $this->render('AffichageVoitures_back.html.twig', [
        'voitures' => $voitures
    ]);
}



   



#[Route('/prop_voitures/voitures', name: 'voitures_prop')]
public function AfficherVoituresProp(ManagerRegistry $doctrine, Request $request,UserRepository $userRepository): Response
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

    //Le user connecté : 
    $user = $this->getUser();
    //var_dump($user);
    //$user = $userRepository->find(14);
    
   // $voitures = $em->getRepository(Voiture::class)->findAll();
    $voitures = $em->getRepository(Voiture::class)->findByUserId($user);
    $voiture = new Voiture();
   // $user = $userRepository->find(14);
    $voiture->setIdUser($user);
    $form = $this->createForm(VoitureType::class, $voiture);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        


        
        $em->persist($voiture);
        $em->flush();
        $this->addFlash('success', 'Votre voiture a été ajoutée avec succès !');
        $voitures = $em->getRepository(Voiture::class)->findByUserId($user);
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
    }
    $errors = $form->getErrors(true, false);
    return $this->render('voiture/garage.html.twig', [
        'controller_name' => 'VoitureController',
        'voitures' => $voitures,
        'formulaire_voiture' => $form->createView(),
        'errors' => $errors
    ]);
}


#[Route('/delete/{id}', name: 'remove_voiture')]
public function delete(Request $request, ManagerRegistry $doctrine, Voiture $voiture): Response
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

    try {
        // Supprimer la voiture
        $em->remove($voiture);
        $em->flush();
        $this->addFlash('success', 'Votre voiture a été retirée avec succès ! ');
    } catch (\Exception $e) {
        // Traiter l'exception
        if (strpos($e->getMessage(), 'Expected argument of type "string", "null" given at property path "image_voiture"') !== false) {
            // La valeur de "image_voiture" est nulle, ne rien faire
        } else {
            // Une autre exception s'est produite, afficher un message d'erreur
            echo 'Une erreur s\'est produite : ' . $e->getMessage();
        }
    }
    $this->addFlash('success', 'Votre voiture a été retirée avec succès ! ');

    return $this->redirectToRoute('voitures_prop');
}


 







#[Route('/updateVoiture/{id}', name: 'edit_voiture')]
public function update(UserRepository $userRepository,ManagerRegistry $doctrine, Request $request, Voiture $voiture, SmartUniqueNamer $namer): Response
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
      //Le user connecté : 
     $user = $this->getUser();
   // $user = $userRepository->find(14);
    
   // $voitures = $em->getRepository(Voiture::class)->findAll();
    $voitures = $em->getRepository(Voiture::class)->findByUserId($user);

    //$voitures = $em->getRepository(Voiture::class)->findAll();

    $form = $this->createForm(VoitureType::class, $voiture);
    $form->handleRequest($request);

    // Vérifier si le formulaire est soumis et valide
    if($form->isSubmitted() && $form->isValid()) {
        $voiture = $form->getData();
    
        // Vérifier si l'utilisateur a choisi un nouveau fichier image
        /*if($form->get('imageFile')->getData() !== null){
            // Si l'utilisateur a choisi un nouveau fichier image, supprimer l'ancien fichier
            
    
            // Enregistrer le nouveau fichier image avec un nom de fichier unique
            $voiture->setImageFile($form->get('imageFile')->getData());

            
            $em->flush();
            $this->addFlash('success', 'Voiture modifiée avec succès.');
            return $this->redirectToRoute('voitures_prop');
        } else {*/
            // Si l'utilisateur n'a pas choisi de nouveau fichier image, conserver l'ancien
           
            $em->flush();
            $this->addFlash('success', 'Voiture modifiée avec succès.');
            return $this->redirectToRoute('voitures_prop');
        }
    
    $errors = $form->getErrors(true, false);
    return $this->render('voiture/garage.html.twig', [
        'formulaire_voiture' => $form->createView(),
        'voitures' => $voitures,
        'errors' => $errors
    ]);
}







    

    #[Route('/Affichagevoiture/{id}', name: 'app_voiture_detail')]
    public function detail(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $voiture = $em->getRepository(Voiture::class)->find($id);
        $cars = array(
            array('name' => 'Car1', 'image' => 'car1.jpeg'),
            array('name' => 'Car2', 'image' => 'car2.jpeg'),
            array('name' => 'Car3', 'image' => 'car3.jpeg'),
            // Add more cars and images as needed
        );

        return $this->render('voiture/AffichageVoiture.html.twig', [
            'voiture' => $voiture,
            'cars' => $cars,
        ]);
    }
    #[Route('/voitures_back/{id}', name: 'app_voiture_back_detail')]
    public function detail_back(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $voiture = $em->getRepository(Voiture::class)->find($id);

        return $this->render('DetailVoiture_back.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    
    
    #[Route('/add', name: 'add_voiture')]
public function add2(ManagerRegistry $doctrine, Request $request): Response
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
    $voiture = new Voiture();
    $form = $this->createForm(VoitureType::class, $voiture);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        

        $boiteVitesse = $form->get('boite_vitesse')->getData();
        $voiture->setBoiteVitesse($boiteVitesse);

        $em->persist($voiture);
        $em->flush();

        return new Response('ok');
    }

    $errors = $form->getErrors(true, false);
    return $this->renderForm('voiture/AjouterVoiture.html.twig', [
        'formulaire_voiture' => $form,
        'errors' => $errors,
    ]);
}

    



    #[Route('/insertVoiture', name: 'app_insert_user')]
    public function Add(ManagerRegistry $doctrine):Response
    {
        //1- préparer l'objet à ajouter
        $voiture = new Voiture();
        $voiture->setImmatriculation('AB-123-CD');
        $voiture->setMarque('Renault');
        $voiture->setModele('Clio');
        $voiture->setBoiteVitesse('Manuelle');
        $voiture->setKilometrage('50000');
        $voiture->setCarburant('Essence');
        $voiture->setImageVoiture('chemin/vers/image.jpg');
        $voiture->setPrixLocation(50.0);
        $voiture->setDateValidationTechnique(new \DateTime());
        $voiture->setDescription('Voiture en très bon état.');
        $em=$doctrine->getManager();
        
        //utilisateur connecté
        $user = $this->getUser();
        //$user = $em->getRepository(User::class)->find(14);
        /* 
        Dans cet exemple, nous avons d'abord créé un objet User en récupérant l'objet correspondant à l'ID 1 à partir de la base de données
        en utilisant la méthode find du repository. Ensuite, nous avons créé un objet Voiture et utilisé les setters pour initialiser
         les valeurs, y compris l'ID étranger id_user en utilisant la méthode setIdUser. Enfin, nous avons persisté l'objet Voiture 
         en appelant la méthode persist de l'EntityManager suivi de la méthode flush.
        */ 
        $voiture->setIdUser($user);
        
      
       //2-ajouter mon objet
        //2.1- récupérer le gestionnairede l'entité
        //$em=$doctrine->getManager();
        $em->persist($voiture); 
        $em->flush();
        return new Response("ok");
    
    }
    
    #[Route('/admin/voitures_populaires', name: 'admin_voitures_populaire')]
    public function voituresPopulaires(NoteVoituresRepository  $NotesVoitureRepository,ReservationVoitureRepository $ReservatioVoitureRepository)
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homefront');
        }
        $totalProfit = $ReservatioVoitureRepository->getTotalProfitReservationsAcceptees();      
// Appeler la méthode dans le repository pour obtenir les résultats
$reservationsCountByDuration = $ReservatioVoitureRepository->reservationsCountByDuration();
        // Conversion du tableau associatif en JSON pour le passage à la vue
       
        // Appeler la méthode du repository pour récupérer les données des voitures populaires
        $voituresData = $ReservatioVoitureRepository->findVoituresPopulairesReservations();
        $voituresPlusNotes = $NotesVoitureRepository->getVoitureWithHighestAverageNote();
        
        // Tableaux pour stocker les marques, les modèles et le nombre de réservations
        $marques = [];
        $modeles = [];
        $reservations = [];
        
        // Parcourir les données des voitures populaires pour remplir les tableaux
        foreach ($voituresData as $voitureData) {
            $marques[] = $voitureData['marque'];
            $modeles[] = $voitureData['modele'];
            $reservations[] = $voitureData['reservations'];
        }


        $marques_notes = [];
        $modeles_notes = [];
        $notes_voit = [];
        
        // Parcourir les données des voitures populaires pour remplir les tableaux
        foreach ($voituresPlusNotes as $voiturePlusNotes) {
            $marques_notes[] = $voiturePlusNotes['marque'];
            $modeles_notes[] = $voiturePlusNotes['modele'];
            $notes_voit [] = $voiturePlusNotes['notes'];
        }
        // Passer les tableaux à la vue Twig pour afficher les graphiques
        return $this->render('dashboard.html.twig', [
            'marques' => json_encode($marques),
            'modeles' => json_encode($modeles),
            'reservations' => json_encode($reservations),
            'reservationsCountByDuration' => json_encode($reservationsCountByDuration),
            'totalProfit' => json_encode($totalProfit),
            'marques_notes' => json_encode($marques_notes),
            'modeles_notes' => json_encode($modeles_notes),
            'notes_voit' => json_encode($notes_voit)
        ]);
    }
    #[Route('/noter/{id}', name: 'noter_voiture')]
    public function noterVoiture(int $id,UserRepository $userRepository,ManagerRegistry $doctrine,Request $request,Voiture $voiture): Response
    {
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
      if ($this->isGranted('ROLE_ADMIN')) {
                // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
                return $this->redirectToRoute('app_homback');
            } 
        
        // Définir l'utilisateur par défaut avec l'id 7
         //L utilisateur connecté 
        $user = $this->getUser();
        //$user = $userRepository->find(14);

        // Récupérer les données envoyées depuis la vue
        $note = $request->request->get('note');
        $id = $request->request->get('id');
          // Vérifier si la voiture existe
          if (!$voiture) {
            // Si la voiture n'existe pas, afficher une erreur
            throw $this->createNotFoundException('La voiture n\'existe pas');
        }

         // Vérifier si l'utilisateur a déjà ajouté cette voiture à ses favoris
         $note_voit = $doctrine->getRepository(NoteVoitures::class)->findOneBy([
            'id_user' => $user,
            'id_voiture' => $voiture,
        ]);
        if (!$note_voit) { {
        // Créer une nouvelle entité NoteVoitures
        $noteVoiture = new NoteVoitures();
        
        $noteVoiture->setIdVoiture($voiture);
        $noteVoiture->setIdUser($user);
        $noteVoiture->setNote($note);

        // Enregistrer la note dans la base de données
        $entityManager = $doctrine->getManager();
        $entityManager->persist($noteVoiture);
        $entityManager->flush();}

          // Rediriger l'utilisateur vers la page de la voiture avec un message de confirmation
          $this->addFlash('success', 'Votre note a été attribuée avec succès ! ');
         // Supposons que $voiture est un objet de la classe Voiture avec un ID valide
return $this->redirectToRoute('app_voiture_detail', ['id' => $voiture->getId()]);

      } else {
          // Si l'utilisateur a déjà ajouté cette voiture à ses favoris, afficher un message d'erreur
          $this->addFlash('warning', 'Vous avez déjà noté cette voiture');
          // Supposons que $voiture est un objet de la classe Voiture avec un ID valide
return $this->redirectToRoute('app_voiture_detail', ['id' => $voiture->getId()]);

      }
    }



    #[Route('/imprimer_voitures', name: 'imprimer_voitures')]
public function imprimerVoituresAction(ManagerRegistry $doctrine)
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }
   
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }
    $em = $doctrine->getManager();
    // Récupérer les informations des voitures depuis la base de données
    $voitures = $em->getRepository(Voiture::class)->findAll();

    // Créer le fichier CSV
    $csv = Writer::createFromString('');
    $csv->setDelimiter("\t");  // Définir le point-virgule comme délimiteur
    $csv->setOutputBOM(Writer::BOM_UTF8); // Définir l'encodage UTF-8 avec BOM
   
    
    $csv->insertOne(['ID Voiture', 'Immatriculation', 'Marque', 'Modèle', 'Date de validation', 'Boîte de vitesse', 'Kilométrage', 'Carburant', 'Prix de location']);
    foreach ($voitures as $voiture) {
        $csv->insertOne([$voiture->getId(), $voiture->getImmatriculation(), $voiture->getMarque(), $voiture->getModele(), $voiture->getDateValidationTechnique()->format('Y-m-d'), $voiture->getBoiteVitesse(), $voiture->getKilometrage(), $voiture->getCarburant(), $voiture->getPrixLocation()]);
    }

    // Envoyer le fichier CSV en tant que réponse
    $response = new Response($csv->getContent());
    $response->headers->set('Content-Type', 'text/csv; charset=UTF-8'); // Définir l'encodage et le type de contenu
    $response->headers->set('Content-Type', 'text/csv');
    $filename = sprintf('voitures_%s.csv', date('Y-m-d')); // date actuelle au format YYYY-MM-DD
    $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
    return $response;
}
#[Route('/imprimer_reservations_voitures', name: 'imprimer_reservations_voitures')]
public function imprimerResVoituresAction(ManagerRegistry $doctrine)
{
    $em = $doctrine->getManager();
    // Récupérer les informations des voitures depuis la base de données
    $reservations = $em->getRepository(ReservationVoiture::class)->findAll();

 // Créer le fichier CSV avec l'encodage UTF-8 et une marque d'ordre des octets (BOM)
 $csv = Writer::createFromString('');
 $csv->setDelimiter("\t");  // Définir le point-virgule comme délimiteur
 $csv->setOutputBOM(Writer::BOM_UTF8); // Définir l'encodage UTF-8 avec BOM

 // Insérer la première ligne d'en-tête
 $csv->insertOne(['ID Réservation Voiture', 'Date Début Réservation', 'Date Fin Réservation', 'ID Utilisateur', 'ID Voiture', 'État Demande Réservation', 'Description Réservation', 'Date Demande Réservation']);

 // Insérer les données de réservation
 foreach ($reservations as $reservation) {
     $csv->insertOne([$reservation->getId(), $reservation->getDateDebutReservation()->format('Y-m-d'), $reservation->getDateFinReservation()->format('Y-m-d'), $reservation->getIdUser(), $reservation->getIdVoiture(), $reservation->getEtatDemandeReservation(), $reservation->getDescriptionReservation(), $reservation->getDateDemandeReservation()->format('Y-m-d')]);
 }

 // ...

 // Envoyer le fichier CSV en tant que réponse
 $response = new Response($csv->getContent());
 $response->headers->set('Content-Type', 'text/csv; charset=UTF-8'); // Définir l'encodage et le type de contenu
 $filename = sprintf('voitures_%s.csv', date('Y-m-d'));
 $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename));
 return $response;
}



     
   
}