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

        $allTimes = $timeRepository->findTimeForPlayer($player->getId(), $name);

        if (empty($allTimes)) {
            throw $this->createNotFoundException('No times found for this player on this map');
        }

        $mapTime = null;
        $stageTimes = [];

        foreach ($allTimes as $time) {
            if ($time->getType() === 0) {
                $mapTime = $time;
            } 
            elseif ($time->getType() === 2) {
                $stageTimes[$time->getStage() - 1] = $time->getFormattedTime();
            }
        }

        ksort($stageTimes);

        $wrTime = null;
        if ($mapTime) {
            $wrTime = $timeRepository->findWorldRecord(
                $mapTime->getMap()->getId(),
                $mapTime->getType(),
                $mapTime->getStage()
            );
        }

        return $this->render('maps/checkpoints.html.twig', [
            'mapTime'    => $mapTime,
            'stageTimes' => $stageTimes,
            'wrTime'     => $wrTime,
        ]);
    }

}