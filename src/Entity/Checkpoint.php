<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Checkpoints')]
class Checkpoint
{
    #[ORM\Id]
    #[ORM\Column(name: 'maptime_id', type: 'integer')]
    private int $mapTimeId;

    #[ORM\Id]
    #[ORM\Column(name: 'cp', type: 'smallint')]
    private ?int $checkpointNumber = null;

    #[ORM\ManyToOne(targetEntity: MapTime::class, inversedBy: 'checkpoints')]
    #[ORM\JoinColumn(name: 'maptime_id', referencedColumnName: 'id')]
    private ?MapTime $mapTime = null;

    #[ORM\Column(name: 'run_time', type: 'integer', nullable: true, options: ['comment' => 'start_touch'])]
    private ?int $runTime = null;

    #[ORM\Column(name: 'start_vel_x', type: 'decimal', precision: 8, scale: 3, nullable: true)]
    private ?string $startVelX = null;

    #[ORM\Column(name: 'start_vel_y', type: 'decimal', precision: 8, scale: 3, nullable: true)]
    private ?string $startVelY = null;

    #[ORM\Column(name: 'end_vel_x', type: 'decimal', precision: 8, scale: 3, nullable: true)]
    private ?string $endVelX = null;

    #[ORM\Column(name: 'end_vel_y', type: 'decimal', precision: 8, scale: 3, nullable: true)]
    private ?string $endVelY = null;

    #[ORM\Column(name: 'attempts', type: 'integer', nullable: true)]
    private ?int $attempts = null;

    // --- GETTERS ---

    public function getMapTime(): ?MapTime
    {
        return $this->mapTime;
    }

    public function getCheckpointNumber(): ?int
    {
        return $this->checkpointNumber;
    }

    public function getRunTime(): ?int
    {
        return $this->runTime;
    }

    public function getStartVelX(): ?string
    {
        return $this->startVelX;
    }

    public function getStartVelY(): ?string
    {
        return $this->startVelY;
    }

    public function getEndVelX(): ?string
    {
        return $this->endVelX;
    }

    public function getEndVelY(): ?string
    {
        return $this->endVelY;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function getHorizontalStartSpeed(): float
    {
        return round(sqrt(pow((float) $this->startVelX, 2) + pow((float) $this->startVelY, 2)), 3);
    }

    public function getHorizontalEndSpeed(): float
    {
        return round(sqrt(pow((float) $this->endVelX, 2) + pow((float) $this->endVelY, 2)), 3);
    }
}