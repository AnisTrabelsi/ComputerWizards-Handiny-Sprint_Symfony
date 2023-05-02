<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Covoiturage;

use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
class CovoimapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('depart')
        ->add('destination')
        ->add('date_covoiturage', DateTimeType::class, [
            'label' => 'Choisissez votre date de covoiturage ',
            'required' => true ,
            'data' => new \DateTime(),
            'widget' => 'single_text',
            
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 'today',
                    'message' => 'La date et l\'heure doivent être supérieures ou égales à la date actuelle.',
                ]),
            ],
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                'class' => 'form-control datetimepicker-input',
            ],
           
        ])
        ->add('Prix')
        ->add('nbrplace')
        ->add('latitude', HiddenType::class)
        ->add('longitude', HiddenType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Add'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
        ]);
    }
}


