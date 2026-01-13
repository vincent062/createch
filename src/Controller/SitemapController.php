<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use App\Repository\RealisationRepository; // Ajout du repository Realisation
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_sitemap', defaults: ['_format' => 'xml'])]
    public function index(
        PrestationRepository $prestationRepository,
        RealisationRepository $realisationRepository
    ): Response
    {
        $urls = [];

        // 1. LES PAGES STATIQUES
        $staticRoutes = [
            'app_home' => ['changefreq' => 'weekly', 'priority' => 1.0],
            'app_contact' => ['changefreq' => 'monthly', 'priority' => 0.5],
            'app_prestation_index' => ['changefreq' => 'weekly', 'priority' => 0.8],
            'app_realisation_index' => ['changefreq' => 'weekly', 'priority' => 0.8],
            // Ajoute ici 'app_mentions' ou 'app_privacy' si tu veux
        ];

        foreach ($staticRoutes as $route => $config) {
            try {
                $urls[] = [
                    'loc' => $this->generateUrl($route, [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'changefreq' => $config['changefreq'],
                    'priority' => $config['priority']
                ];
            } catch (\Exception $e) {
                continue;
            }
        }

        // 2. LES PRESTATIONS (Dynamique)
        foreach ($prestationRepository->findAll() as $prestation) {
            $urls[] = [
                'loc' => $this->generateUrl('app_prestation_show', ['slug' => $prestation->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'changefreq' => 'weekly',
                'priority' => 0.9 // Haute importance car c'est ce que tu vends
            ];
        }

        // 3. LE PORTFOLIO (Dynamique - Optionnel si tu veux lister chaque projet)
        /* foreach ($realisationRepository->findAll() as $realisation) {
            // Si tu crées une page de détail pour les réalisations un jour :
            // $urls[] = [ ... ];
        }
        */

        // Construction de la réponse XML
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['urls' => $urls]),
            200
        );

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}