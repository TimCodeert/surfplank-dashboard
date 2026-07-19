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

    #[Route('/map/{name}/{steamId}/checkpoints', name: 'app_player_map_checkpoints')]
    public function mapCheckpoints(
        string $name, 
        int $steamId, 
        MapTimeRepository $timeRepository,
        PlayerRepository $playerRepository,
    ): Response {
        $player = $playerRepository->findPlayerBySteamId($steamId);

        if (!$player) {
            throw $this->createNotFoundException('Player not found');
        }

        $mapTime = $timeRepository->findTimeForPlayer($player->getId(), $name);

        if (!$mapTime) {
            throw $this->createNotFoundException('No time found');
        }

        $wrTime = $timeRepository->findWorldRecord(
                    $mapTime->getMap()->getId(),
                    $mapTime->getType(),
                    $mapTime->getStage()
                );

        return $this->render('maps/checkpoints.html.twig', [
            'mapTime'     => $mapTime,
            'wrTime'      => $wrTime,
        ]);
    }

}