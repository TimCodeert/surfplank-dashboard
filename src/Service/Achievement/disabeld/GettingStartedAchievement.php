<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class GettingStartedAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'getting_started'; }
    public function getName(): string { return 'Getting started'; }
    public function getDescription(): string { return 'Complete surf_easy2, surf_beginner and surf_how2surf.'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        $completedMapNames = array_map(function ($time) {
            return $time->getMap()->getName();
        }, $times);

        $requiredMaps = ['surf_easy2', 'surf_beginner', 'surf_how2surf'];
        $missingMaps = array_diff($requiredMaps, $completedMapNames);

        return empty($missingMaps);
    }
}