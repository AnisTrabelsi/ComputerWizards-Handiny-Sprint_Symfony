<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

//use Symfony\Component\Security\Core\Role\Role;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles =[
            'Proprietaire de voiture ' => 'Proprietaire de voiture' ,
            'Locataire de voiture' => 'Locataire de voiture' ,
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
            ->add('nom')
            ->add('prenom')
            ->add('cin')
            ->add('email')
            ->add('telephone')
            ->add('login')
            ->add('password', PasswordType::class)
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
                    'Locataire' => 'ROLE_LOCATAIRE',
                    'Propriétaire' => 'ROLE_PROPRIETAIRE',
                ],
                'multiple' => true,
            ])
           // ->add('code')
            //SubmitType pour comprendre qu'il s'agit d'un bouton
            ->add('Save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
