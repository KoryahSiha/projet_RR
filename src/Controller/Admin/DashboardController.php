<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Salle;
use App\Entity\Domaine;
use App\Entity\Reservation;
use App\Entity\TypeReservation;
use App\Entity\GestionnaireSalle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Projet RR - Administration')
            ->renderContentMaximized();
    }
    
    public function configureMenuItems(): iterable
    {
        
        // ajoute un lien vers l'accueil dans le dashboard
        yield MenuItem::linkToRoute('Accueil', 'fas fa-home', 'app_home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-user', User::class);

        yield MenuItem::section('Calendrier');
        yield MenuItem::linkToCrud('Gestionnaires de salle', 'fa-solid fa-person-dots-from-line', GestionnaireSalle::class);
        yield MenuItem::linkToCrud('Domaines', 'fa-solid fa-building-user', Domaine::class);
        yield MenuItem::linkToCrud('Salles', 'fa-solid fa-person-shelter', Salle::class);
        yield MenuItem::linkToCrud('Types de réservation', 'fa-solid fa-calendar-week', TypeReservation::class);
        yield MenuItem::linkToCrud('Réservations', 'fa-solid fa-calendar-days', Reservation::class);

    }
}
