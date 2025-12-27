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
                'choice_label' => function (Customer $customer) {
                    return $customer->getName() . ' ' . $customer->getSurname();
                },
                'placeholder' => 'Sélectionnez un client',
            ])
            ->add('zone', EntityType::class, [
                'class' => Zone::class,
                'choice_label' => 'name', // Affiche le nom de la zone
                'placeholder' => 'Sélectionnez une zone',
                'required' => false, // Pas obligatoire pour les commandes non-livrées
            ])
            ->add('extra', EntityType::class, [
                'class' => Extra::class,
                'choice_label' => 'name', // Affiche le nom de l'extra
                'multiple' => true,
                'expanded' => false, // Pour une liste déroulante multiple
                'attr' => ['class' => 'select2'], // Optionnel: pour une meilleure UX
            ])
            ->add('payment', EntityType::class, [
                'class' => Payment::class,
                'choice_label' => function (Payment $payment) {
                    // Vous devez vérifier quelle propriété afficher
                    // Si vous avez un champ 'type' ou 'method' dans Payment
                    // Sinon, vous pouvez afficher l'ID avec d'autres infos
                    return $payment->getPaymentMethod() . ' - ' . $payment->getAmount() . ' FCFA';
                },
                'placeholder' => 'Sélectionnez un paiement',
                'required' => false,
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