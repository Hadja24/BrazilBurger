<?php

namespace App\Form;

use App\Entity\Orders;
use App\Entity\DeliveryGuy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignDeliveryGuyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deliveryGuy', EntityType::class, [
                'class' => DeliveryGuy::class,
                'choices' => $options['delivery_guys'],
                'choice_label' => function (DeliveryGuy $deliveryGuy) {
                    return $deliveryGuy->getAccount()->getName() . ' ' . $deliveryGuy->getAccount()->getSurname();
                },
                'label' => 'Livreur',
                'placeholder' => 'Choisir un livreur...',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
            'delivery_guys' => [],
        ]);
    }
}