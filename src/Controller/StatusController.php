<?php

namespace App\Controller;

use App\Service\ServerStatusService;
use App\Repository\MapRepository;
use App\Repository\MapTimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatusController extends AbstractController
{
    #[Route('/', name: 'app_home')] 
    #[Route('/status', name: 'app_status')]
    public function index(ServerStatusService $serverStatusService, MapRepository $mapRepository, MapTimeRepository $mapTimeRepository): Response
    {
        $serverStatus = $serverStatusService->getServerStatus();
        $map = $mapRepository->findMapByName($serverStatus['map']);
        $activities = $mapTimeRepository->getLastActivity();

        return $this->render('status/index.html.twig', [
            'server' => $serverStatus,
            'map' => $map,
            'activities' => $activities,
        ]);
    }
}