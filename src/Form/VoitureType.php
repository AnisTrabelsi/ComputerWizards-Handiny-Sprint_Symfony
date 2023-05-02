<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;


class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('immatriculation')
            ->add('marque')
            ->add('modele')
            ->add('boite_vitesse', ChoiceType::class, [
                'choices' => [
                    'Manuelle' => 'Manuelle',
                    'Automatique' => 'Automatique',
                ],
            ])
            ->add('kilometrage')
            ->add('carburant')
            /*->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                
            ])*/
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de voiture',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => false
            ])

            /*->add('image_voiture',FileType::class,[
                'label' => 'imageVoiture : ',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2Mi',
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],])*/
        
            ->add('prix_location')
            ->add('date_validation_technique', null, [
                'data' => new \DateTime(),
            ])
            ->add('description')
            


            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
