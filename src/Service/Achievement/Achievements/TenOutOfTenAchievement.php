<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class TenOutOfTenAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'ten_out_of_ten'; }
    public function getName(): string { return 'Ten out of Ten!'; }
    public function getDescription(): string { return 'Complete at least 10 maps'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());
        return count($times) >= 10;
    }
}