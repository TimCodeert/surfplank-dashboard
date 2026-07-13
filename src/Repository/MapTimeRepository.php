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
        $rsm->addScalarResult('total_completions', 'completions');

        $sql = "
            SELECT mt.*, ranked_times.worldwide_rank, ranked_times.total_completions
            FROM MapTimes mt
            JOIN Maps m ON mt.map_id = m.id
            INNER JOIN (
                SELECT id,
                    COUNT(*) OVER (PARTITION BY map_id, style, type, stage) as total_completions,
                    RANK() OVER (PARTITION BY map_id, style, type, stage ORDER BY run_time ASC) as worldwide_rank
                FROM MapTimes
            ) ranked_times ON mt.id = ranked_times.id
            WHERE mt.player_id = :playerId
            AND m.ranked = :isRanked
            ORDER BY ranked_times.worldwide_rank ASC
        ";

        $query = $entityManager->createNativeQuery($sql, $rsm);

        $query->setParameter('playerId', $playerId);
        $query->setParameter('isRanked', true);
        

        return $query->getResult();
    }

    /**
     * Get the latest activities on the server
     * * @param int $limit
     * @return array
     */
    public function getLastActivity(int $limit = 10): array
    {
        $entityManager = $this->getEntityManager();

        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('run_time', 'runTime');
        $rsm->addScalarResult('run_timestamp', 'runTimestamp');
        $rsm->addScalarResult('player_id', 'playerId');
        $rsm->addScalarResult('raw_player_name', 'rawPlayerName');
        $rsm->addScalarResult('player_country', 'playerCountry');
        $rsm->addScalarResult('map_name', 'mapName');
        $rsm->addScalarResult('map_id', 'mapId');
        $rsm->addScalarResult('worldwide_rank', 'rank');

        $sql = "
            SELECT 
                mt.id,
                mt.run_time,
                mt.run_timestamp,
                p.id AS player_id,
                p.name AS raw_player_name,
                p.country AS player_country,
                m.name AS map_name,
                m.id AS map_id,
                ranked_times.worldwide_rank
            FROM MapTimes mt
            JOIN Player p ON mt.player_id = p.id
            JOIN Maps m ON mt.map_id = m.id
            INNER JOIN (
                SELECT id,
                    RANK() OVER (PARTITION BY map_id, style, type, stage ORDER BY run_time ASC) as worldwide_rank
                FROM MapTimes
            ) ranked_times ON mt.id = ranked_times.id
            WHERE mt.type = 0 AND mt.stage = 0
            ORDER BY mt.run_timestamp DESC
            LIMIT :limit
        ";

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('limit', $limit, \Doctrine\DBAL\ParameterType::INTEGER);

        $results = $query->getResult();


        foreach ($results as $key => $activity) {
            $rawName = $activity['rawPlayerName'] ?? '';
            $cleanName = preg_replace('/^\[[0-9:.-]+\]\s+/', '', $rawName);
            $results[$key]['playerName'] = $cleanName;
            unset($results[$key]['rawPlayerName']);
        }

        return $results;
    }
}