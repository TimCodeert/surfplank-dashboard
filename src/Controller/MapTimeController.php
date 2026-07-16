<?php

namespace App\Controller;

use App\Repository\MapRepository;
use App\Repository\MapTimeRepository;
use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MapTimeController extends AbstractController
{
    #[Route('/map/{name}/times', name: 'app_map_times')]
    public function mapLeaderboard(string $name, MapTimeRepository $timeRepository, MapRepository $mapRepository): Response
    {
        $map = $mapRepository->findMapByName($name);
        if (!$map) {
            throw $this->createNotFoundException('Map not found');
        }

        $times = $timeRepository->findLeaderboardForMap($map->getId());

        return $this->render('maps/times.html.twig', [
            'map' => $map,
            'times' => $times,
        ]);
    }

    #[Route('/player/{id}/times', name: 'app_player_times')]
    public function playerTimes(int $id, MapTimeRepository $timeRepository, PlayerRepository $playerRepository): Response
    {
        $player = $playerRepository->find($id);
        
        if (!$player) {
            throw $this->createNotFoundException('Player not found');
        }

        $times = $timeRepository->findTimesForPlayer($id);

        return $this->render('players/times.html.twig', [
            'player' => $player,
            'times' => $times,
        ]);
    }
}