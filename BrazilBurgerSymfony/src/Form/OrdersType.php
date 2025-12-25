<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Extra;
use App\Entity\Orders;
use App\Entity\Payment;
use App\Entity\Zone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('totalPrice')
            ->add('orderDate', null, [
                'widget' => 'single_text',
            ])
            ->add('orderState')
            ->add('receptionType')
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'id',
            ])
            ->add('zone', EntityType::class, [
                'class' => Zone::class,
                'choice_label' => 'id',
            ])
            ->add('extra', EntityType::class, [
                'class' => Extra::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
