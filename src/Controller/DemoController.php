<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demo')]
class DemoController extends AbstractController
{
    #[Route('/restaurant', name: 'app_demo_restaurant')]
    public function restaurant(): Response
    {
        // On n'étend PAS base.html.twig car on veut un design totalement différent
        return $this->render('demo/restaurant.html.twig');
    }

    #[Route('/boutique', name: 'app_demo_boutique')]
    public function boutique(): Response
    {
        return $this->render('demo/boutique.html.twig');
    }

    #[Route('/food_truck', name: 'app_demo_foodtruck')]
    public function foodtruck(): Response
    {
        return $this->render('demo/foodtruck.html.twig');
    }

    #[Route('/gym', name: 'app_demo_gym')]
    public function gym(): Response
    {
        return $this->render('demo/gym.html.twig');
    }
}