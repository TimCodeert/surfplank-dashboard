<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class OverworkedAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'overworked'; }
    public function getName(): string { return 'Overworked'; }
    public function getDescription(): string { return 'Complete at least 30 maps'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findMapTimesForPlayer($player->getId());
        return count($times) >= 30;
    }
}