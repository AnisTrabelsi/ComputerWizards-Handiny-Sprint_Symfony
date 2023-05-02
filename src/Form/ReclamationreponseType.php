<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReclamationreponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $etat = [
            'En attente'=> 'En attente',
            'En cours de traitement'=>'En cours de traitement',
            'En attente de réponse'=>'En attente de réponse',
            'Résolue'=>'Résolue',
            'Fermée'=> 'Fermée',
            'Rejetée'=> 'Rejetée',

        ];
        $builder
        ->add('etat_reclamation', ChoiceType::class, [
            'choices' => $etat,
            'label' => 'Type reclamation',
            'required' => true,
            'placeholder' => 'la reclamation est actuellement en  :'])
            ->add('reponse')
            ->add('Update',SubmitType::class)
          
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}