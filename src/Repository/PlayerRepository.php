<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * Get players sorted by their last seen timestamp (most recent first)
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.lastSeen', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get X last active players
     * @return Player[]
     */
    public function findActivePlayers($limit): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.lastSeen', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
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
}