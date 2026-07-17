<?php
namespace App\Service\Achievement;

use App\Entity\Player;

interface AchievementInterface
{
    public function getKey(): string;
    public function getName(): string;
    public function getDescription(): string;
    public function isQualified(Player $player): bool;
}