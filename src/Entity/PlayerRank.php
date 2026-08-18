<?php
namespace App\Entity;

use App\Repository\PlayerRankRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRankRepository::class, readOnly: true)]
#[ORM\Table(name: 'v_player_ranks')]
class PlayerRank
{
    #[ORM\Id]
    #[ORM\Column(name: 'player_id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'player_name', type: 'string')]
    private string $playerName;

    #[ORM\Column(name: 'total_finished_maps', type: 'integer')]
    private int $totalFinishedMaps;

    #[ORM\Column(name: 'total_points', type: 'integer')]
    private int $totalPoints = 0;

    #[ORM\Column(name: 'rank_title', type: 'string')]
    private string $rankTitle;

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function getTotalFinishedMaps(): int
    {
        return $this->totalFinishedMaps;
    }

    public function getTotalPoints(): int
    {
        return $this->totalPoints;
    }

    public function getRankTitle(): string
    {
        return $this->rankTitle;
    }
}