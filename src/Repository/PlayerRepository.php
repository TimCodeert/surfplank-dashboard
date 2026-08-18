<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * Get total players
     * @return int
     */
    public function countPlayers(): int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get online players
     * @return Player[]
     */
    public function findOnlinePlayers(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isOnline = :onlineStatus')
            ->setParameter('onlineStatus', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get players grouped by country
     *
     * @return array<int, array{country: string, count: int}>
     */
    public function getPlayerCountByCountry(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.country AS country, COUNT(p.id) AS count')
            ->where('p.country IS NOT NULL')
            ->andWhere("p.country != ''")
            ->groupBy('p.country')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find player by steamId
     * @return Player
     */
    public function findPlayerBySteamId($steamId): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.steamId = :steamId')
            ->setParameter('steamId', $steamId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPaginatedPlayers(
        int $page = 1, 
        int $limit = 40, 
        string $sort = 'lastSeen', 
        string $direction = 'DESC', 
        ?string $search = null
    ): Paginator {
        $qb = $this->createQueryBuilder('p')
            ->select('p AS player', 'COALESCE(r.totalPoints, 0) AS totalPoints', 'COALESCE(r.totalFinishedMaps, 0) AS completions', 'COALESCE(r.rankTitle, \'Newbie\') AS rankTitle')
            ->leftJoin('p.rank', 'r');

        if ($search) {
            $qb->andWhere('p.name LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        $allowedSortColumns = [
            'name'        => 'p.name',
            'country'     => 'p.country',
            'lastSeen'    => 'p.lastSeen',
            'connections' => 'p.connections',
            'completions' => 'completions',
            'points'      => 'totalPoints',
        ];

        $sortOrder = $allowedSortColumns[$sort] ?? 'p.lastSeen';
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $qb->orderBy($sortOrder, $direction);

        $qb->setFirstResult(($page - 1) * $limit)
        ->setMaxResults($limit);

        return new Paginator($qb->getQuery(), false);
    }

}