<?php

namespace App\Repository;

use App\Entity\Map;
use App\Entity\MapTime;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Map>
 */
class MapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Map::class);
    }

    /**
     * @return Map[] Returns an array of only ranked Map objects
     */
    public function findRankedMaps(): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.ranked = :ranked')
            ->setParameter('ranked', true)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Map
     */
    public function findMapByName($name): ?Map
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Map
     */
    public function findActiveMap(): Map
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.lastPlayed', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * 
     * @param int $playerId
     * @return Map[]
     */
    public function findUncompletedMapsForPlayer(int $playerId): array
        {
            return $this->createQueryBuilder('m')
                ->leftJoin(
                    MapTime::class, 
                    'mt', 
                    'WITH', 
                    'mt.map = m.id AND mt.player = :playerId AND mt.type = 0'
                )
                ->where('mt.id IS NULL AND m.ranked = 1')
                ->setParameter('playerId', $playerId)
                ->orderBy('m.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
}