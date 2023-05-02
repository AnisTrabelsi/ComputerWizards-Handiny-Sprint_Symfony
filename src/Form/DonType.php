<?php

namespace App\Form;

use App\Entity\Don;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class DonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'label' => 'Choose a type:',
            'choices' => [
                'Choisissez le type a ajouter' => '',
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
        ])

            ->add('imageDon',FileType::class,[
                'label' => 'imageDon : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2Mi',
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],])
            ->add('description',TextareaType::class )
            ->add('Save', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
