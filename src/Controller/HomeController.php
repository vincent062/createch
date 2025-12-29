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
        // On demande à Symfony de chercher UNIQUEMENT ces 3 identifiants
        // REMPLACEZ PAR VOS PROPRES ID !!!
        $services = $prestationRepository->findBy(
            ['id' => [18, 19, 20]], 
            ['id' => 'ASC'] // Cela les trie dans l'ordre croissant des ID
        );

        return $this->render('home/index.html.twig', [
            'prestations' => $services,
        ]);
    }
}