<?php

namespace App\Service;

use App\Repository\PlayerRepository;
use App\Mapper\ServerStatusMapper;
use xPaw\SourceQuery\SourceQuery;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ServerStatusService
{
    public function __construct(
        private LoggerInterface $logger,
        private PlayerRepository $playerRepository,
        private ServerStatusMapper $statusMapper,
        private CacheInterface $cache, // Toegevoegd
        private string $serverIp,
        private int $serverPort
    ) {}

    public function getServerStatus(): array
    {
        return $this->cache->get('cs2_server_status', function () {
            return $this->refreshCache();
        });
    }

    public function refreshCache(): array
    {
        $serverInfo = [];
        $query = new SourceQuery();

        try {
            $query->Connect($this->serverIp, $this->serverPort, 1, SourceQuery::SOURCE);
            $serverInfo = $query->GetInfo() ?: [];
        } catch (\Exception $e) {
            $this->logger->warning('Failed to retrieve server info: ' . $e->getMessage());
        } finally {
            $query->Disconnect();
        }

        $playerCount = $serverInfo['Players'] ?? 0;
        $players = $this->playerRepository->findActivePlayers($playerCount);
        $statusData = $this->statusMapper->map($serverInfo, $players);

        $this->cache->delete('cs2_server_status');
        $this->cache->get('cs2_server_status', function () use ($statusData) {
            return $statusData;
        });

        return $statusData;
    }
}