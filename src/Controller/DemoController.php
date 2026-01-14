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
}