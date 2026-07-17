<?php
namespace App\Repository;

use App\Entity\Achievement;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Achievement>
 */
class AchievementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Achievement::class);
    }

    public function findUnlockedKeysForPlayer(Player $player): array
    {
        $results = $this->createQueryBuilder('ap')
            ->select('ap.achievementKey')
            ->where('ap.player = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->getScalarResult();

        return array_column($results, 'achievementKey');
    }
}