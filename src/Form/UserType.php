<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\GestionnaireSalle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [];

        foreach (User::ROLES as $role) {
            $key = $role;
            $key = str_replace('ROLE_', '', $key);
            $key = strtolower($key);
            $key = ucfirst($key);
            $choices[$key] = $role;
        }

        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control w-50',
                    'minlength' => '2',
                    'maxlength' => '180',
                ],
                'label' => 'Adresse email :',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle(s) :',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'choices' => $choices,
                // si la valeur est 'true', plusieurs options peuvent être sélectionnées.
                'multiple' => true,
                // si la valeur est 'true', des boutons radio ou des cases à cocher seront rendus. Si la valeur est 'false', un élément select sera rendu.
                'expanded' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control w-50'
                    ],
                    'label' => 'Mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control w-50'
                    ],
                    'label' => 'Confirmation du mot de passe :',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.'
            ])
            ->add('gestionnaireSalle', EntityType::class, [
                'class' => GestionnaireSalle::class,
                
                'choice_label' => function(GestionnaireSalle $element) {
                    return "{$element->getNom()} {$element->getPrenom()}";
                },
                'attr' => [
                    'class' => 'form-control w-50'
                ],
                'label' => 'Gestionnaire de salle :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'multiple' => false,
                'expanded' => false,
                // autorise à laisser vide, n'est pas requis.
                'required' => false,
                ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'L\'utilisateur est-il actif ?',
                'label_attr' => [
                'class' => 'form-check-label  mt-4'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
