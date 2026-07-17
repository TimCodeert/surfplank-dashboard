<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class FollowTheLineAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'follow_the_line'; }
    public function getName(): string { return 'Follow the line'; }
    public function getDescription(): string { return 'Complete a linear map'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if ($time->getMap()->isLinear()) {
                return true;
            }
        }

        return false;
    }
}