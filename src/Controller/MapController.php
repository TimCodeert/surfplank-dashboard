<?php

namespace App\Controller;

use App\Repository\MapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class MapController extends AbstractController
{
    #[Route('/maps', name: 'app_maps', options: ['sitemap' => ['priority' => 0.7, 'changefreq' => UrlConcrete::CHANGEFREQ_DAILY]])]
    public function index(MapRepository $mapRepository): Response
    {
        $rankedMaps = $mapRepository->findRankedMaps();

        return $this->render('maps/index.html.twig', [
            'maps' => $rankedMaps,
        ]);
    }
}