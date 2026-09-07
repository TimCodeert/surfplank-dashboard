<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Service\Achievement\AchievementInterface;

class GettingTheHangOfItAchievement implements AchievementInterface
{
    public function __construct(
    ) {}

    public function getKey(): string { return 'getting_the_hang_of_it'; }
    public function getName(): string { return 'Getting the hang of it'; }
    public function getDescription(): string { return 'Reach the rank "Regular"'; }

    public function isQualified(Player $player): bool
    {
        return $player->getRank()->getTotalPoints() >=  1000;
    }
}