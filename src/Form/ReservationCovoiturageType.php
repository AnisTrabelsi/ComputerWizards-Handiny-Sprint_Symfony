<?php

namespace App\Form;

use App\Entity\ReservationCovoiturage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Covoiturage;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReservationCovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix_covoiturage')
            ->add('depart')
            ->add('destination')
            // ->add('idCov')
            ->add('idCov',EntityType::class,[
                'class'=>covoiturage::class,
                'choice_label'=>'id'
                ]) ->add('reservationCovoiturage',SubmitType::class)
 ;
} 
     

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservationCovoiturage::class,
        ]);
    }
}
