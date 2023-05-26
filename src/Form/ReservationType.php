<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Reservation;
use App\Entity\TypeReservation;
use App\Entity\GestionnaireSalle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Description :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('start', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Date de début :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'widget' => 'single_text'
            ])
            ->add('duration', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Durée :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('end', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Date de fin :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'widget' => 'single_text'
            ])
            ->add('participant_number', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Nombre de participants :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
            
                'choice_label' => function(Salle $element) {
                    return "{$element->getNom()}";
                },
            
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Salle :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('type_reservation', EntityType::class, [
                'class' => TypeReservation::class,
            
                'choice_label' => function(TypeReservation $element) {
                    return "{$element->getNom()}";
                },
            
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Type de réservation :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('gestionnaire_salle', EntityType::class, [
                'class' => GestionnaireSalle::class,
            
                'choice_label' => function(GestionnaireSalle $element) {
                    return "{$element->getNom()} {$element->getPrenom()}";
                },
            
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Gestionnaire de salle :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('background_color', ColorType::class, [
                'label' => 'Couleur de fond',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('border_color', ColorType::class, [
                'label' => 'Couleur de la bordure',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('text_color', ColorType::class, [
                'label' => 'Couleur du texte',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('all_day', CheckboxType::class, [
                'label' => 'L\'événement dure-t-il toute la journée ?',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
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
