<?php
namespace App\Service\Achievement;

use App\Entity\Player;
use App\Entity\Achievement;
use App\Repository\AchievementRepository;
use App\Service\Achievement\AchievementInterface;
use Doctrine\ORM\EntityManagerInterface;

class AchievementManager
{
    /**
     * @param iterable<AchievementInterface> $achievements
     */
    public function __construct(
        private iterable $achievements,
        private AchievementRepository $achievementRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Scan achievements for a player
     * @return string[] Newly unlocked achievements!
     */
    public function checkAllForPlayer(Player $player): array
    {
        $existingKeys = $this->achievementRepository->findUnlockedKeysForPlayer($player);

        $newlyUnlocked = [];

        foreach ($this->achievements as $achievement) {
            if (in_array($achievement->getKey(), $existingKeys)) {
                continue;
            }

            if ($achievement->isQualified($player)) {
                $progress = new Achievement($player, $achievement->getKey());
                $this->entityManager->persist($progress);
                
                $newlyUnlocked[] = $achievement->getName();
            }
        }

        if (!empty($newlyUnlocked)) {
            $this->entityManager->flush();
        }

        return $newlyUnlocked;
    }
    
    public function getDefinitions(): array
    {
        return iterator_to_array($this->achievements);
    }
}