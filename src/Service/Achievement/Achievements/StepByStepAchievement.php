<?php
namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class StepByStepAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'step_by_step'; }
    public function getName(): string { return 'Step by step'; }
    public function getDescription(): string { return 'Complete a staged map'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            if (!$time->getMap()->isLinear()) {
                return true;
            }
        }

        return false;
    }
}