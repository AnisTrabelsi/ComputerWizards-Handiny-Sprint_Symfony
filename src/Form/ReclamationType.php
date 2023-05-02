<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $types = [
            'Site'=> 'Site',
            'Couvoiturage'=>'Couvoiturage',
            'Don'=>'Don',
            'Voiture'=>'Voiture',

        ];
        $builder
            ->add('type_reclamation', ChoiceType::class, [
                'choices' => $types,
                'label' => 'Type reclamation',
                'required' => true,
                'placeholder' => 'Votre reclamation concerne :'])
            
            ->add('description')
            
          
            ->add('Save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
