<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles =[
            'Locataire de voiture' => 'ROLE_LOCATAIRE',
            'Proprietaire de voiture' => 'ROLE_PROPRIETAIRE',
        ];

        $regions = [
            'Ariana' => 'Ariana',
            'Béja' => 'Béja',
            'Ben Arous' => 'Ben Arous',
            'Bizerte' => 'Bizerte',
            'Gabes' => 'Gabes',
            'Gafsa' => 'Gafsa',
            'Jendouba' => 'Jendouba',
            'Kairaouen' => 'Kairaouen',
            'Kassrine' => 'Kassrine',
            'Kbeli' =>'Kbeli' ,
            'Kef' => 'Kef',
            'Mahdia' => 'Mahdia',
            'Manouba' =>'Manouba' ,
            'Mednine' => 'Mednine',
            'Monastir' => 'Monastir' ,
            'Nabeul' =>'Nabeul' ,
            'Sfax' => 'Sfax ',
            'Sidi Bouzid' => 'Sidi Bouzid' ,
            'Siliana' =>  'Siliana' ,
            'Sousse ' => 'Sousse ' ,
            'Tataouine' => 'Tataouine' ,
            'Tozeur' =>  'Tozeur' ,
            'Tunis' => 'Tunis' ,
            'Mednine' => 'Mednine'  ,
        ];
        $builder
            ->add('email')
            ->add('Accepter_le_reglement', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Accepter les reglemnts du site .',
                    ]),
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'homme',
                    'Femme' => 'femme',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('cin')
            ->add('email')
            ->add('telephone')
            ->add('login')
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control form-control-sm password-strength',
                    'placeholder' => 'Mot de passe',
                    'data-strength-meter' => 'password-strength-meter',
                    'data-strength-meter-wrapper' => 'password-strength-wrapper'
                ]
            ])
            ->add('confirm_password', PasswordType::class)
            ->add('date_de_naissance',DateType::class, [
                'years' => range(date('Y')-100, date('Y')),
            ])
            ->add('region' ,ChoiceType::class, [
                'choices' => $regions,
                'label' => 'Région',
                'required' => true,
                'placeholder' => 'Choisissez une région',]
            )
            ->add('adresse')
            ->add('code_postal')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Locataire de voiture' => 'ROLE_LOCATAIRE',
                    'Proprietaire de voiture' => 'ROLE_PROPRIETAIRE',
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => true,
            ]);
            // ->add('code')
            //SubmitType pour comprendre qu'il s'agit d'un bouton
            $builder->add('captcha', CaptchaType::class, array(
                'width' => 200,
                'height' => 50,
                'length' => 6,
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Le captcha est obligatoire.'
                    )),
                )
            ))
            ->add('save', SubmitType::class);
            
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
           
        ]);
    }
}
