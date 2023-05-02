<?php

namespace App\Controller;

use App\Entity\Covoiturage;
use App\Form\CovoiturageType;
use App\Form\CovoiturageUType;
use App\Service\Twilio;

use App\Repository\CovoiturageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\CovoimapType ; 
// use Symfony\Component\Mailer\MailerInterface;
// use Symfony\Component\Mime\Email;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use App\Services\Servicegeo;
#[Route('/covoiturage')]
class CovoiturageController extends AbstractController
{

private $Servicegeo ; 

public function __construct(Servicegeo $Servicegeo)
    {
        $this->Servicegeo = $Servicegeo;
      
    }


    #[Route('/', name: 'app_covoiturage_index', methods: ['GET','POST'])]
    public function index(CovoiturageRepository $covoiturageRepository,Request $request): Response
    {
        {
            
            $covoiturage = $covoiturageRepository->findAll();
            // Traiter la soumission du formulaire de tri
            $triAscendant = false;
            if ($request->getMethod() === 'POST') {
                $triAscendant = $request->request->get('tri_ascendant');
                
            }
    
            // Si le tri descendant est sélectionné
           
            // Si le tri ascendant est sélectionné
         if ($triAscendant) {
                $covoiturage = $covoiturageRepository->findBydateDsc();
            }
    
            return $this->render('covoiturage/index.html.twig', [
                'covoiturages' => $covoiturage,
               
                'tri_ascendant' => $triAscendant,
            ]);
    }
}


#[Route('supprm/{id}', name: 'app_covoiturage_delete2', methods: ['POST'])]
public function deleteb(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
{
    if ($this->isCsrfTokenValid('delete'.$covoiturage->getId(), $request->request->get('_token'))) {
        $covoiturageRepository->remove($covoiturage, true);
    }
// return $this->render('covoiturage/statistique.html.twig');
    return $this->redirectToRoute('app_covoiturage_index1', [], Response::HTTP_SEE_OTHER);
}




    #[Route('/Mescovoiturage', name: 'app_covoiturage_indexM', methods: ['GET'])]
    public function indexM(CovoiturageRepository $covoiturageRepository): Response
    {
        return $this->render('covoiturage/Mescovoiturage.html.twig', [
            'covoiturages' => $covoiturageRepository->findAll(),
        ]);
    }

    #[Route('/rechs', name: 'bookx')]
    public function index11(Request $request, CovoiturageRepository $covoiturageRepository, PaginatorInterface $paginator): Response
    {
        $search = $request->query->get('search');

        $queryBuilder = $covoiturageRepository->createQueryBuilder('v');
        $queryBuilder1 = $covoiturageRepository->createQueryBuilder('v');

        if ($search) {
            $queryBuilder->andWhere('v.depart LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

         $queryBuilder1->orderBy('v.date_covoiturage', 'DESC')->setMaxResults(7);
         $query1 = $queryBuilder1->getQuery();
         $recentBooks = $query1->getResult();

         $queryBuilder = $covoiturageRepository->createQueryBuilder('v');
         if ($search) {
             $queryBuilder->andWhere('v.depart LIKE :search')
                 ->setParameter('search', '%' . $search . '%');
         }
        $query = $queryBuilder->getQuery();
        $covoiturage = $paginator->paginate($query, $request->query->getInt('page', 1), 12);

        return $this->render('covoiturage/index.html.twig', [
            'covoiturages' => $covoiturage,
            'recentBooks' => $recentBooks,
            'search' => $search,
            
        ]);
    }
    #[Route('/rech', name: 'book', options: ["expose" => true])]
    public function index1(Request $request, CovoiturageRepository $covoiturageRepository, PaginatorInterface $paginator): Response
{
    $search = $request->query->get('search');

    $queryBuilder = $covoiturageRepository->createQueryBuilder('v');
    $queryBuilder1 = $covoiturageRepository->createQueryBuilder('v');

    if ($search) {
        $queryBuilder->andWhere('v.depart LIKE :search')
            ->setParameter('search', '%' . $search . '%');
    }

     $queryBuilder1->orderBy('v.date_covoiturage', 'DESC')->setMaxResults(7);
     $query1 = $queryBuilder1->getQuery();
     $recentBooks = $query1->getResult();

     $queryBuilder = $covoiturageRepository->createQueryBuilder('v');
     if ($search) {
         $queryBuilder->andWhere('v.depart LIKE :search')
             ->setParameter('search', '%' . $search . '%');
     }
    $query = $queryBuilder->getQuery();
    $covoiturage = $paginator->paginate($query, $request->query->getInt('page', 1), 12);

    // check if the request is an AJAX request and return a partial view with the search results
    if ($request->isXmlHttpRequest()) {
        return $this->render('covoiturage/search_results.html.twig', [
            'covoiturages' => $covoiturage,
            'search' => $search
        ]);
    }

    // otherwise, return the regular view
    return $this->render('covoiturage/index.html.twig', [
        'covoiturages' => $covoiturage,
        'recentBooks' => $recentBooks,
        'search' => $search
    ]);
}

    

// public function sendSms(Client $twilio, $to, $message)
// {
//     $from = '++15673716202'; // Your Twilio phone number
//     $twilio->messages->create($to, ['from' => $from, 'body' => $message]);
// }





#[Route('/state', name: 'stat', methods: ['GET', 'POST'])]
     public function statistiques(CovoiturageRepository  $commandeRepository){
         // On va chercher toutes les catégories
 
         $commande = $commandeRepository->countByDate();
         $dates = [];
         $commandeCount = [];
         //$categColor = [];
         foreach($commande as $com){
             $dates[] = $com['date_covoiturage'];
             $commandeCount[] = $com['count'];
         }
         return $this->render('covoiturage/statistique.html.twig', [
             'dates' => json_encode($dates),
             'commandeCount' => json_encode($commandeCount),
         ]);
 
 
     }





    #[Route('/cal', name: 'app_cal', methods: ['GET'])]
public function cal(CovoiturageRepository $appointmentRepository)
{
    $events = $appointmentRepository->findAll();

    $rdvs = [];

    foreach ($events as $event) {
        $rdvs[] = [
            'id' => $event->getId(),
            'start' => $event->getDateCovoiturage()->format('Y-m-d H:i:s'),
            'end' => $event->getDateCovoiturage()->format('Y-m-d H:i:s'),
            'title' => $event->getDestination(),
        ];
    }

 

    $data = json_encode($rdvs);

    return $this->render('covoiturage/showCalendar.html.twig', compact('data'));
}


// #[Route('/get-address/{lat}/{lng}', name: 'getadress')]
// public function map(GoogleMapsService $googleMapsService)
// {
//     $lat = 40.714224;
//     $lng = -73.961452;

//     $address = $googleMapsService->getAddress($lat, $lng);

//     return new Response($address);
// }

#[Route('/get-address/{lat}/{lng}', name: 'getadress')]
    public function geocoding($lat, $lng)
    {
        $apiKey = 'pk.eyJ1IjoiZmplcmJpIiwiYSI6ImNrdWp6bXJhdTE4MGwyd215bzhpb3c0OGYifQ.jW0ZovMg20DoAaiOtGkPhg';
        $url = sprintf('https://api.mapbox.com/geocoding/v5/mapbox.places/%s,%s.json?access_token=%s', $lng, $lat, $apiKey);
        
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        
        if (empty($data['features'][0]['place_name'])) {
            throw new \Exception('Address not found');
        }
        return new Response($data['features'][0]['place_name']);
       
    }



    #[Route('/mapi/add', name: 'docc', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {
        $doctor = new Covoiturage();

        $form = $this->createForm(CovoiturageType::class, $doctor,[
            'attr' => [
                'novalidate' => 'novalidate' ]
            ]);

        $form->handleRequest($request      );

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('app_covoiturage_index');
        }
      
        $mapboxAccessToken = 'pk.eyJ1IjoiZmplcmJpIiwiYSI6ImNrdWp6bXJhdTE4MGwyd215bzhpb3c0OGYifQ.jW0ZovMg20DoAaiOtGkPhg';

        return $this->render('covoiturage/add.html.twig', [
            'form' => $form->createView(),
            'mapbox_access_token' => $mapboxAccessToken,
        ]);
    }
    
        
   







// #[Route('/calen/{id}', name: 'app_calen', methods: ['GET'])]
//     public function calen( CovoiturageRepository $repository)
//     {
//         $reservationsapprouve = $repository->findBy(['user' => 1]);
//         // $reservationsnonapprouve = $repository->findBy(['user' => $user,'approved'=>0]); //,'approved'=>1

       

//         $rdvs = [];

//         foreach ($reservationsapprouve as $event) {
//             $rdvs[] = [
//                 'id' => $event->getId(),
//                 'start' => $event->getDateCovoiturage()->format('Y-m-d H:i:s'),
//                 'end' => $event->getDateCovoiturage()->format('Y-m-d H:i:s'),
//                 'title' => $event->getDestination(),
//                 'backgroundColor' => 'green',

//             ];
//         }
//         // foreach ($reservationsnonapprouve as $event) {
//         //     $rdvs[] = [
//         //         'id' => $event->getId(),
//         //         'start' => $event->getAppointmentDate()->format('Y-m-d H:i:s'),
//         //         'end' => $event->getDatefin()->format('Y-m-d H:i:s'),
//         //         'title' => $event->getCategorie(),
//         //         'backgroundColor' => 'red',

//         //     ];
//         // }
        
//         $data = json_encode($rdvs);
//         //dd($data);
        

//         return $this->render('covoiturage/showCalendar.html.twig', compact('data'));
//     }

    // #[Route('/email', name: 'email')]

    //          public function sendEmail(MailerInterface $mailer)
    //          {
    //              $email = (new Email())
    //                  ->from('commercial.edusex@gmail.com')
    //                  ->to('mohamed.benabbes@esprit.tn')
    //                  ->subject('Hello from Symfony Mailer!')
    //                  ->text('Sending emails is fun again!')
    //                  ->html('<p>hello! a new Event has been added today !<em>fun</em> again!</p>');
             
    //              $mailer->send($email);
             
    //              return new Response("Success");
    //          }

    #[Route('/Back', name: 'app_covoiturage_index1', methods: ['GET'])]
    public function indexB(CovoiturageRepository $covoiturageRepository): Response
    {
        return $this->render('covoiturage/indexB.html.twig', [
            'covoiturages' => $covoiturageRepository->findAll(),
        ]);
    }

    public function list(CovoiturageRepository $repository ,Request $request )
    {
        $covoiturage= $this->getDoctrine()->getRepository(Covoiturage::class)->findAll();

        ////
        $back = null;
            
        if($request->isMethod("POST")){
            
                    $type = $request->request->get('optionsearch');
                    $value = $request->request->get('Search');
                    switch ($type){
                        case 'nomtype':
                            $covoiturage = $repository->findByDep($value);
                            break;
    
                        
    
                    }
                }
            if ( $covoiturage){
                $back = "success";
            }else{
                $back = "failure";
            }
        
    
    return $this->render('covoiturage/index.html.twig',['types'=>$covoiturage,'back'=>$back]);
    }

    #[Route('/searchAJ', name: 'searchAJ')]

      public function searchAJ(Request $request)
      {
          $em = $this->getDoctrine()->getManager();
          $requestString = $request->get('q');
          $covoiturage = $em->getRepository(Typeappoinment::class)->findEntitiesByString($requestString);
          if (!$typeappointment) {
              $result['covoiturage']['error'] = "stock not found 🙁";
          } else {
                   $result['covoiturage'] = $this->getRealEntities($covoiturage);
                 }
          return new Response(json_encode($result));
      }








    #[Route('/new', name: 'app_covoiturage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CovoiturageRepository $covoiturageRepository): Response
    {
        $covoiturage = new Covoiturage();
        $form = $this->createForm(CovoiturageType::class, $covoiturage,[
            'attr' => [
                'novalidate' => 'novalidate' ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $covoiturageRepository->save($covoiturage, true);

            return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('covoiturage/new.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form,
        ]);
    }





    #[Route('/newback', name: 'app_covoiturage_new1', methods: ['GET', 'POST'])]
    public function newB(Request $request, CovoiturageRepository $covoiturageRepository): Response
    {
        $covoiturage = new Covoiturage();
        $form = $this->createForm(CovoiturageType::class, $covoiturage,[
            'attr' => [
                'novalidate' => 'novalidate' ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $covoiturageRepository->save($covoiturage, true);

            return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('covoiturage/newB.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/appointment/{id}", name="app_appointment_detail")
     */
//     #[Route('/covoituragesssss/{id}', name: 'app_cov_detail', methods: ['GET'])]
//     public function appointmentDetails(Covoiturage $appointment, Request $request, EntityManagerInterface $entityManager)
//     {
//     //     $id = $request->get('id');
//     //     $appointments = $this->getDoctrine()
//     //         ->getRepository(Covoiturage::class)

//     //         ->find($id); 
//     //  $app = $this->Servicegeo->geocoding($appointments->getLatitude(),$appointments->getLongitude()) ; 

//     //    $app =  geocoding($appointment.getLatitude(),$appointment.getLongitude()) ; 
//         return $this->render('covoiturage/covmap.html.twig', [
//             'appointment' => $appointment,
// // 'app'  => $app ,
//         ]);
//     }

    #[Route('/covoituragesssss/{id}', name: 'app_cov_detail', methods: ['GET'])]
    public function appointmentDetails(Covoiturage $appointment, Request $request, EntityManagerInterface $entityManager)
    {
        $id = $request->get('id');
        $appointments = $this->getDoctrine()
            ->getRepository(Covoiturage::class)
            ->find($id);
    
        $lat = $appointment->getLatitude();
        $lng = $appointment->getLongitude();
    
        $app = $this->geocoding($lat, $lng);
    
        return $this->render('covoiturage/covmap.html.twig', [
            'appointment' => $appointment,
            'app' => $app,
        ]);
    }
    




    





    #[Route('/{id}', name: 'app_covoiturage_show', methods: ['GET'])]
    public function show(int $id, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository ): Response
    {
        $covoiturage = $covoiturageRepository->find($id);

        if (!$covoiturage) {
            throw $this->createNotFoundException('The covoiturage does not exist');
        }
        return $this->render('covoiturage/show.html.twig', [
            'covoiturage' => $covoiturage,
        ]);
    }

    #[Route('/{id}/back', name: 'app_covoiturage_show1', methods: ['GET'])]
    public function showB(Covoiturage $covoiturage): Response
    {
        return $this->render('covoiturage/showB.html.twig', [
            'covoiturage' => $covoiturage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_covoiturage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
    {
        $form = $this->createForm(CovoiturageUType::class, $covoiturage,[
            'attr' => [
                'novalidate' => 'novalidate' ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $covoiturageRepository->save($covoiturage, true);

            return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('covoiturage/edit.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit/back', name: 'app_covoiturage_edit1', methods: ['GET', 'POST'])]
    public function editB(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
    {
        $form = $this->createForm(CovoiturageUType::class, $covoiturage,[
            'attr' => [
                'novalidate' => 'novalidate' ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $covoiturageRepository->save($covoiturage, true);

            return $this->redirectToRoute('app_covoiturage_index1', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('covoiturage/editB.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_covoiturage_delete', methods: ['POST'])]
    public function delete(Request $request, Covoiturage $covoiturage, CovoiturageRepository $covoiturageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$covoiturage->getId(), $request->request->get('_token'))) {
            $covoiturageRepository->remove($covoiturage, true);
        }

        return $this->redirectToRoute('app_covoiturage_index', [], Response::HTTP_SEE_OTHER);
    }


    
    #[Route('/covoiturage/{id}/reserver', name: 'covoiturage_reserver', methods: ['POST'])]
    public function reserver(Covoiturage $covoiturage, Request $request): Response
    {
        // Créer une nouvelle réservation de covoiturage
        $reservation = new ReservationCovoiturage();
        $reservation->setDepart($covoiturage->getDepart());
        $reservation->setDestination($covoiturage->getDestination());
        $reservation->setPrixCovoiturage($covoiturage->getPrix());
        $reservation->setCovoiturage($covoiturage);

        // Gérer le formulaire de réservation de covoiturage
        $form = $this->createForm(ReservationCovoiturageType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Decrement the nbrplace field of the Covoiturage entity
        // $covoiturage->setNbrplace($covoiturage->getNbrplace() - 1);
            // Sauvegarder la réservation de covoiturage dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Rediriger vers la page de confirmation de réservation de covoiturage
            return $this->redirectToRoute('reservation_covoiturage_confirmation');
        }

        // Afficher le formulaire de réservation de covoiturage
        return $this->render('covoiturage/reserver.html.twig', [
            'covoiturage' => $covoiturage,
            'form' => $form->createView(),
        ]);
    }



//     #[Route('/search', name: 'app_covoiturage_search')]
//     public function search(Request $request, CovoiturageRepository $covoiturageRepository): Response
// {
//     $query = $request->query->get('q');

//     if ($query) {
//         $covoiturages = $covoiturageRepository->findByKeyword($query);
//     } else {
//         $covoiturages = $covoiturageRepository->findAll();
//     }

//     return $this->render('covoiturage/index.html.twig', [
//         'covoiturages' => $covoiturages,
//     ]);
// }




 

}

