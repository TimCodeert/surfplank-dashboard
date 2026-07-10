<?php

namespace App\Controller;

use App\Service\ServerStatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatusController extends AbstractController
{
    #[Route('/', name: 'app_home')] 
    #[Route('/status', name: 'app_status')]
    public function index(ServerStatusService $serverStatusService): Response
    {
        $serverStatus = $serverStatusService->getServerStatus();

        return $this->render('status/index.html.twig', [
            'server' => $serverStatus,
        ]);
    }
}