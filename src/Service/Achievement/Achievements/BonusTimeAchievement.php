<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class BonusTimeAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'bonus_time'; }
    public function getName(): string { return 'Bonus time!'; }
    public function getDescription(): string { return 'Complete a bonus stage'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if ($time->isBonus()) {
                return true;
            }
        }

        return false;
    }
}