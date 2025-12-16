<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        // Récupère tes services depuis la base de données
        $services = $prestationRepository->findAll();

        return $this->render('home/index.html.twig', [
            'prestations' => $services,
        ]);
    }
}