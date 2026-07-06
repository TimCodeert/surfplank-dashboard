<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Maps')]
class Map
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 64, unique: true)]
    private string $name;

    #[ORM\Column(type: 'smallint')]
    private int $tier;

    #[ORM\Column(type: 'string', length: 64)]
    private string $author;

    #[ORM\Column(type: 'smallint')]
    private int $stages;

    #[ORM\Column(type: 'smallint')]
    private int $bonuses;

    #[ORM\Column(type: 'boolean')]
    private bool $ranked;

    #[ORM\Column(name: 'date_added', type: 'integer')]
    private int $dateAdded;

    #[ORM\Column(name: 'last_played', type: 'integer')]
    private int $lastPlayed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTier(): int
    {
        return $this->tier;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getStages(): int
    {
        return $this->stages;
    }

    public function getBonuses(): int
    {
        return $this->bonuses;
    }

    public function isRanked(): bool
    {
        return $this->ranked;
    }

    public function getDateAdded(): int
    {
        return $this->dateAdded;
    }

    public function getLastPlayed(): int
    {
        return $this->lastPlayed;
    }

    public function isLinear(): bool
    {
        return $this->stages === 0;
    }
}