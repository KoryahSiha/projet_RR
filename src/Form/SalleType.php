<?php

namespace App\Form;

use App\Entity\Domaine;
use App\Entity\Salle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('personnes_max')
            ->add('domaine', EntityType::class, [
                'class' => Domaine::class,
                'choice_label' => function(Domaine $element) {
                    return "{$element->getNom()} (id {$element->getId()})";
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
            'data_class' => Salle::class,
        ]);
    }
}
