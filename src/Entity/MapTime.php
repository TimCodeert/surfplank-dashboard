<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\MapTimeRepository::class)]
#[ORM\Table(name: 'MapTimes')]
class MapTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(name: 'player_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Player $player;

    #[ORM\ManyToOne(targetEntity: Map::class)]
    #[ORM\JoinColumn(name: 'map_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Map $map;

    #[ORM\Column(type: 'smallint')]
    private int $type;

    #[ORM\Column(type: 'smallint')]
    private int $stage;

    #[ORM\Column(name: 'run_time', type: 'integer')]
    private int $runTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getMap(): Map
    {
        return $this->map;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getStage(): int
    {
        return $this->stage;
    }

    public function getRunTime(): int
    {
        return $this->runTime;
    }

    public function isBonus(): bool
    {
        return $this->type > 0;
    }

    public function getBonusNumber(): ?int
    {
        return $this->isBonus() ? $this->stage : null;
    }

    /**
     * Converts server ticks (64 tickrate) to a readable time format (MM:SS.ms)
     */
    public function getFormattedTime(): string
    {
        $tickRate = 64;
        
        $totalSeconds = floor($this->runTime / $tickRate);
        $remainingTicks = $this->runTime % $tickRate;

        $minutes = floor($totalSeconds / 60);
        $seconds = $totalSeconds % 60;

        $milliseconds = floor(($remainingTicks / $tickRate) * 100);

        return sprintf('%02d:%02d.%02d', $minutes, $seconds, $milliseconds);
    }
}