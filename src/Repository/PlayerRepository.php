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

    public function findPaginatedPlayers(int $page, int $limit, string $sort, string $direction, ?string $search): Paginator
    {
        $qb = $this->createQueryBuilder('p');

        // Calculate completions
        $qb->select('p AS player')
            ->addSelect('(
            SELECT COUNT(mt.id) 
            FROM App\Entity\MapTime mt 
            JOIN mt.map m 
            WHERE mt.player = p.id 
                AND mt.type = 0 
                AND mt.stage = 0 
                AND m.ranked = 1
        ) AS completions');

        if ($search) {
            $qb->andWhere('p.name LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        $allowedSortColumns = ['name' => 'p.name', 'country' => 'p.country', 'lastSeen' => 'p.lastSeen', 'connections' => 'p.connections', 'completions' => 'completions'];
        $sortOrder = $allowedSortColumns[$sort] ?? 'p.lastSeen';
        $direction = strtoupper($direction) === 'ASC' ? 'ASC' : 'DESC';

        $qb->orderBy($sortOrder, $direction);

        $qb->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);

        return new Paginator($qb->getQuery(), true);
    }

}