<?php

namespace App\Service\Achievement\Achievements;

use App\Entity\Player;
use App\Repository\MapTimeRepository;
use App\Service\Achievement\AchievementInterface;

class FlawlessAchievement implements AchievementInterface
{
    public function __construct(
        private MapTimeRepository $mapTimeRepository
    ) {}

    public function getKey(): string { return 'flawless_run'; }
    public function getName(): string { return 'Flawless!'; }
    public function getDescription(): string { return 'Complete a staged map with zero retries on any stage'; }

    public function isQualified(Player $player): bool
    {
        $times = $this->mapTimeRepository->findTimesForPlayer($player->getId());

        foreach ($times as $time) {
            $map = $time->getMap();

            if ($map->isLinear()) {
                continue;
            }

            $checkpoints = $time->getCheckpoints();

            if (count($checkpoints) === 0) {
                continue;
            }

            $isFlawless = true;

            foreach ($checkpoints as $checkpoint) {
                if ($checkpoint->getAttempts() > 1) {
                    $isFlawless = false;
                    break;
                }
            }

            if ($isFlawless) {
                return true;
            }
        }

        return false;
    }
}