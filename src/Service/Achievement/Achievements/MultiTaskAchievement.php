<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class MultiTaskAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'multi_task'; }
    public function getName(): string { return 'Multitask'; }
    public function getDescription(): string { return 'Open your profile while being on the server.'; }

    public function isQualified(Player $player): bool
    {
        return $player->isOnline();
    }
}