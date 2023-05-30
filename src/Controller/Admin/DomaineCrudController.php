<?php

namespace App\Controller\Admin;

use App\Entity\Domaine;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DomaineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Domaine::class;
    }

    // permet de configurer le CRUD.
    // ex : changer le nom de la page, le nom de l'entité dans l'index, le nombre maximum de données visibles par page, etc.
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // permet de définir les labels utilisés pour faire référence à cette entité dans les titres, les boutons, etc.
            // définit le label 'Domaines' de cette entité dans l'index
            ->setEntityLabelInPlural('Domaines')
            // définit le label 'Domaine' de cette entité dans la page de modification
            ->setEntityLabelInSingular('Domaine')

            // définit le nom de la page
            ->setPageTitle('index', 'Projet RR - Administration des domaines')
            
            // définit le nombre de données à afficher par page.
            ->setPaginatorPageSize(20);
    }
    
    // permet de configuer les champs de l'entité
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            // l'id ne sera pas visible sur la page de modification.
                ->hideOnForm(),
            TextField::new('nom')
        ];
    }
    
}
