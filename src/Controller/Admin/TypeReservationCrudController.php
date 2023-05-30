<?php

namespace App\Controller\Admin;

use App\Entity\TypeReservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeReservation::class;
    }

    // permet de configurer le CRUD.
    // ex : changer le nom de la page, le nom de l'entité dans l'index, le nombre maximum de données visibles par page, etc.
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // permet de définir les labels utilisés pour faire référence à cette entité dans les titres, les boutons, etc.
            // définit le label 'Types de réservation' de cette entité dans l'index
            ->setEntityLabelInPlural('Types de réservation')
            // définit le label 'Type de réservation' de cette entité dans la page de modification
            ->setEntityLabelInSingular('Type de réservation')

            // définit le nom de la page
            ->setPageTitle('index', 'Projet RR - Administration des types de réservation')
            
            // définit le nombre de données à afficher par page.
            ->setPaginatorPageSize(20);
    }
    
    // permet de configurer les champs de l'entité
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            // l'id ne sera pas visible sur la page de modification.
                ->hideOnForm(),
            TextField::new('nom'),
            TextField::new('description'),
        ];
    }
    
}
