<?php

namespace App\Controller;

use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PlayerController extends AbstractController
{
    #[Route('/', name: 'app_home')] 
    #[Route('/players', name: 'app_players')]
    public function index(PlayerRepository $playerRepository): Response
    {
        $activePlayers = $playerRepository->getActivePlayers();

        return $this->render('players/index.html.twig', [
            'players' => $activePlayers,
        ]);
    }
}