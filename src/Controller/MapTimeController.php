<?php

namespace App\Controller;

use App\Repository\AchievementRepository;
use App\Repository\MapRepository;
use App\Repository\MapTimeRepository;
use App\Repository\PlayerRepository;
use App\Service\Achievement\AchievementManager;
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

    #[Route('/player/{steamId}/times', name: 'app_player_times')]
    public function playerTimes(
        int $steamId,
        MapTimeRepository $timeRepository,
        PlayerRepository $playerRepository,
        AchievementRepository $achievementRepository,
        AchievementManager $achievementManager,
        ): Response
    {
        $player = $playerRepository->findPlayerBySteamId($steamId);
        
        if (!$player) {
            throw $this->createNotFoundException('Player not found');
        }

        $achievementManager->checkAllForPlayer($player);
        $times = $timeRepository->findTimesForPlayer($player->getId());
        $unlocked = $achievementRepository->findUnlockedKeysForPlayer($player);

        return $this->render('players/times.html.twig', [
            'player' => $player,
            'times' => $times,
            'unlockedKeys' => $unlocked,
            'achievements' => $achievementManager->getDefinitions()
        ]);
    }
}