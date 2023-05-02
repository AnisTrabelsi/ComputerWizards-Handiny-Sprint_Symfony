<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\Mime\Address;
use App\Security\AppUserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }


    #[Route('/pathapp_add_user', name: 'app_add_user')]
    public function register(Request $request, UserPasswordHasherInterface $encoder, UserAuthenticatorInterface $userAuthenticator, AppUserAuthenticator $authenticator, EntityManagerInterface $entityManager, CaptchaBuilder $captchaBuilder) : Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // Vérifier si le code Captcha est correct
            $captchaCode = $request->getSession()->get('captcha_code');
            if ($captchaCode == $request->request->get('captcha')) {
                $this->addFlash('warning', 'Code Captcha incorrect, veuillez réessayer.');
                // Régénérer le code Captcha
                $captcha = $captchaBuilder->build();
                $request->getSession()->set('captcha_code', $captcha->getPhrase());
                // Générer l'URL de l'image Captcha
                $captchaUrl = $captcha->inline();
                // Afficher le formulaire avec l'image Captcha mise à jour
                return $this->render('user/add.html.twig', [
                    'registrationForm' => $form->createView(),
                    'captcha_url' => $captchaUrl,
                    'captcha_width' => 120,
                    'captcha_height' => 30,
                ]);
            }

            if ($form->isValid()) {
                // Encode le mot de passe
                $hash = $encoder->hashPassword($user,$user->getPassword());
                $user->setPassword($hash);
                $data = $form->get('roles')->getData();
                $user->setRoles($data);
                $entityManager->persist($user);
                $entityManager->flush();

                // Envoyer un e-mail de confirmation
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('Chayma.bensaad@esprit.tn', 'Chayma Bot'))
                        ->to($user->getEmail())
                        ->subject('Veuillez confirmer votre adresse e-mail')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

                // Rediriger vers la page de connexion
                return $this->redirectToRoute('app_login');
            }
        }


    // generate the captcha code
    $captcha = $captchaBuilder->build();
    // save the captcha code in the session
    $request->getSession()->set('captcha_code', $captcha->getPhrase());
    // generate the captcha image url
    $captchaUrl = $captcha->inline();

        // set captcha image dimensions
        $captchaWidth = 120;
        $captchaHeight = 30;

    return $this->render('user/add.html.twig', [
        'registrationForm' => $form->createView(),
        'captcha_url' => $captchaUrl,
        'captcha_width' => $captchaWidth,
        'captcha_height' => $captchaHeight,

    ]);
}

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_login');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre addresse est vérifié.');

        return $this->redirectToRoute('app_login');
    }
}