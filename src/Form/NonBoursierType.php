<?php

namespace App\Form;

use App\Entity\NonBoursier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NonBoursierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->remove('nom')
            ->remove('prenom')
            ->remove('login')
            ->remove('telephone')
            ->remove('dateNaissance', DateType::class,[
                'by_reference' => true,
                'widget'=>'single_text',
            ])
            ->add('adresse' ,TextType::class ,[
                'attr' => [
                    'Placeholder' => 'Entrer l\'adresse'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NonBoursier::class,
            'required'   => false
        ]);
    }
}
