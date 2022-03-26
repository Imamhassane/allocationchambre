<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Boursier;
use App\Entity\TypeBourse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EBoursierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('login' , TextType::class)
            ->add('telephone')
            ->add('bourse', EntityType::class,[
                'class' =>  TypeBourse::class,
                'placeholder' => 'Type de bourse',
                'choice_label'=> 'type'
            ])
            ->add('chambre', EntityType::class,[
                    'class' =>  Chambre::class,
                    'placeholder' => 'Affecter à une chambre',
                    'choice_label'=> 'num_chambre'
            ])
            ->add('dateNaissance', DateType::class,[
                'by_reference' => true,
                'widget'=>'single_text',
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boursier::class,
            'required'   => false
        ]);
    }
}
