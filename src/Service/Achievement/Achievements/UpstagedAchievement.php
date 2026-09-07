<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class UpstagedAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'upstaged'; }
    public function getName(): string { return 'Upstaged'; }
    public function getDescription(): string { return 'Complete at least 100 stages'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findStageRecordsForPlayer($player->getId());
        return count($times) >= 100;
    }
}