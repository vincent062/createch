<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Prestation;
use App\Entity\Realisation;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // C'est CETTE ligne qui fait le lien avec ton design HTML/CSS
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CreaTech - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Gestion du site');
        yield MenuItem::linkToCrud('Mes Services', 'fas fa-briefcase', Prestation::class);
        yield MenuItem::linkToCrud('Mon Portfolio', 'fas fa-laptop-code', Realisation::class);

        yield MenuItem::section('Communication');
        yield MenuItem::linkToCrud('Messages reçus', 'fas fa-envelope', Contact::class);

        yield MenuItem::section('Sécurité');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}