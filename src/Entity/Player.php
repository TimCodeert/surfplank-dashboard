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
        return preg_replace('/^\[[0-9:.-]+\]\s+/', '', $this->name);
    }

    public function getCountry(): string
    {
        if ($this->country === 'LL') {
            $this->country = 'BE';
        }
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

    public function getLastSeenAgo(): string
    {
        $timeAgo = time() - $this->lastSeen;

        if ($timeAgo < 1) {
            return 'just now';
        }

        $condition = [
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60      => 'month',
            24 * 60 * 60           => 'day',
            60 * 60                => 'hour',
            60                     => 'minute',
            1                      => 'second'
        ];

        foreach ($condition as $secs => $str) {
            $d = $timeAgo / $secs;

            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
            }
        }

        return 'unknown';
    }
}