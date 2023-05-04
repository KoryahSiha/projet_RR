<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\GestionnaireSalle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GestionnaireSalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-50',
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Prénom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $element) {
                    return "{$element->getEmail()}";
                },

                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control w-50',
                ],
                'label' => 'Utilisateur :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GestionnaireSalle::class,
        ]);
    }
}
