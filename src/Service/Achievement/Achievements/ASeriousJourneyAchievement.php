<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class ASeriousJourneyAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'a_serious_journey'; }
    public function getName(): string { return 'A serious journey'; }
    public function getDescription(): string { return 'Complete a staged map with at least 10 stages.'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if ($time->getMap()->getStages() >= 10) {
                return true;
            }
        }

        return false;
    }
}