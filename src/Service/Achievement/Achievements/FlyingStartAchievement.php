<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class FlyingStartAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'flying_start'; }
    public function getName(): string { return 'Flying start'; }
    public function getDescription(): string { return 'Finish a run with a startspeed of 300u/s.'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if ($time->getStartSpeed() >= 300) {
                return true;
            }
        }

        return false;
    }
}