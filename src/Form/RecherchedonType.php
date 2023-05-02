<?php

namespace App\Form;

use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecherchedonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'label' => 'Choose a type:',
            'choices' => [
                'Tous' => 'Tous',
                'Fauteuils roulants' => 'Fauteuils roulants',
                'Prothèses' => 'Prothèses',
                'Appareils auditifs' => 'Appareils auditifs',
                'Lunettes et lentilles de contact' => 'Lunettes et lentilles de contact',
                'Béquilles' => 'Béquilles',
                'Équipement de soins à domicile' => 'Équipement de soins à domicile',
                'Rampes d accès' => 'Rampes d accès',
                'Appareils de levage' => 'Appareils de levage',
                'Sièges de bain' => 'Sièges de bain',
                'autre' => 'autre',
            ],
        ], )
        ->add('description',null, [
            'error_bubbling' => true,
        ])
        ->add('Chercher', SubmitType::class,)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
