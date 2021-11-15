<?php

namespace App\Controller\Admin;

use App\Entity\Bid;
use App\Entity\User;
use App\Entity\Pickup;
use App\Entity\Address;
use App\Entity\Article;
use App\Entity\Category;
use App\Controller\Admin\UserCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Encheres Eni');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-shopping-bag', Article::class);
        yield MenuItem::linkToCrud('Adresses', 'fas fa-address-card', Address::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list-alt', Category::class);
        yield MenuItem::linkToCrud('Mises', 'fas fa-money-bill-alt', Bid::class);
        yield MenuItem::linkToCrud('Points de retraits', 'fas fa-shipping-fast', Pickup::class);
        yield MenuItem::section();
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-backward', 'home');
    }
}
