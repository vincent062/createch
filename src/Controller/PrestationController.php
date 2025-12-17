<?php

namespace App\Controller;

use App\Entity\Prestation;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PrestationController extends AbstractController
{
    #[Route('/service/{slug}', name: 'app_prestation_show')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Prestation $prestation
    ): Response
    {
        return $this->render('prestation/show.html.twig', [
            'prestation' => $prestation,
        ]);
    }
}