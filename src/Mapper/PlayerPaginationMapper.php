<?php

namespace App\Mapper;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PlayerPaginationMapper
{
    public function map(Paginator $paginator, int $page, int $limit, string $sort, string $direction, ?string $search): array
    {
        $totalPlayers = count($paginator);
        $maxPages = (int) ceil($totalPlayers / $limit);

        $players = [];
        foreach ($paginator as $item) {
            $player = $item['player'];
            $player->completionsCount = (int) $item['completions']; 
            $players[] = $player;
        }

        return [
            'players'          => $players,
            'totalPlayers'     => $totalPlayers,
            'currentPage'      => $page,
            'maxPages'         => $maxPages,
            'currentSort'      => $sort,
            'currentDirection' => $direction,
            'currentSearch'    => $search,
        ];
    }
}