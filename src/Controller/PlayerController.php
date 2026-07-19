<?php

namespace App\Controller;

use App\Repository\AchievementRepository;
use App\Repository\MapTimeRepository;
use App\Repository\PlayerRepository;
use App\Service\Achievement\AchievementManager;
use App\Mapper\PlayerPaginationMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class PlayerController extends AbstractController
{
    #[Route('/players', name: 'app_players', options: ['sitemap' => ['priority' => 0.7, 'changefreq' => UrlConcrete::CHANGEFREQ_DAILY]])]
    public function index(
        PlayerRepository $playerRepository, 
        PlayerPaginationMapper $paginationMapper,
        Request $request
    ): Response {
        $page      = $request->query->getInt('page', 1);
        $search    = $request->query->get('search', null);
        $sort      = $request->query->get('sort', 'lastSeen');
        $direction = $request->query->get('direction', 'desc');
        $limit     = 40;

        $paginator = $playerRepository->findPaginatedPlayers($page, $limit, $sort, $direction, $search);
        $viewData = $paginationMapper->map($paginator, $page, $limit, $sort, $direction, $search);

        return $this->render('players/index.html.twig', $viewData);
    }
    
    #[Route('/player/{steamId}', name: 'app_player')]
    public function player(
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
        $unlocked = $achievementRepository->findUnlockedKeysForPlayer($player);

        $times = $timeRepository->findTimesForPlayer($player->getId());

        $mainTimes = array_filter($times, fn($time) => !$time->isBonus());
        $bonusTimes = array_filter($times, fn($time) => $time->isBonus());

        return $this->render('players/times.html.twig', [
            'player' => $player,
            'mainTimes' => $mainTimes,
            'bonusTimes' => $bonusTimes,
            'unlockedKeys' => $unlocked,
            'achievements' => $achievementManager->getDefinitions()
        ]);
    }
}