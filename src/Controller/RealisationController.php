<?php

namespace App\Controller;

use App\Entity\Realisation;
use App\Repository\RealisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RealisationController extends AbstractController
{
    #[Route('/portfolio', name: 'app_realisation_index')]
    public function index(RealisationRepository $realisationRepository): Response
    {
        return $this->render('realisation/index.html.twig', [
            // On récupère toutes les réalisations (tu pourras ajouter un tri par date plus tard)
            'realisations' => $realisationRepository->findAll(),
        ]);
    }
}