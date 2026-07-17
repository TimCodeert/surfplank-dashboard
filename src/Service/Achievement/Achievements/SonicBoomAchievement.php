<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class SonicBoomAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'sonic_boom'; }
    public function getName(): string { return 'Sonic boom'; }
    public function getDescription(): string { return 'Finish a run going at least 3500u/s.'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if ($time->getEndSpeed() >= 3500) {
                return true;
            }
        }

        return false;
    }
}