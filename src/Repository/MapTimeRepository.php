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
        return $this->createQueryBuilder('mt')
            ->select('mt', 'rd')
            ->join('mt.map', 'm')
            ->join('mt.rankedData', 'rd')
            ->where('mt.player = :playerId')
            ->andWhere('m.ranked = :isRanked')
            ->setParameter('playerId', $playerId)
            ->setParameter('isRanked', true)
            ->orderBy('rd.worldwideRank', 'ASC')
            ->getQuery()
            ->getResult();  
    }

    /**
     * Get the latest activities on the server
     * @param int $limit
     * @return MapTime[]
     */
    public function getLastActivity(int $limit = 10): array
    {
        return $this->createQueryBuilder('mt')
            ->select('mt', 'p', 'm', 'rd')
            ->join('mt.player', 'p')
            ->join('mt.map', 'm')
            ->join('mt.rankedData', 'rd')
            ->where('mt.type = :type')
            ->andWhere('mt.stage = :stage')
            ->setParameter('type', 0)
            ->setParameter('stage', 0)
            ->orderBy('mt.runTimestamp', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}