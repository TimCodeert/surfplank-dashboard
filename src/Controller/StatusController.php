<?php

namespace App\Controller;

use App\Repository\MapRepository;
use App\Repository\PlayerRepository;
use App\Repository\MapTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class StatusController extends AbstractController
{
    #[Route('/', name: 'app_home', options: ['sitemap' => ['priority' => 1, 'changefreq' => UrlConcrete::CHANGEFREQ_ALWAYS]])] 
    public function index(
        MapRepository $mapRepository,
        PlayerRepository $playerRepository,
        MapTimeRepository $mapTimeRepository
        ): Response
    {
        $players = $playerRepository->findOnlinePlayers();
        $map = $mapRepository->findActiveMap();
        $activities = $mapTimeRepository->getLastActivity();

        return $this->render('status/index.html.twig', [
            'players' => $players,
            'map' => $map,
            'activities' => $activities,
        ]);
    }
}