<?php

namespace App\Controller\Admin;

use App\Entity\Salle;
use App\Entity\Domaine;
use App\Controller\Admin\DomaineCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SalleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Salle::class;
    }

    // permet de configurer le CRUD.
    // ex : changer le nom de la page, le nom de l'entité dans l'index, le nombre maximum de données visibles par page, etc.
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // permet de définir les labels utilisés pour faire référence à cette entité dans les titres, les boutons, etc.
            // définit le label 'Salle' de cette entité dans la page de modification
            ->setEntityLabelInSingular('Salle')

            // définit le nom de la page
            ->setPageTitle('index', 'Projet RR - Administration des salles')

            // trie les noms des domaines par ordre croissant
            ->setDefaultSort(['nom' => 'ASC'])
            
            // définit le nombre de données à afficher par page.
            ->setPaginatorPageSize(20);
    }
    
    // permet de configurer les champs de l'entité
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            // l'id ne sera pas visible sur la page de modification
                ->hideOnForm(),
            TextField::new('nom'),
            // AssociationField permet d'afficher le contenu d'une propriété utilisée pour associer des entités entre elles.
            AssociationField::new('domaine'),
            TextField::new('description'),
            IntegerField::new('personnes_max')
                ->hideOnIndex(),
        ];
    }
    
}
