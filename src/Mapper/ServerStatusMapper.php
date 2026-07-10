<?php

namespace App\Mapper;

class ServerStatusMapper
{
    public function map(array $serverInfo, array $players): array
    {
        $isOnline = !empty($serverInfo);

        return [
            'status'       => $isOnline ? 'online' : 'offline',
            'host'         => $serverInfo['HostName'] ?? '### De Surfplank ###',
            'map'          => $serverInfo['Map'] ?? 'Unknown',
            'player_count' => $serverInfo['Players'] ?? 0,
            'max_players'  => $serverInfo['MaxPlayers'] ?? 64,
            'players'      => $players,
        ];
    }
}