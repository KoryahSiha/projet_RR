<?php

namespace App\Controller\Admin;

use App\Entity\GestionnaireSalle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GestionnaireSalleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GestionnaireSalle::class;
    }

    // permet de configurer le CRUD.
    // ex : changer le nom de la page, le nom de l'entité dans l'index, le nombre maximum de données visibles par page, etc.
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // permet de définir les labels utilisés pour faire référence à cette entité dans les titres, les boutons, etc.
            // définit le label 'Gestionnaires de salle' de cette entité dans l'index
            ->setEntityLabelInPlural('Gestionnaires de salle')
            // définit le label 'Gestionnaire de salle' de cette entité dans la page de modification
            ->setEntityLabelInSingular('Gestionnaire de salle')

            // définit le nom de la page
            ->setPageTitle('index', 'Projet RR - Administration des gestionnaires de salle')

            // trie les noms des domaines par ordre croissant
            ->setDefaultSort(['nom' => 'ASC'])
            
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
            // AssociationField permet d'afficher le contenu d'une propriété utilisée pour associer des entités entre elles.
            AssociationField::new('user', 'Utilisateur'),
            TextField::new('nom'),
            TextField::new('prenom', 'Prénom'),
        ];
    }
}
