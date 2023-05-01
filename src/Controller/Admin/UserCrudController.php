<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // permet de configurer le CRUD.
    // ex : changer le nom de la page, le nom de l'entité dans l'index, le nombre maximum de données visibles par page, etc.
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // permet de définir les labels utilisés pour faire référence à cette entité dans les titres, les boutons, etc.
            // définit le label 'Utilisateurs' de cette entité dans l'index
            ->setEntityLabelInPlural('Utilisateurs')
            // définit le label 'Utilisateur' de cette entité dans la page de modification
            ->setEntityLabelInSingular('Utilisateur')

            // définit le nom de la page
            ->setPageTitle('index', 'Projet RR - Administration des utilisateurs')
            
            // définit le nombre de données à afficher par page. Ici 20.
            ->setPaginatorPageSize(20);
    }

    
    // permet de configurer les champs
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                // l'id ne sera pas visible sur la page de modification.
                ->hideOnForm(),
            TextField::new('email'),
            ArrayField::new('roles'),
            BooleanField::new('enabled'),
        ];
    }
    
}
