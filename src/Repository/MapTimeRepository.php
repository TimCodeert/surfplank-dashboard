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
     * Get total maptimes
     * @return int
     */
    public function countMapTimes(): int
    {
        return $this->createQueryBuilder('mp')
            ->select('count(mp.id)')
            ->getQuery()
            ->getSingleScalarResult();
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
     * Get time for player
     * @return MapTime[]
     */
    public function findTimeForPlayer(int $playerId, string $mapName): array
    {
        return $this->createQueryBuilder('mt')
            ->join('mt.map', 'm')
            ->where('mt.player = :playerId')
            ->andWhere('m.name = :mapName')
            ->setParameter('playerId', $playerId)
            ->setParameter('mapName', $mapName)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get WR time for map
     * @return ?MapTime
     */
    public function findWorldRecord(int $mapId, int $type, int $stage = 0): ?MapTime
    {
        return $this->createQueryBuilder('mt')
            ->join('mt.rankedData', 'rd')
            ->where('mt.map = :mapId')
            ->andWhere('mt.type = :type')
            ->andWhere('mt.stage = :stage')
            ->andWhere('rd.worldwideRank = 1')
            ->setParameter('mapId', $mapId)
            ->setParameter('type', $type)
            ->setParameter('stage', $stage)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get WR times
     * @return MapTime[]
     */
    public function getWorldRecords(): array
    {
        return $this->createQueryBuilder('mt')
            ->join('mt.rankedData', 'rd')
            ->andWhere('rd.worldwideRank = 1')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all Stage World Records for a map (Type 2, Rank 1 per stage)
     * @param int $mapId
     * @return MapTime[] Indexed by stage number
     */
    public function findStageRecordsForMap(int $mapId): array
    {
        $records = $this->createQueryBuilder('mt')
            ->select('mt', 'p', 'rd')
            ->join('mt.player', 'p')
            ->join('mt.rankedData', 'rd')
            ->where('mt.map = :mapId')
            ->andWhere('mt.type = 2') // Type 2 = Stage times
            ->andWhere('rd.worldwideRank = 1') // WR van die specifieke stage
            ->orderBy('mt.stage', 'ASC')
            ->setParameter('mapId', $mapId)
            ->getQuery()
            ->getResult();

        $indexedRecords = [];
        foreach ($records as $record) {
            $indexedRecords[$record->getStage()] = ['player' => $record->getPlayer(), 'time' => $record->getRunTime()];
        }

        return $indexedRecords;
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