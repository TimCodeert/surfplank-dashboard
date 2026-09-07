<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Service\Achievement\AchievementInterface;

class GoingProAchievement implements AchievementInterface
{
    public function __construct(
    ) {}

    public function getKey(): string { return 'going_pro'; }
    public function getName(): string { return 'Going pro'; }
    public function getDescription(): string { return 'Reach the rank "Pro"'; }

    public function isQualified(Player $player): bool
    {
        return $player->getRank()->getTotalPoints() >=  5000;
    }
}