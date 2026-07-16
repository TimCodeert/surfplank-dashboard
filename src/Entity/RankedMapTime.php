<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'v_ranked_maptimes')]
class RankedMapTime
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: MapTime::class, inversedBy: 'rankedData')]
    #[ORM\JoinColumn(name: 'map_time_id', referencedColumnName: 'id')]
    private MapTime $mapTime;

    #[ORM\Column(name: 'worldwide_rank', type: 'integer')]
    private int $worldwideRank;

    #[ORM\Column(name: 'total_completions', type: 'integer')]
    private int $totalCompletions;

    /**
     * @return MapTime
     */
    public function getMapTime(): MapTime
    {
        return $this->mapTime;
    }

    /**
     * @return int
     */
    public function getWorldwideRank(): int
    {
        return $this->worldwideRank;
    }

    /**
     * @return int
     */
    public function getTotalCompletions(): int
    {
        return $this->totalCompletions;
    }
}