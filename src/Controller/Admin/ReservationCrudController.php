<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    // permet de configurer le CRUD.
    // ex : changer le nom de la page, le nom de l'entité dans l'index, le nombre maximum de données visibles par page, etc.
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // permet de définir les labels utilisés pour faire référence à cette entité dans les titres, les boutons, etc.
            // définit le label 'Réservations' de cette entité dans l'index
            ->setEntityLabelInPlural('Réservations')
            // définit le label 'Réservation' de cette entité dans la page de modification
            ->setEntityLabelInSingular('Réservation')

            // définit le nom de la page
            ->setPageTitle('index', 'Projet RR - Administration des réservations')
            
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
            TextField::new('title', 'Nom'),
            TextField::new('description'),
            DateTimeField::new('start', 'Date et heure de début'),
            DateTimeField::new('end', 'Date et heure de fin'),
            IntegerField::new('participant_number', 'Nombre de participants')
                ->hideOnIndex(),
            TextField::new('duration', 'Durée')
                ->hideOnIndex(),
            BooleanField::new('all_day', 'Toute la journée')
                ->hideOnIndex(),
            // AssociationField permet d'afficher le contenu d'une propriété utilisée pour associer des entités entre elles.
            AssociationField::new('salle', 'Salle'),
            AssociationField::new('type_reservation', 'Type de réservation'),
            AssociationField::new('gestionnaire_salle', 'Gestionnaire de salle')
                ->hideOnIndex(),
            ColorField::new('background_color', 'Couleur de fond')
                ->hideOnIndex(),
            ColorField::new('border_color', 'Couleur de la bordure')
                ->hideOnIndex(),
            ColorField::new('text_color', 'Couleur de texte')
                ->hideOnIndex(),
        ];
    }
    
}
