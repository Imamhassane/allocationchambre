<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Pavillon;
use App\Entity\TypeChambre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numChambre')
            ->add('numEtage')
            ->add('pavillon', EntityType::class,[
                'class' =>  Pavillon::class,
                'placeholder' => 'Affecter à un pavillon',
                'choice_label'=> 'num_pavillon'
            ])
            ->add('typeChambre', EntityType::class,[
                'class' =>  TypeChambre::class,
                'placeholder' => 'Choisir le type de chambre',
                'choice_label'=> 'type'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
            'required'   => false,
            
        ]);
    }
}
