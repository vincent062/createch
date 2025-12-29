<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Repository\PrestationRepository; // <--- Import indispensable
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class PrestationController extends AbstractController
{
    // 1. C'est cette partie qui manquait : La liste des services
    #[Route('/services', name: 'app_prestation_index')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        return $this->render('prestation/index.html.twig', [
            'prestations' => $prestationRepository->findAll(),
        ]);
    }

    // 2. La page de détail (que vous aviez déjà)
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