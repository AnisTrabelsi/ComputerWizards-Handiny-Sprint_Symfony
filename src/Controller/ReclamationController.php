<?php

namespace App\Controller;

use Swift_Mailer;
use Swift_Message;
use App\Entity\User;
use Swift_Attachment;
use Swift_SmtpTransport;
use App\Entity\Reclamation;
use Doctrine\ORM\Mapping\Id;
use App\Form\ReclamationType;
use App\Repository\UserRepository;
use App\Form\ReclamationUpdateType;
use Symfony\Component\Mime\Message;
use App\Form\ReclamationreponseType;
use Symfony\Component\Mailer\Mailer;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    
   


    #[Route('/reclamation/add', name: 'app_add_reclamation')]
    public function addReclamation(Request $request, ManagerRegistry $doctrine ,FlashBagInterface $flashBag ): Response
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if ($this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        return $this->redirectToRoute('app_homefront');
    }
    $reclam = new Reclamation();
    $formr = $this->createForm(ReclamationType::class, $reclam);
    $formr->handleRequest($request);
    //isSubmitted pour vérifier qu'on a cliqué sur le bouton et is valid pour verifié les controles de saisie
    if ($formr->isSubmitted() && $formr->isValid()) {
        $user = $this->getUser();
        $reclam->setIdUtilisateur($user); 
        $em = $doctrine->getManager();
        $em->persist($reclam);
        $em ->flush();
        $flashBag->add('success', 'Votre reclamtion a été ajouté.');
        $reclam = new Reclamation();
        if ($this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        }
        return $this->redirectToRoute('app_list_reclamation');
        $formr = $this->createForm(ReclamationType::class, $reclam);
    }

    // Return the form to the user
    return $this->renderForm('reclamation/addreclam.html.twig', [
        'reclamationform' => $formr,
    ]);
}
//afficher les reclamations selon l'id de l'utilisateur
#[Route('/reclamation/list', name: 'app_list_reclamation')]
public function getbyid(ReclamationRepository $repo ): Response
{

    // par suite cette 30 sera changé avec l'id de l'utilisateur actuel
    $user = $this->getUser(); 
    $list = $repo->findByUserId($user); 
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if ($this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        return $this->redirectToRoute('app_homefront');
    }
    return $this->render('reclamation/reclamations.html.twig', [
        'reclamations' => $list,
    ]);
}
//Afficher toutes les reclamations
#[Route('/reclamation/cartetype', name: 'app_list_reclam_type')]
public function parType(ReclamationRepository $repo): Response
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }
    $types = ['Site', 'Couvoiturage', 'Don','Voiture'];
    
    return $this->render('reclamation/readall.html.twig', [
        
        'types'=>$types,
        
    ]);
   
}
#[Route('/reclamation/listall', name: 'app_list_reclamation2')]
public function getAll(Request $request,ReclamationRepository $repo,UserRepository $repou , PaginatorInterface $paginator): Response
{
    
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }

    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        return $this->redirectToRoute('app_homefront');
    }
    $type = $request->query->get('type');
    $reclamations = $repo->findBy(['type_reclamation' => $type]);


            $reclamations = $paginator->paginate(
            $reclamations, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
             8 // Nombre de résultats par page
        );
        
    return $this->render('reclamation/readalldetail.html.twig', [
        'reclamations' => $reclamations,
        
    ]);
}

#[Route('/reclamation/remove/{id}', name: 'app_remove_reclamation')]
    public function removeReclamation(Request $request,ManagerRegistry $doctrine ,FlashBagInterface $flashBag  ): Response
    {
       
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        $idreclamation=$request->get('id');
        $repo=$doctrine->getRepository(Reclamation::class);
        $reclamation=$repo->find($idreclamation);
        
        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }
            $em=$doctrine->getManager();
            $em->remove($reclamation);
            $em->flush();
        
            $flashBag->add('warning', 'Votre reclamtion a été supprimé');

    //Pour retourner le formulaire dans une vue
    if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
        return $this->redirectToRoute('app_list_reclam_type');
    } else {
        return $this->redirectToRoute('app_list_reclamation');
    }
    $this->addFlash('Warning','Votre reclamation a été supprimé');
    }
    #[Route('/reclamation/repondre/{id}', name: 'app_repondre_reclamation')]
    public function repondreReclamation(Request $request,ManagerRegistry $doctrine ,FlashBagInterface $flashBag ): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('app_homefront');
        }
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        $idreclamation=$request->get('id');
        $repo=$doctrine->getRepository(Reclamation::class);
        $reclamation=$repo->find($idreclamation);
        $formr= $this->createForm(ReclamationreponseType::class, $reclamation);
    
        $formr->handleRequest($request);
        if ($formr->isSubmitted() && $formr->isValid()) {
            //provisoire jusqu'a commencer a utiliser la session
           // $user = $doctrine->getRepository(User::class)->find(57);
            // $reclamation->setIdUtilisateur($user);
            $em = $doctrine->getManager();
           // $em->persist($reclam);
            $em->flush();
            $flashBag->add('success', 'Votre réponse a été envoyé.');

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
->setUsername('chayma.bensaad@esprit.tn')
->setPassword('223JFT2127');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

$user =$reclamation->getIdUtilisateur();
$nom=$user->getNom();
$prenom=$user->getPrenom();
    // Create a message
    $message = (new Swift_Message('Reponse a votre reclamation'))
    ->setFrom(['chayma.bensaad@esprit.tn' => 'Handiny'])
    ->setTo($user->getEmail())
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
                text-align: center;
                font-size: 24px;
                color: #104D62;
                margin-top: 0;
            }
            .welcome {
                    text-align: center;
                    font-style: italic;
                    font-size: 22px;
                    color: #41AF64;				
                    margin-top: 20px;
                    margin-bottom: 10px;
                }
                p {
                    margin: 10px 0;
                    font-size: 16px;
                    font-weight: bold;
                    color: #104D62;
                }
            .success {
                background-color: #d4edda;
                border-color: #c3e6cb;
                color: #155724;
                padding: 15px;
                border-radius: 5px;
            }
        
            .lien {
                font-size: 18px;
                font-weight: bold;
                color: #0f421f;
    
            }
            .footer {
                font-size: 12px;
                font-weight: bold;
                color: #104D62;
            }
        </style>
            </head>
            <body>
                
                <div class='don-info'>
                    <h1>Monsieur,Madame ". $nom ." ".$prenom ."</h1>
                    <p class='welcome'>Reponse a votre reclamtion!</p>
                    <p >Votre reclamation concerne : " . $reclamation->getTypeReclamation()."</p>
                    <p > La reponse a votre reclamtion :" . $reclamation->getReponse() ."</p>
                </div>
            </body>
        </html>",
        'text/html'
    )

    ;


        $mailer->send($message);
            return $this->redirectToRoute("app_list_reclam_type");
    
        }
        return $this->renderForm('reclamation/repondrereclam.html.twig', [
            'reclamationform' => $formr,
            'reclamation'=>$reclamation,
        ]);

    }


    
    #[Route('/reclamation/update/{id}', name: 'app_update_reclamation')]
    public function updateReclamation(Request $request, ManagerRegistry $doctrine ,FlashBagInterface $flashBag  ): Response
{
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
    if ($this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        return $this->redirectToRoute('app_homefront');
    }
    $idreclamation=$request->get('id');
    $repo=$doctrine->getRepository(Reclamation::class);
    $reclamation=$repo->find($idreclamation);
    $formr= $this->createForm(ReclamationUpdateType::class, $reclamation);

    $formr->handleRequest($request);
    if ($formr->isSubmitted() && $formr->isValid()) {
        $user = $this->getUser() ;

        $reclamation->setIdUtilisateur($user); // Définir l'ID de l'utilisateur associé à la réclamation

        $em = $doctrine->getManager();
       // $em->persist($reclam);
        $em->flush();
        $flashBag->add('success', 'Votre reclamtion a été mise a jour.');

        return $this->redirectToRoute("app_list_reclamation");

    }
    return $this->renderForm('reclamation/updatereclam.html.twig', [
        'reclamationform' => $formr,
        'reclamation'=>$reclamation,
    ]);
}





}
