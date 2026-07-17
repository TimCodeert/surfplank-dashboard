<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'PlayerAchievements')]
#[ORM\UniqueConstraint(columns: ['player_id', 'achievement_key'])]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Player $player;

    #[ORM\Column(name: 'achievement_key', length: 50)]
    private string $achievementKey;

    public function __construct(Player $player, string $achievementKey)
    {
        $this->player = $player;
        $this->achievementKey = $achievementKey;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;
        return $this;
    }

    public function getAchievementKey(): string
    {
        return $this->achievementKey;
    }

    public function setAchievementKey(string $achievementKey): self
    {
        $this->achievementKey = $achievementKey;
        return $this;
    }
}