<?php

namespace App\Form;

use App\Entity\Neighbourhood;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NeighbourhoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du quartier',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Plateau, Cocody, Marcory...'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Neighbourhood::class,
        ]);
    }
}