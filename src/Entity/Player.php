<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'Player')]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'steam_id', type: 'bigint', unique: true)]
    private string $steamId;

    #[ORM\Column(type: 'string', length: 32)]
    private string $name;

    #[ORM\Column(type: 'string', length: 2)]
    private string $country;

    #[ORM\Column(name: 'join_date', type: 'integer')]
    private int $joinDate;

    #[ORM\Column(name: 'last_seen', type: 'integer')]
    private int $lastSeen;

    #[ORM\Column(type: 'integer')]
    private int $connections;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSteamId(): string
    {
        return $this->steamId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getJoinDate(): int
    {
        return $this->joinDate;
    }

    public function getLastSeen(): int
    {
        return $this->lastSeen;
    }

    public function getConnections(): int
    {
        return $this->connections;
    }
}