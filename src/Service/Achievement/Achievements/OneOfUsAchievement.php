<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class OneOfUsAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'one_of_us'; }
    public function getName(): string { return 'One of us'; }
    public function getDescription(): string { return 'Finish a map having at least 50 total completions'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findMapTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if ($time->getRankedData()->getTotalCompletions() >= 50) {
                return true;
            }
        }

        return false;
    }
}