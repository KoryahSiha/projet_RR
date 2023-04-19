<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('date_debut')
            ->add('duree')
            ->add('date_fin')
            ->add('nombre_participant')
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
            
                'choice_label' => function(Salle $element) {
                    return "{$element->getNom()} (id {$element->getId()})";
                },
            
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
            ->add('type_reservation', EntityType::class, [
                'class' => TypeReservation::class,
            
                'choice_label' => function(TypeReservation $element) {
                    return "{$element->getNom()} (id {$element->getId()})";
                },
            
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
            ->add('gestionnaire_salle', EntityType::class, [
                'class' => GestionnaireSalle::class,
            
                'choice_label' => function(GestionnaireSalle $element) {
                    return "{$element->getNom()} {$element->getPrenom()} ({id {$element->getId()})";
                },
            
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
