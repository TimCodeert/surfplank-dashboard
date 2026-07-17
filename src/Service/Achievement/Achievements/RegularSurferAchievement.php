<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class RegularSurferAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'regular_surfer'; }
    public function getName(): string { return 'Regular surfer'; }
    public function getDescription(): string { return 'Visit the server at least 50 times.'; }

    public function isQualified(Player $player): bool
    {
        return $player->getConnections() >= 50;
    }
}