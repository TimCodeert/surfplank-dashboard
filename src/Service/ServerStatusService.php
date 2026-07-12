<?php

namespace App\Service;

use App\Repository\PlayerRepository;
use App\Mapper\ServerStatusMapper;
use xPaw\SourceQuery\SourceQuery;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ServerStatusService
{
    public function __construct(
        private LoggerInterface $logger,
        private PlayerRepository $playerRepository,
        private ServerStatusMapper $statusMapper,
        private CacheInterface $cache,
        private string $serverIp,
        private int $serverPort
    ) {}

    public function getServerStatus(): array
    {
        $serverInfo = $this->cache->get('cs2_server_info', function (ItemInterface $item) {
            $item->expiresAfter(30); 
            
            return $this->fetchLiveServerInfo();
        });

        $players = $this->playerRepository->findOnlinePlayers();
        return $this->statusMapper->map($serverInfo, $players);
    }

    private function fetchLiveServerInfo(): array
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

        return $serverInfo;
    }
}