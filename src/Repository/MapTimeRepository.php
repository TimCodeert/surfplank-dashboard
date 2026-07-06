<?php

namespace App\Repository;

use App\Entity\MapTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @extends ServiceEntityRepository<MapTime>
 */
class MapTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MapTime::class);
    }

    /**
     * Get the leaderboard for a specific map (sorted by fastest time)
     * @return MapTime[]
     */
    public function findLeaderboardForMap(int $mapId): array
    {
        return $this->createQueryBuilder('mt')
            ->leftJoin('mt.player', 'p')
            ->addSelect('p')
            ->andWhere('mt.map = :mapId')
            ->setParameter('mapId', $mapId)
            // No Bonusses
            ->andWhere('mt.type = 0') 
            ->orderBy('mt.runTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all times achieved by a specific player
     * @return MapTime[]
     */
    public function findTimesForPlayer(int $playerId): array
    {
        $entityManager = $this->getEntityManager();

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata(MapTime::class, 'mt');
        
        $rsm->addScalarResult('worldwide_rank', 'rank');

        $sql = "
            SELECT mt.*, ranked_times.worldwide_rank
            FROM MapTimes mt
            JOIN Maps m ON mt.map_id = m.id
            INNER JOIN (
                SELECT id,
                       RANK() OVER (PARTITION BY map_id, style, type, stage ORDER BY run_time ASC) as worldwide_rank
                FROM MapTimes
            ) ranked_times ON mt.id = ranked_times.id
            WHERE mt.player_id = :playerId
            AND m.ranked = :isRanked
            ORDER BY mt.type ASC, mt.run_time ASC
        ";

        $query = $entityManager->createNativeQuery($sql, $rsm);

        $query->setParameter('playerId', $playerId);
        $query->setParameter('isRanked', true);
        

        return $query->getResult();
    }
}