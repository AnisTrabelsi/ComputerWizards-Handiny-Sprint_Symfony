<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
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
           
        ->add('email', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ Email est obligatoire'
                ]),
                new Assert\NotNull([
                    'message' => 'Le champ Email ne peut pas être null'
                ]),
            ]
        ])
        ->add('telephone', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ Téléphone est obligatoire'
                ]),
                new Assert\NotNull([
                    'message' => 'Le champ Téléphone ne peut pas être null'
                ]),
            ]
        ])
        ->add('login', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ Login est obligatoire'
                ]),
                new Assert\NotNull([
                    'message' => 'Le champ Login ne peut pas être null'
                ]),
            ]
        ])
        ->add('region', ChoiceType::class, [
            'choices' => $regions,
            'label' => 'Région',
            'required' => true,
            'placeholder' => 'Choisissez une région',
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ Région est obligatoire'
                ]),
                new Assert\NotNull([
                    'message' => 'Le champ Région ne peut pas être null'
                ]),
            ]
        ])
        ->add('adresse', null, [
            'constraints' => [
                new Assert\NotBlank([
                    'message' => 'Le champ Adresse est obligatoire'
                ]),
                new Assert\NotNull([
                    'message' => 'Le champ Adresse ne peut pas être nul'
                ]),  ]
                ])
                ->add('code_postal', null, [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Le champ Code Postal est obligatoire'
                        ]),
                        new Assert\NotNull([
                            'message' => 'Le champ Code Postal ne peut pas être nul'
                        ]),
                    ]
                ])
            ->add('Update',SubmitType::class)
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}