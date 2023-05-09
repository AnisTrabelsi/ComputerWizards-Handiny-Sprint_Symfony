<?php

namespace App\Form;

use App\Entity\ReservationChauffeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Chauffeur;


class ReservationChauffeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dureeService')
            ->add('datePriseEnCharge')
            ->add('descriptionDemande')
            ->add('idChauffeur')
            ->add('idChauffeur', EntityType::class, [
                'class' => Chauffeur::class,
                'choice_label' => 'nom',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationChauffeur::class,
        ]);
    }
}
