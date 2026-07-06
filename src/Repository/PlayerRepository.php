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
    public function getActivePlayers(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.lastSeen', 'DESC')
            ->getQuery()
            ->getResult();
    }
}