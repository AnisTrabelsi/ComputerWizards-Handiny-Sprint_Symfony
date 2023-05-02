<?php

namespace App\Controller;

use App\Entity\User;
//use App\Form\UserType;
use App\Form\UserUpdateType;

use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError ;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    

    //Vue utilisateur update profil
    #[Route('/pathapp_update_user{id}', name: 'app_update_user')]
    public function updateUser(Request $request,ManagerRegistry $doctrine,UserRepository $rep,FlashBagInterface $flashBag  ): Response
    {
        $user = $this->getUser();
        $formuu=$this->createForm(UserUpdateType::class,$user,['allow_extra_fields'=>true ]) ;
        $formuu->handleRequest($request);
        
        if ($this->isGranted('ROLE_ADMIN')) {
            // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
            return $this->redirectToRoute('app_homback');
        }
        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
        if ($formuu-> isSubmitted() && $formuu->isValid()){
            $em=$doctrine->getManager();
            $em->flush($user);
            $flashBag->add('success', 'Votre compte a été mis a jour !');

            return $this->redirectToRoute('app_homefront');
        }

        if (!$this->getUser()) {
            // Rediriger l'utilisateur vers la page de login
            return $this->redirectToRoute('app_login');
        }
    
        return $this->renderForm('user/update.html.twig', [
            'userformupdate' => $formuu,
        ]);
    }

   // Vue admin liste utilisateurs 

    #[Route('/pathapp_list_user', name: 'app_list_user')]
    public function getAll(Request $request,UserRepository $repo, PaginatorInterface $paginator): Response
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
        $list=$repo->findAll();
        $list = $paginator->paginate(
            $list, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
             8 // Nombre de résultats par page
        );
        return $this->render('user/read.html.twig', [
            'users' => $list,
        ]);
    }

    
   //Vue admin effcer un utilisateur
    #[Route('/pathapp_remove_user{id}', name: 'app_remove_user')]
    public function removeUser(Request $request,ManagerRegistry $doctrine,FlashBagInterface $flashBag  ): Response
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
      
        $iduser=$request->get('id');
        $repo=$doctrine->getRepository(User::class);
        $user=$repo->find($iduser);
            $em=$doctrine->getManager();
            $em->remove($user);
            $em->flush();
            $flashBag->add('warning', 'Utilisateur est supprimé !');

    return $this->redirectToRoute("app_list_user");
    
}
//vue admin profil de l'utilisateur 
#[Route('/pathapp_profil_user{id}', name: 'app_profil_user')]
public function Dashboard(Request $request, ManagerRegistry $doctrine): Response
{
    $iduser = $request->get('id');
    $repo = $doctrine->getRepository(User::class);
    $user = $repo->find($iduser);
    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }
    return $this->render('user/profil_admin.html.twig', [
        'user' => $user,
    ]);
    
    if (!$this->isGranted('ROLE_ADMIN')) {
        // L'utilisateur n'a pas les autorisations nécessaires pour accéder à la page
        throw new AccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        return $this->redirectToRoute('app_homefront');
    }
    if (!$this->getUser()) {
        // Rediriger l'utilisateur vers la page de login
        return $this->redirectToRoute('app_login');
    }
}
// Vue Admin reclamation par utilisateur 
#[Route('/reclamation_par_user_admin{id}', name: 'app_mdp_user')]
public function reclam_par_user(Request $request, ManagerRegistry $doctrine): Response
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
    $iduser = $request->get('id');
    $repo = $doctrine->getRepository(User::class);
    $user = $repo->find($iduser);
    return $this->render('user/reclam_par_user.html.twig', [
        'user' => $user,
    ]);
}
}
